<?php

namespace dio\Entities;

class Campaign
{
    private $optProps;
    private $id;
    private $publisherBlacklist;

    public function __construct(int $id, OptimizationProps $optProps, array $publisherBlacklist = [])
    {
        $this->id = $id;
        $this->optProps = $optProps;
        $this->publisherBlacklist = $publisherBlacklist;
    }

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
        $this->publisherBlacklist = $blacklist;
    }
}
