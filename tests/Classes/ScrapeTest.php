<?php

use Classes\Utils\Scraper;

class ScrapeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LinkCollectionProcessor
     */
    private $scrape;

    public function setup()
    {
        $this->scraper = new Scraper('www.roy.shay');
    }

    /**
     * Test Private Data Members set correctly.
     * Uses Reflection to get at 'em.
     *
     * @return void
     */
    public function testPrivateMembers()
    {
        $reflection_class = new ReflectionClass($this->scraper);

        $property = $reflection_class->getProperty('path');
        $property->setAccessible(true);

        $this->assertEquals('/tmp/', $property->getValue($this->scraper));

        $property = $reflection_class->getProperty('url');
        $property->setAccessible(true);

        $this->assertEquals('www.roy.shay', $property->getValue($this->scraper));
    }

    /**
     * Test we're getting something back from getScrapedData.
     *
     * @return void
     */
    public function testGetScrapedData()
    {
        $this->scraper = new Scraper('http://www.sainsburys.co.uk/webapp/wcs/stores/servlet/CategoryDisplay?listView=true&orderBy=FAVOURITES_FIRST&parent_category_rn=12518&top_category=12518&lang%20Id=44&beginIndex=0&pageSize=20&catalogId=10137&searchTerm=&categoryId=185749&listId=&storeId=10151&promotionId=#langId=44&storeId=10151&catalogId=10137&%20categoryId=185749&parent_category_rn=12518&top_category=12518&pageSize=20&orderBy=FAVOURITES_FIRST&searchTerm=&beginIndex=0&hideFilters=true');

        $json = $this->scraper->getScrapedData();

        $json_array = json_decode($json, true);
        $this->assertTrue(is_array($json_array));
        $this->assertArrayHasKey('results', $json_array);
        $this->assertArrayHasKey('total',   $json_array);
    }


    /**
     * Test getProductSize.
     *
     * @return void
     */
    public function testGetProductSize()
    {
        $reflection_class = new ReflectionClass($this->scraper);
        $method = $reflection_class->getMethod("getProductSize");

        $method->setAccessible(true);

        $this->assertEquals('0kb', $method->invoke($this->scraper));
    }


    /**
     * Test getProductDescription.
     *
     * @return void
     */
    public function testGetProductDescription()
    {
        $reflection_class = new ReflectionClass($this->scraper);
        $method = $reflection_class->getMethod("getProductDescription");

        $method->setAccessible(true);

        $this->assertEquals('', $method->invoke($this->scraper));
    }
}