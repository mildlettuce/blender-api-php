<?php

require_once(dirname(__FILE__) . "/bulksms/BulkSMS.php");

// Defaults
$ROUTE_ID = "ee806618-2d0b-4d8c-8ddc-cf66a2029784";
$USERNAME = "demo";
$PASSWORD = "demo";

$recipient = "61000000000";
$bulksms = new BulkSMS();

// Login
$bulksms->login($USERNAME, $PASSWORD);

// EXAMPLE 1 - Send to single recipient
# Uncomment to run
// $bulksms->singleRecipient("test", $recipient, "hello <asaf !!esting " . date("H:i:s"), $ROUTE_ID, "reference");

# ----------------------------------------------------------------------------------------------------------------

// EXAMPLE 2 - Send same message to batch
$batch = new BatchMessageSingleBody();
$batch->setOriginator("test");
$batch->setRouteId($ROUTE_ID);
$batch->setBody("Batch single <message> in 漢語");
$batch->addMSISDN($recipient);
// You can add as many as you like.

# Dont filter duplicates - will send two messages to same recipient
// $batch->addMSISDN($recipient);
// $batch->setFilterDuplicaets(false);

# Send to Mailing List
// $batch->addMailingList("Mailing-List-Id");

# Send to Contact
// $batch->addContact("contact-id")

# Do not filter optouts
// $batch->setFilterOptouts(false);


# Uncomment to run
//$responseXml = $bulksms->sendBatch($batch);
//echo "Response: " . $responseXml;

# ----------------------------------------------------------------------------------------------------------------

// EXAMPLE 3 - Send batch of different messages to different recipients

$batch = new BatchMessageMultiBody();
// Set defaults
$batch->setOriginator("test");
$batch->setRouteId($ROUTE_ID);
$batch->setBody("Batch single message");

$batch->addRecipient1($recipient);
$batch->addRecipien2($recipient, "different message");
$batch->addRecipien2($recipient, "different message with unicode טוקיו 東京(Tokyo)");
$longtext = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse auctor turpis at nunc rutrum, eget sodales turpis molestie. Nullam mattis sit amet urna et tristique. Vivamus nec justo et dui sed. 200+ chars";
$batch->addRecipient("orignew", $recipient, $longtext);
# Uncomment to run
$responseXml =  $bulksms->sendBatch($batch);
echo "Response: {$responseXml}\n";

