<?php

namespace App\Services;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;

class PriceParser
{
    public function getPrice(string $url): string
    {
        $client = HttpClient::create();
        $response = $client->request('GET', $url);
        $html = $response->getContent();

        $crawler = new Crawler($html);
        $price = $crawler->filter('[data-testid="ad-price-container"] h3')->text();

        return $price;
    }
}
