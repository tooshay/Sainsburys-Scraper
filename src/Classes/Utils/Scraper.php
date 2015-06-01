<?php
namespace Classes\Utils;

use Silex\Application;
use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;

class Scraper
{
    /**
     * @var URL
     */
    private $url;

    /**
     * @var Temp file save path
     */
    private $path = '/tmp/';

    /**
     * @param String $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Scrapes URL for required data.
     *
     * @return JSON
     */
    public function getScrapedData()
    {
        // New Goutte client
        $client  = new Client();
        $crawler = $client->request('GET', $this->url);

        $status_code = $client->getResponse()->getStatus();

        if (200 === $status_code) {
            $total   = 0.00;
            $results = array();
            $nodes   = $crawler->filter('.product');

            $results['results'] = $nodes->each(function($node) use(&$total, $client) {
                $n = $node->filter('.productInner > .productInfoWrapper > .productInfo > h3 > a');

                // Extract price; Tally total
                $price   = str_replace(array('£', '/unit'), '', trim($node->filter('.productInner > .pricingAndTrolleyOptions > .pricing > .pricePerUnit')->text()));
                $total  += $price;

                // Get product Title and Unit Price
                $product = array(
                    'title'      => trim($n->text()),
                    'unit_price' => $price
                    );

                // Get the linked product page
                $linksCrawler   = $n->selectLink(trim($n->text()));
                $link           = $linksCrawler->link();
                $productCrawler = $client->request('GET', $link->getUri());

                $product['size']        = $this->getProductSize($productCrawler);
                $product['description'] = $this->getProductDescription($productCrawler);

                return $product;
            });

            $results['total'] = $total;

            return json_encode($results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
    }

    /**
     * Get Product Page HTML Size
     *
     * @param Crawler productCrawler
     * @return string
     */
    private function getProductSize(Crawler $productCrawler = null)
    {
        $productPageSize = 0;

        /* To get size, as Content-Length is not passed in the header,
           a local copy of the HTML gets temporarly saved locally and 
           measure for size. */
        if (!is_null($productCrawler)) {
            file_put_contents($this->path . 'product.html', $productCrawler->html());
            $content_length  = filesize($this->path . 'product.html');
            $productPageSize = round($content_length / 1024, 2);

            // Get rid of the temp file
            if (file_exists($this->path . 'product.html')) {
                unlink($this->path . 'product.html');
            }
        }

        return $productPageSize . 'kb';
    }

    /**
     * Get Product Description
     *
     * @param Crawler productCrawler
     * @return string
     */
    private function getProductDescription(Crawler $productCrawler = null)
    {
        $description = '';

        if (!is_null($productCrawler)) {
            $productNode = $productCrawler->filter('.tabs > .section');

            if ($productNode->filter('#mainPart > .productText > .longTextItems')->count()) {
                $description = trim($productNode->filter('#mainPart > .productText > .longTextItems')->text());
            } elseif ($productNode->filter('.productText')->count()) {
                $description = trim($productNode->filter('.productText')->first()->text());
            } else {
                $description = $productNode->filter('p')->eq(1)->text();
            }
        }

        return (strlen($description) > 84) ? substr($description, 0, 84) . '...' : $description;
    }
}