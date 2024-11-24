<?php

namespace App\Services;

use App\Database\Database;
use App\Services\PriceParser;

class SubscriptionService
{
    private Database $db;
    private PriceParser $priceParser;

    public function __construct()
    {
        $this->db = new Database();
        $this->priceParser = new PriceParser();
    }

    public function subscribe(string $url, string $email): void
    {
        $existingSubscription = $this->db->getSubscription($url, $email);

        if ($existingSubscription) {
            throw new \Exception('You are already subscribed to this ad.');
        }

        $currentPrice = $this->priceParser->getPrice($url);

        $token = bin2hex(random_bytes(16));

        // Додаємо підписку
        $this->db->addSubscriptionWithToken($url, $email, $currentPrice, $token);

        // Надсилаємо підтвердження email
        $this->sendConfirmationEmail($email, $token);
    }

    private function sendConfirmationEmail(string $email, string $token): void
    {
        $confirmationLink = sprintf(
            'http://price_watcher.loc/confirm?token=%s',
            $token
        );

        $subject = "Confirm your subscription";
        $message = "Please click the link below to confirm your subscription:\n$confirmationLink";

        // Надсилаємо email (використання PHP mail())
        mail($email, $subject, $message);
    }

    public function confirmSubscription(string $token): void
    {
        $subscription = $this->db->getSubscriptionByToken($token);

        if (!$subscription) {
            throw new \Exception('Invalid or expired token.');
        }

        // Оновлюємо статус підтвердження
        $this->db->confirmSubscription($subscription['id']);
    }

    public function getAllSubscriptions(): array
    {
        return $this->db->getAllSubscriptions();
    }

}
