<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Database\Database;
use App\Services\SubscriptionService;

/**
 * @coversDefaultClass \App\Services\SubscriptionService
 */
class SubscriptionTest extends TestCase
{
    private Database $db;
    private SubscriptionService $service;

    protected function setUp(): void
    {
        $this->db = new Database();
        $this->service = new SubscriptionService();

        $this->db->deleteSubscription(
            "https://www.olx.ua/d/uk/obyavlenie/toyota-scion-avtomat-1-5b-IDVKEc6.html",
            "test@gmail.com"
        );
    }

    /**
     * @covers \App\Services\SubscriptionService::subscribe
     */
    public function testSubscription()
    {
        $url = "https://www.olx.ua/d/uk/obyavlenie/toyota-scion-avtomat-1-5b-IDVKEc6.html";
        $email = "test@gmail.com";

        $this->service->subscribe($url, $email);

        $subscription = $this->db->getSubscription($url, $email);
        $this->assertNotNull($subscription);
        $this->assertEquals($email, $subscription['email']);
        $this->assertNotEmpty($subscription['confirmation_token']);
        $this->assertNotEmpty($subscription['last_price']);
    }

    /**
     * @covers \App\Services\SubscriptionService::confirmSubscription
     */
    public function testSubscriptionConfirmation()
    {
        $url = "https://www.olx.ua/d/uk/obyavlenie/toyota-scion-avtomat-1-5b-IDVKEc6.html";
        $email = "test@gmail.com";

        $this->service->subscribe($url, $email);

        $subscription = $this->db->getSubscription($url, $email);
        $this->assertNotEmpty($subscription['confirmation_token']);

        $this->service->confirmSubscription($subscription['confirmation_token']);

        $updatedSubscription = $this->db->getSubscription($url, $email);
        $this->assertEquals(1, $updatedSubscription['is_confirmed']);
    }
}
