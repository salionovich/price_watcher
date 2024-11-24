<?php

namespace App\Database;

class Database
{
    private \PDO $pdo;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/database.php';

        // Формування DSN рядка
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['database'],
            $config['charset']
        );

        try {
            $this->pdo = new \PDO($dsn, $config['username'], $config['password'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ]);
        } catch (\PDOException $e) {
            throw new \RuntimeException('Database connection failed: ' . $e->getMessage());
        }
    }

    public function getSubscription(string $url, string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM subscriptions WHERE url = :url AND email = :email");
        $stmt->execute(['url' => $url, 'email' => $email]);

        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function addSubscription(string $url, string $email, string $price): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO subscriptions (url, email, last_price) VALUES (:url, :email, :price)"
        );
        $stmt->execute(['url' => $url, 'email' => $email, 'price' => $price]);
    }

    public function updatePrice(string $url, string $price): void
    {
        $stmt = $this->pdo->prepare("UPDATE subscriptions SET last_price = :price WHERE url = :url");
        $stmt->execute(['price' => $price, 'url' => $url]);
    }

    public function getAllSubscriptions(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM subscriptions");
        return $stmt->fetchAll();
    }

    public function addSubscriptionWithToken(string $url, string $email, string $price, string $token): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO subscriptions (url, email, last_price, confirmation_token) 
         VALUES (:url, :email, :price, :token)"
        );
        $stmt->execute(['url' => $url, 'email' => $email, 'price' => $price, 'token' => $token]);
    }

    public function getSubscriptionByToken(string $token): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM subscriptions WHERE confirmation_token = :token");
        $stmt->execute(['token' => $token]);

        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function confirmSubscription(int $id): void
    {
        $stmt = $this->pdo->prepare("UPDATE subscriptions SET is_confirmed = 1, confirmation_token = NULL WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function clearSubscriptions(): void
    {
        $this->pdo->exec("DELETE FROM subscriptions");
    }

    public function deleteSubscription(string $url, string $email): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM subscriptions WHERE url = :url AND email = :email");
        $stmt->execute(['url' => $url, 'email' => $email]);
    }
}
