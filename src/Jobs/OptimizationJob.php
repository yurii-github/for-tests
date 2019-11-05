<?php
namespace dio\Jobs;

use dio\Repositories\CampaignDataSource;
use dio\Repositories\EventsDataSource;

class OptimizationJob
{
    public function run()
    {
        // array of Campaign objects
        $campaigns = (new CampaignDataSource())->getCampaigns();

        foreach ((new EventsDataSource())->getEventsSince("2 weeks ago") as $event) {
            // START HERE
        }
    }
}
