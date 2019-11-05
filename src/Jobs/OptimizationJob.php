<?php
namespace dio\Jobs;

use dio\Entities\Campaign;
use dio\Entities\Event;
use dio\Events\BlacklistUpdated;
use dio\Repositories\CampaignDataSource;
use dio\Repositories\EventsDataSource;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class OptimizationJob
{
    protected $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function run()
    {
        $campaigns = (new CampaignDataSource())->getCampaigns();
        $events = (new EventsDataSource())->getEventsSince("2 weeks ago");
        $blacklist = [];
        $findCampaign = function (int $id) use ($campaigns) {
            foreach ($campaigns as $campaign) {
                if ($campaign->getId() === $id) {
                    return $campaign;
                }
            }
            return null;
        };
        $getBlacklistItem = function (Campaign $campaign, Event $event) use ($blacklist) {
            if (isset($blacklist[$campaign->getId()][$event->getPublisherId()])) {
                return $blacklist[$campaign->getId()][$event->getPublisherId()];
            }
            return ['sourceCount' => 0, 'measureCount' => 0];
        };

        // build blacklist
        foreach ($events as $event) {
            if ($campaign = $findCampaign($event->getCampaignId())) {
                $blacklistItem = $getBlacklistItem($campaign, $event);
                $blacklist[$campaign->getId()][$event->getPublisherId()] = [
                    'sourceCount' => $blacklistItem['sourceCount'] + (int)($campaign->getOptimizationProps()->sourceEvent === $event->getType()),
                    'measureCount' => $blacklistItem['measureCount'] + (int)($campaign->getOptimizationProps()->measuredEvent === $event->getType()),
                ];
            }
        }

        // apply threshold - if a publisher has less sourceEvents that the threshold , then she should not be blacklisted
        foreach ($blacklist as $campaignId => $campaignBlacklist) {
            foreach ($campaignBlacklist as $publisherId => $blacklistItem) {
                if ($blacklistItem['sourceCount'] < $findCampaign($campaignId)->getOptimizationProps()->threshold) {
                    unset($blacklist[$campaignId][$publisherId]);
                }
            }
        }

        // apply ratio - blacklisted publishers can only be removed from the blacklist if they cross the ratio
        foreach ($blacklist as $campaignId => $campaignBlacklist) {
            foreach ($campaignBlacklist as $publisherId => $blacklistItem) {
                $ratio = round(100 * ($blacklistItem['measureCount'] / $blacklistItem['sourceCount']), 2);
                if ($ratio > $findCampaign($campaignId)->getOptimizationProps()->ratioThreshold) {
                    unset($blacklist[$campaignId][$publisherId]);
                }
            }
        }

        // update blacklist
        foreach ($campaigns as $campaign) {
            if ($blacklistItem = $blacklist[$campaign->getId()] ?? null) {
                $oldBlackList = $campaign->getBlackList();
                $newBlacklist = array_keys($blacklistItem);
                $campaign->saveBlacklist($newBlacklist);
                // B. publishers are notified with an email whenever they are added or removed from a campaign's blacklist
                $this->dispatcher->dispatch(new BlacklistUpdated($campaign, $oldBlackList));
            }
        }
    }
}
