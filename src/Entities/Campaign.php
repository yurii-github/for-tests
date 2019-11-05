<?php

namespace dio\Entities;

use dio\Entities\Publisher\PublisherBlacklist;

class Campaign
{
    /** @var OptimizationProps */
    private $optProps;
    /** @var int */
    private $id;
    /** @var array[publisherId] */
    private $publisherBlacklist = [];

    public function getId()
    {
        return $this->id;
    }

    public function getOptimizationProps()
    {
        return $this->optProps;
    }

    public function getBlackList()
    {
        return $this->publisherBlacklist;
    }

    public function saveBlacklist($blacklist)
    {
        // don't implement
    }
}
