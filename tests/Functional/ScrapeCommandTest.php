<?php

namespace tests;

use App\Commands\ScrapeCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class ScrapeCommandTest extends TestCase
{
    public function testCommand()
    {
        $this->markTestSkipped();
        
        $app = new Application();
        $cmd = $app->add(new ScrapeCommand());
        
        $commandTester = new CommandTester($cmd);
        $result = $commandTester->execute(['command' => 'scrape']);
        $this->assertSame(Command::SUCCESS, $result);
        $this->assertSame(Command::SUCCESS, $commandTester->getStatusCode());
    }
}