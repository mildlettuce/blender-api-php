<?
require_once(dirname(__FILE__) . "/../bulksms/BulkSMS.php");
require_once(dirname(__FILE__) . "/../bulksms/AutoRoute.php");
$minBalance = 5;
$username = "demo";
$password = "demo";
$alertPhone = "61400000000";

$bulksms = new \Blender\Client\BulkSMS();
$bulksms->login($username, $password);
$autoRoute = new \Blender\Client\AutoRoute($bulksms);

$balance = $bulksms->getBalance();


if($balance < $minBalance) {
    $routeId = $autoRoute->getRouteId($alertPhone);
    $result = $bulksms->sendSingle("RawMobility", $alertPhone, "Balance Alert: Your current balance is under {$minBalance} ({$balance}), please top up ASAP.", $routeId);
} else {
    echo "All good ({$balance})\n";
    
}
