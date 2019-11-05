<?php
namespace dio\Events;

use dio\Entities\Campaign;
use Symfony\Contracts\EventDispatcher\Event;

class BlacklistUpdated extends Event
{
    protected $campaign;
    protected $oldBlacklist;

    public function __construct(Campaign $campaign, array $oldBlacklist)
    {
        $this->campaign = $campaign;
        $this->oldBlacklist = $oldBlacklist;
    }
}
