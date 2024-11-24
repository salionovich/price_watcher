<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Services\PriceMonitor;

$monitor = new PriceMonitor();
$monitor->monitor();
