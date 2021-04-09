<?php

namespace App\Commands;

use App\Crawler\CrawlerInterface;
use App\Crawler\MagpiehqCrawler;
use App\Crawler\MagpiehqTestDataCrawler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ScrapeCommand extends Command
{
    protected $crawlers = [
        'magpiehq' => MagpiehqCrawler::class,
        'magpiehq-test' => MagpiehqTestDataCrawler::class
    ];

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->checkAllRequiredOptionsAreNotEmpty($input);

        $crawler = $this->getCrawler($input->getOption('crawler'));

        $output->setVerbosity(OutputInterface::VERBOSITY_NORMAL);
        $output->writeln("Fetching data..");
        $products = $crawler->getAllProducts();
        $output->writeln("Fetched {$products->count()} products.");
        $output->writeln("Filtering data..");
        $products = $products->dedupedProducts();
        $output->writeln("{$products->count()} products left after filter was applied.");
        $filename = dirname(__DIR__, 2) . '/output.json';
        $output->writeln("Dumping data into JSON file '$filename'..");
        file_put_contents($filename, json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        $output->writeln("Data was dumped into JSON file successfully.");
        return Command::SUCCESS;
    }


    protected function configure(): void
    {
        $this
            ->setName('scrape')
            ->addOption('crawler', null, InputOption::VALUE_REQUIRED, " 'magpiehq' or 'magpiehq-test'")
            ->setDescription('Scrapes test data from magpiehq.com');
    }

    
    protected function getCrawler(string $name): CrawlerInterface
    {
        if (!array_key_exists($name, $this->crawlers)) {
            throw new \InvalidArgumentException("Unknown crawler!");
        }

        return new $this->crawlers[$name]();
    }

    
    // https://github.com/symfony/symfony/issues/14716
    protected function checkAllRequiredOptionsAreNotEmpty(InputInterface $input)
    {
        $options = $this->getDefinition()->getOptions();
        foreach ($options as $option) {
            $name = $option->getName();
            $value = $input->getOption($name);
            if ($option->isValueRequired() && empty($value)) {
                throw new \InvalidArgumentException(sprintf('The required option %s is not set', $name));
            }
        }
    }
}

