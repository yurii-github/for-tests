<?php
namespace dio\Repositories;

use dio\Entities\Event;

class EventsDataSource
{
    /**
     * @param string $time PHP time format
     * @return Event[]
     */
    public function getEventsSince($time)
    {
        $dt = new \DateTime($time);

        return [];
    }
}
