<?php

namespace App\Services;

use App\Database\Database;

class PriceMonitor
{
    private Database $db;
    private PriceParser $parser;

    public function __construct()
    {
        $this->db = new Database();
        $this->parser = new PriceParser();
    }

    public function monitor(): void
    {
        $subscriptions = $this->db->getAllSubscriptions();

        foreach ($subscriptions as $subscription) {
            $url = $subscription['url'];
            $currentPrice = $this->parser->getPrice($url);

            if ($currentPrice !== $subscription['last_price']) {
                $this->db->updatePrice($url, $currentPrice);
                $this->sendEmail($subscription['email'], $url, $currentPrice);
            }
        }
    }

    private function sendEmail(string $email, string $url, string $price): void
    {
        mail($email, "Price Changed!", "The price of the ad at $url has changed to $price.");
    }
}
