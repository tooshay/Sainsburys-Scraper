<?php

namespace Classes\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Classes\Utils\Scraper;

/**
 * This command scrapes a provided URL.
 *
 * @author  Roy Shay <roy.shay@gmail.com>
 */
class ScrapeCommand extends Command
{
    /**
     * Configure command arguments.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('scrape')
            ->addArgument(
                'url',
                InputArgument::REQUIRED,
                'What are we scraping?'
            )
            ->setDescription("Scrapes the provided URL.");
    }

    /**
     * Execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $scraper = new Scraper($input->getArgument('url'));
        $output->writeln($scraper->getScrapedData());
    }
}