<?php

require_once(dirname(__FILE__) . "/../bulksms/BulkSMS.php");
require_once(dirname(__FILE__) . "/../bulksms/AutoRoute.php");

$bulksms = new Blender\Client\BulkSMS();

$USERNAME = "demo";
$PASSWORD = "demo";

// Login
$bulksms->login($USERNAME, $PASSWORD);

$autoRoute = new \Blender\Client\AutoRoute($bulksms);

//$autoRoute->printRoutes();

$testNumbers = array(
    "61417188345",
    "44398938433",
    "12798723829"
);

foreach ($testNumbers as $recipient) {
    $routeId = $autoRoute->getRouteId($recipient);
    echo "Route id for {$recipient}: {$routeId}\n";
}