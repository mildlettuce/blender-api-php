# blender-api-php
Blender Bulk SMS Platform - Send SMS via PHP



### Send SMS to single recipient
```php
require_once("bulksms/BulkSMS.php");
# Create client instance
$bulksms = new Blender\Client\BulkSMS();
# Login to gateway
$bulksms->login($USERNAME, $PASSWORD);
# Send to a single recipient
$response = $bulksms->singleRecipient("originator", "61400000000", "Test SMS", $ROUTE_ID, "my-reference");
```
see [demo/Demo.php](demo/Demo.php)

### Get Route ID by Recipient/MSISDN
```php
require_once("bulksms/BulkSMS.php");
require_once("bulksms/AutoRoute.php");
# Create client instance
$bulksms = new Blender\Client\BulkSMS();
# Login to gateway
$bulksms->login($USERNAME, $PASSWORD);
# Initialize Autoroute
$autoRoute = new AutoRoute($bulksms);
# Get Route ID
$ROUTE_ID = $autoRoute->getRouteId($recipient);
```
see [demo/AutoRouting.php](demo/AutoRouting.php)


### Get Route ID by Country
```php
require_once("bulksms/BulkSMS.php");
# Create client instance
$bulksms = new Blender\Client\BulkSMS();
# Login to gateway
$bulksms->login($USERNAME, $PASSWORD);
# Get route id
$ROUTE_ID = $bulksms->getRouteIdByCountry("Australia");
```
see [demo/Routing.php](demo/Routing.php)

### Send same message to multiple recipients
```php
require_once("bulksms/BulkSMS.php");
# Create client instance
$bulksms = new Blender\Client\BulkSMS();
# Login to gateway
$bulksms->login($USERNAME, $PASSWORD);
# Create batch
$batch = new Blender\Client\BatchMessageSingleBody();
# Set originator
$batch->setOriginator("test");
# Set route id
$batch->setRouteId($ROUTE_ID);
# Set message body (for all recipients)
$batch->setBody("Batch unicode message with 漢語");

## Recipients ##
$batch->addMSISDN("61000000001");
$batch->addMSISDN("61000000002");
$batch->addMSISDN("61000000003");

# Send message
$responseXml = $bulksms->sendBatch($batch);
```
see [demo/Demo.php](demo/Demo.php)

### Send multiple messages to multiple recipients
```php
require_once("bulksms/BulkSMS.php");
# Create client instance
$bulksms = new Blender\Client\BulkSMS();
# Login to gateway
$bulksms->login($USERNAME, $PASSWORD);
# Create batch
$batch = new Blender\Client\BatchMessageMultiBody();
# Set default originator (you can override per recipient)
$batch->setOriginator("test");
# Set default route id (you can override per recipient)
$batch->setRouteId($ROUTE_ID);
# Set default message body (you can override per recipient)
$batch->setBody("Batch unicode message with 漢語");

## Recipients ##
# Add recipient
$batch->addRecipient1($recipient);
# Add recipient, override message
$batch->addRecipient2($recipient, "different message");
# Add recipient with unicode message
$batch->addRecipient2($recipient, "different message with unicode טוקיו 東京(Tokyo)");
# Add new message with different originator, recipient and long body
$longtext = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse auctor turpis at nunc rutrum, eget sodales turpis molestie. Nullam mattis sit amet urna et tristique. Vivamus nec justo et dui sed. 200+ chars";
$batch->addRecipient("orignew", $recipient, $longtext);

# Send message
$responseXml = $bulksms->sendBatch($batch);
```
see [demo/Demo.php](demo/Demo.php)

### Schedule message
```php
require_once("bulksms/BulkSMS.php");
$bulksms = new Blender\Client\BulkSMS();
$bulksms->login($USERNAME, $PASSWORD);
$batch = new Blender\Client\BatchMessageMultiBody();

# Schedule message with date/time/timezone
$batch->setSchedule("2017-01-01T15:24:04", "Australia/Melbourne");
```
see [demo/Demo.php](demo/Demo.php)

### Receive SMS

```php
require_once(dirname(__FILE__) . "/bulksms/DeliveryMessage.php");
$xml = $_POST['xml'];
$incomingMessage = new Blender\Client\DeliveryMessage($xml);
$originator = $incomingMessage->getOriginator();
$body = $incomingMessage->getBody();
```
see [demo/MOHandler.php](demo/MOHandler.php)

### Receive Receipt

```php
require_once(dirname(__FILE__) . "/bulksms/DeliveryReceipt.php");
$xml = $_POST['xml'];
$receipt = new Blender\Client\DeliveryReceipt($xml);
$myref = $receipt->getClientReference();
$status = $receipt->getStatus();
```
see [demo/DRHandler.php](demo/DRHandler.php)

### Check Balance

```php
require_once("bulksms/BulkSMS.php");
# Create client instance
$bulksms = new Blender\Client\BulkSMS();
# Login to gateway
$bulksms->login($USERNAME, $PASSWORD);
# Get Balance
$balance = $bulksms->getBalance();
```
see [demo/BalanceAlert.php](demo/BalanceAlert.php)