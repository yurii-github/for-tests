<?php
namespace dio\Entities;

class Event
{
    const TYPE_INSTALL = 'install';
    const TYPE_APP_OPEN = 'app_open';
    const TYPE_PURCHASE = 'purchase';
    // ...

    private $type;
    private $campaignId;
    private $publisherId;

    public function getType()
    {
        // for example "install"
        return $this->type;
    }
    public function getTs()
    {
        return $this->ts;
    }
    public function getCampaignId()
    {
        return $this->campaignId;
    }
    public function getPublisherId()
    {
        return $this->publisherId;
    }
}
