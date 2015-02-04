# blender-api-php
Blender Bulk SMS Platform - Send SMS via PHP



### Send SMS to single recipient (see Demo.php)
```php
require_once("bulksms/BulkSMS.php");
# Create client instance
$bulksms = new BulkSMS();
# Login to gateway
$bulksms->login($USERNAME, $PASSWORD);
# Send to a single recipient
$response = $bulksms->singleRecipient("originator", "61400000000", "Test SMS", $ROUTE_ID, "my-reference");
```

### Send same message to multipl recipients (see Demo.php)
```php
require_once("bulksms/BulkSMS.php");
# Create client instance
$bulksms = new BulkSMS();
# Login to gateway
$bulksms->login($USERNAME, $PASSWORD);
# Create batch
$batch = new BatchMessageSingleBody();
# Set originator
$batch->setOriginator("test");
# Set route id
$batch->setRouteId($ROUTE_ID);
# Set message body (for all recipients)
$batch->setBody("Batch unicode message with 漢語");
# Add recipients
$batch->addMSISDN("61000000001");
$batch->addMSISDN("61000000002");
$batch->addMSISDN("61000000003");
# Send message
$responseXml = $bulksms->sendBatch($batch);
```


### Receive SMS (see MOHandler.php)

```php
require_once(dirname(__FILE__) . "/bulksms/DeliveryMessage.php");
$xml = $_POST['xml'];
$incomingMessage = new DeliveryMessage($xml);
$originator = $incomingMessage->getOriginator();
$body = $incomingMessage->getBody();
```
### Receive Receipt (see DRHandler.php)

```php
require_once(dirname(__FILE__) . "/bulksms/DeliveryReceipt.php");
$xml = $_POST['xml'];
$receipt = new DeliveryReceipt($xml);
$myref = $receipt->getClientReference();
$status = $receipt->getStatus();
```