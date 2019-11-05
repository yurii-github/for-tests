<?php

namespace tests;

use dio\Entities\Campaign;
use dio\Entities\Event;
use dio\Entities\OptimizationProps;
use dio\Jobs\OptimizationJob;
use dio\Repositories\CampaignDataSource;
use dio\Repositories\EventsDataSource;
use Symfony\Component\EventDispatcher\EventDispatcher;

class OptimizationJobTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
    }

    public function test_run()
    {
        $logfile = dirname(__DIR__).'/email.log';
        @unlink($logfile);

        $campaigns = [];
        $events = [];
        // at least 1 purchases for 2 installs, does not fail on 3 installs w/o purchases
        $opt = new OptimizationProps(3, Event::TYPE_INSTALL, Event::TYPE_PURCHASE, 1 / 2);

        $mainCampaign = 1;
        //
        $publisherOKAfterChange1 = 1;
        $publisherOK = 2;
        $publisherAlmostBlock = 3;
        $publisherBlock4 = 4;
        $publisherUnblock = 123;

        $campaigns[$mainCampaign] = new Campaign($mainCampaign, $opt, [$publisherUnblock]);

        // ---
        $events[] = new Event(Event::TYPE_APP_OPEN, 100, 100);

        $events[] = new Event(Event::TYPE_INSTALL, $mainCampaign, $publisherOKAfterChange1);
        $events[] = new Event(Event::TYPE_INSTALL, $mainCampaign, $publisherOKAfterChange1);
        $events[] = new Event(Event::TYPE_INSTALL, $mainCampaign, $publisherOKAfterChange1);
        $events[] = new Event(Event::TYPE_INSTALL, $mainCampaign, $publisherOKAfterChange1);
        $events[] = new Event(Event::TYPE_PURCHASE, $mainCampaign, $publisherOKAfterChange1);
        $events[] = new Event(Event::TYPE_PURCHASE, $mainCampaign, $publisherOKAfterChange1);
        // ---
        $events[] = new Event(Event::TYPE_INSTALL, $mainCampaign, $publisherOK);
        $events[] = new Event(Event::TYPE_INSTALL, $mainCampaign, $publisherOK);
        $events[] = new Event(Event::TYPE_INSTALL, $mainCampaign, $publisherOK);
        $events[] = new Event(Event::TYPE_PURCHASE, $mainCampaign, $publisherOK);
        $events[] = new Event(Event::TYPE_PURCHASE, $mainCampaign, $publisherOK);
        $events[] = new Event(Event::TYPE_PURCHASE, $mainCampaign, $publisherOK);
        // ---
        $events[] = new Event(Event::TYPE_INSTALL, $mainCampaign, $publisherAlmostBlock);
        $events[] = new Event(Event::TYPE_INSTALL, $mainCampaign, $publisherAlmostBlock);
        $events[] = new Event(Event::TYPE_INSTALL, $mainCampaign, $publisherAlmostBlock);
        // ---
        $events[] = new Event(Event::TYPE_INSTALL, $mainCampaign, $publisherBlock4);
        $events[] = new Event(Event::TYPE_INSTALL, $mainCampaign, $publisherBlock4);
        $events[] = new Event(Event::TYPE_INSTALL, $mainCampaign, $publisherBlock4);
        $events[] = new Event(Event::TYPE_INSTALL, $mainCampaign, $publisherBlock4);

        $dispatcher = new EventDispatcher();

        // TODO: email and event mocks
        $dispatcher->addListener(\dio\Events\BlacklistUpdated::class, [new \dio\EventListeners\SendEmailToPublisher(), 'onEvent']);

        $dsCampaigns = $this->createMock(CampaignDataSource::class);
        $dsCampaigns->expects($this->any())->method('getCampaigns')->willReturn($campaigns);

        $dsEvents = $this->createMock(EventsDataSource::class);
        $dsEvents->expects($this->any())->method('getEventsSince')->willReturn($events);

        $this->assertEquals([$publisherUnblock], $campaigns[$mainCampaign]->getBlackList());

        (new OptimizationJob($dispatcher, $dsCampaigns, $dsEvents))->run();

        $this->assertNotEmpty($campaigns[$mainCampaign]->getBlackList());
        $this->assertEquals([$publisherOKAfterChange1, $publisherBlock4], $campaigns[$mainCampaign]->getBlackList());

        $this->assertEquals(":ADDED: 1\n:ADDED: 4\n:REMOVED: 123\n", file_get_contents($logfile));

        $opt->ratioThreshold = 2 / 5;
        (new OptimizationJob($dispatcher, $dsCampaigns, $dsEvents))->run();
        $this->assertEquals([$publisherBlock4], $campaigns[$mainCampaign]->getBlackList());

        $this->assertEquals(":ADDED: 1\n:ADDED: 4\n:REMOVED: 123\n:REMOVED: 1\n", file_get_contents($logfile));
    }
}
