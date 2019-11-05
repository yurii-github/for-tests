<?php
namespace dio\Jobs;

use dio\Entities\Event;

class OptimizationJob
{
    public function run()
    {
        $campaignDS = new CampaignDataSource();

        // array of Campaign objects
        $campaigns = $campaignDS->getCampaigns();


        $eventsDS = new EventsDataSource();
        /** @var Event $event */
        foreach ($eventsDS->getEventsSince("2 weeks ago") as $event) {
            // START HERE
        }
    }
}
