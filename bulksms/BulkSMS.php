<?php

require_once(dirname(__FILE__) . "/BatchMessageMultiBody.php");
require_once(dirname(__FILE__) . "/BatchMessageSingleBody.php");

class BulkSMS {
    CONST MSG_SINGLE = "single";
    CONST MSG_BATCH = "batch";
    private static $url = "https://apps.rawmobility.com/bulksms/xmlapi/";
    private static $USER_AGENT = "BlenderClient/1.0";
    private static $HTTP_TIMEOUT = 15;
    private $session = "";

    public function login($username, $password) {
        $url = BulkSMS::$url . "login/" . urldecode($username) . "/" . urlencode($password);
        $xml = $this->getRequest($url);
        $doc = new DOMDocument();
        $doc->loadXML($xml);
        $this->checkResponse($doc);

        $this->session = BulkSMS::xpathValue($doc, "/xaresponse/session");
    }

    public function getRequest($url) {
//        echo "URL: " . $url;
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_TIMEOUT, BulkSMS::$HTTP_TIMEOUT);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_USERAGENT, BulkSMS::$USER_AGENT);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);

        /* Make the request and check the result. */
        $content = curl_exec($c);
        $status = curl_getinfo($c, CURLINFO_HTTP_CODE);

        if ($errno = curl_errno($c)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}\n";
        }

        if ($status != 200)
            throw new Exception(sprintf('Unexpected HTTP return code %d\n' . $content, $status));


        return $content;
    }

    public static function checkResponse($doc) {
        $xpath = new DOMXPath($doc);
        $query = "/xaresponse/authentication/code";
        $code = BulkSMS::xpathValue($doc, $query);

        if ($code != "0") {
            $query = "/xaresponse/authentication/text";
            $text = BulkSMS::xpathValue($doc, $query);
            throw new Exception("Invalid Auth Responses: {$text} ({$code})");
        }

        return true;
    }

    public static function xpathValue($doc, $query, $default = null) {
        $xpath = new DOMXPath($doc);
        $nodes = $xpath->query($query);
        if ($nodes->length == 0)
            return $default;

        $node = $nodes->item(0);
        return $node->nodeValue;
    }

    public function sendSingle($originator, $recipient, $body, $routeId, $reference = null) {
        $recipient = new BatchRecipientMultiBody($originator, $recipient, $body, $reference, $routeId);
        $dom = new DOMDocument('1.0', "UTF-8");
        $node = $dom->importNode($recipient->toXml("message"), true);

        $dom->appendChild($node);
        $xml = $dom->saveXml();

        $result = $this->postXml(BulkSMS::MSG_SINGLE, $xml);

//        echo "Got result: {$result}\n";
        return $result;
    }

    public function postXml($type, $xml) {
        $url = BulkSMS::$url . $this->session . "/send/sms/" . $type;

//        echo "URL: {$url}\n";
//        echo "XML: {$xml}\n";
        $data = "xml=" . urlencode(trim($xml));

//        echo "DATA: {$data}";

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_HEADER, false);
        curl_setopt($c, CURLOPT_TIMEOUT, BulkSMS::$HTTP_TIMEOUT);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_USERAGENT, BulkSMS::$USER_AGENT);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
        if ($this->contains_any_multibyte($xml))
            curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=utf-8'));

        /* Make the request and check the result. */
        $content = curl_exec($c);
        $status = curl_getinfo($c, CURLINFO_HTTP_CODE);

        if ($status != 200)
            throw new Exception(sprintf('Unexpected HTTP return code %d\n' . $content, $status));


        return $content;
    }

    function contains_any_multibyte($string) {
        return !mb_check_encoding($string, 'ASCII') && mb_check_encoding($string, 'UTF-8');
    }

    public function sendBatch($batch) {
        $dom = new DOMDocument('1.0', "UTF-8");
        $node = $dom->importNode($batch->toXml(), true);

        $dom->appendChild($node);
        $xml = $dom->saveXml();

        $result = $this->postXml(BulkSMS::MSG_BATCH, $xml);

//        echo "Got result: {$result}\n";
        return $result;

    }
}
