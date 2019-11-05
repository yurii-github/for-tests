<?php

namespace dio\Jobs;

use dio\Events\BlacklistUpdated;
use dio\Repositories\CampaignDataSource;
use dio\Repositories\EventsDataSource;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class OptimizationJob
{
    // bitwise
    const PASSED_NONE = 0;
    const PASSED_THRESHOLD = 1;
    const PASSED_RATIO = 2;
    const PASSED_ALL = 3; // bitwise 1|2

    protected $dispatcher;
    protected $dsCampaigns;
    protected $dsEvents;

    public function __construct(EventDispatcherInterface $dispatcher, CampaignDataSource $dsCampaigns, EventsDataSource $dsEvents)
    {
        $this->dispatcher = $dispatcher;
        $this->dsCampaigns = $dsCampaigns;
        $this->dsEvents = $dsEvents;
    }

    public function run()
    {
        $campaigns = $this->dsCampaigns->getCampaigns();
        $events = $this->dsEvents->getEventsSince("2 weeks ago");
        $blacklist = [];
        $findCampaign = function (int $id) use ($campaigns) {
            foreach ($campaigns as $campaign) {
                if ($campaign->getId() == $id) {
                    return $campaign;
                }
            }
            return null;
        };

        // build blacklist
        foreach ($events as $event) {
            if ($campaign = $findCampaign($event->getCampaignId())) {
                if (!empty($blacklist[$campaign->getId()][$event->getPublisherId()])) {
                    $blacklistItem = $blacklist[$campaign->getId()][$event->getPublisherId()];
                } else {
                    $blacklistItem = ['sourceCount' => 0, 'measureCount' => 0, 'pass' => static::PASSED_NONE];
                }
                $blacklist[$campaign->getId()][$event->getPublisherId()] = [
                    'sourceCount' => $blacklistItem['sourceCount'] + (int)($campaign->getOptimizationProps()->sourceEvent === $event->getType()),
                    'measureCount' => $blacklistItem['measureCount'] + (int)($campaign->getOptimizationProps()->measuredEvent === $event->getType()),
                    'pass' => static::PASSED_NONE
                ];
            }
        }

        // apply threshold - if a publisher has less sourceEvents that the threshold , then she should not be blacklisted
        foreach ($blacklist as $campaignId => $campaignBlacklist) {
            foreach ($campaignBlacklist as $publisherId => $blacklistItem) {
                if ($blacklistItem['sourceCount'] <= $findCampaign($campaignId)->getOptimizationProps()->threshold) {
                    $blacklist[$campaignId][$publisherId]['pass'] |= static::PASSED_THRESHOLD;
                }
            }
        }

        // apply ratio - blacklisted publishers can only be removed from the blacklist if they cross the ratio
        foreach ($blacklist as $campaignId => $campaignBlacklist) {
            foreach ($campaignBlacklist as $publisherId => $blacklistItem) {
                $ratio = $blacklistItem['sourceCount'] === 0 ? 1.0 : $blacklistItem['measureCount'] / $blacklistItem['sourceCount'];
                if ($ratio > $findCampaign($campaignId)->getOptimizationProps()->ratioThreshold) {
                    $blacklist[$campaignId][$publisherId]['pass'] |= static::PASSED_RATIO;
                }
            }
        }

        // filter - final blacklist control
        foreach ($blacklist as $campaignId => $campaignBlacklist) {
            foreach ($campaignBlacklist as $publisherId => $blacklistItem) {
                // NOTE: change control logic here!
                if ($blacklistItem['pass'] === static::PASSED_ALL ||
                    $blacklistItem['pass'] === static::PASSED_RATIO ||
                    $blacklistItem['pass'] === static::PASSED_THRESHOLD ) {
                    unset($blacklist[$campaignId][$publisherId]);
                }
            }
        }

        // update blacklist
        foreach ($campaigns as $campaign) {
            $oldBlackList = $campaign->getBlackList();
            $newBlacklist = array_keys($blacklist[$campaign->getId()] ?? []);
            $campaign->saveBlacklist($newBlacklist);
            // B. publishers are notified with an email whenever they are added or removed from a campaign's blacklist
            $this->dispatcher->dispatch(new BlacklistUpdated($campaign, $oldBlackList));
        }
    }
}
