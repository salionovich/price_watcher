<?php

require __DIR__ . '/vendor/autoload.php';

use App\Services\SubscriptionService;

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


if ($requestUri === '/subscribe' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['url'] ?? null;
    $email = $_POST['email'] ?? null;

    if ($url && $email) {
        $subscriptionService = new SubscriptionService();
        try {
            http_response_code(200);
            $subscriptionService->subscribe($url, $email);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Subscription successful']);
        } catch (Exception $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input data']);
    }
} elseif ($requestUri === '/confirm' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $token = $_GET['token'] ?? null;
    if ($token) {
        $subscriptionService = new SubscriptionService();
        try {
            http_response_code(200);
            $subscriptionService->confirmSubscription($token);
            echo 'Email confirmation successful';
        } catch (Exception $e) {
            http_response_code(400);
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        http_response_code(400);
        echo 'Invalid token';
    }
} elseif ($requestUri === '/subscriptions' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $subscriptionService = new SubscriptionService();
    try {
        $subscriptions = $subscriptionService->getAllSubscriptions();

        $formattedSubscriptions = array_map(function ($subscription) {
            return [
                'id' => $subscription['id'],
                'url' => $subscription['url'],
                'email' => $subscription['email'],
                'last_price' => $subscription['last_price'],
                'is_confirmed' => (bool)$subscription['is_confirmed'],
            ];
        }, $subscriptions);

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($formattedSubscriptions, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    } catch (Exception $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(404);
    header('Content-Type: text/plain');
    echo 'Not Found';
}
