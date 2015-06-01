<?php

use Classes\Console\ScrapeCommand;

class ScrapeCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testConfigure()
    {
        $scrapeCommand = new ScrapeCommand();

        $this->assertEquals('Scrapes the provided URL.', $scrapeCommand->getDescription());
    }
}