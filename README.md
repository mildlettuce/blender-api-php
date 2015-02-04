# blender-api-php
Blender Bulk SMS Platform - Send SMS via PHP

### Send SMS (see Demo.php)
```php
require_once("bulksms/BulkSMS.php");
# Create client instance
$bulksms = new BulkSMS();
# Login to gateway
$bulksms->login($USERNAME, $PASSWORD);
# Send to a single recipient
$response = $bulksms->singleRecipient("originator", "61400000000", "Test SMS", $ROUTE_ID, "my-reference");
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