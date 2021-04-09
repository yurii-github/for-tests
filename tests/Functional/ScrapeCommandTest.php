<?php

namespace tests;

use App\Commands\ScrapeCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class ScrapeCommandTest extends TestCase
{
    protected string $outputFilename;
    
    protected function setUp(): void
    {
        $this->outputFilename = dirname(__DIR__,2).'/output.json'; // TODO: use virtualFS
        if (file_exists($this->outputFilename)) {
            unlink($this->outputFilename);
        }
    }

    public function testCommand()
    {
        $app = new Application();
        $cmd = $app->add(new ScrapeCommand());
        
        $commandTester = new CommandTester($cmd);
        $result = $commandTester->execute(['command' => 'scrape', '--crawler' => 'magpiehq-test'],);
        $this->assertSame(Command::SUCCESS, $result);
        $this->assertSame(Command::SUCCESS, $commandTester->getStatusCode());
    }
}