<?php

namespace dio\Entities;

class Campaign
{
    /** @var  OptimizationProps $optProps */
    private $optProps;

    /** @var  int */
    private $id;

    private $publisherBlacklist = [];

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
