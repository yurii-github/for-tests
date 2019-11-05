<?php
namespace dio\Entities;

class OptimizationProps
{
    public $threshold;
    public $sourceEvent;
    public $measuredEvent;
    public $ratioThreshold;

    public function __construct(int $threshold, string $sourceEvent, string $measuredEvent, float $ratioThreshold)
    {
        $this->threshold = $threshold;
        $this->sourceEvent = $sourceEvent;
        $this->measuredEvent = $measuredEvent;
        $this->ratioThreshold = $ratioThreshold;
    }
}
