<?php
namespace Blender\Client;
/**
 * Created by IntelliJ IDEA.
 * User: lettuce
 * Date: 15/10/15
 * Time: 9:32 PM
 */
class CountryCode {

    private static $instance = null;

    private $countryNames = array();
    private $countryNamesById = array();
    private $countryIds = array();

    function __construct() {
        $filename = dirname(__FILE__) . "/cp.txt";
        $data = file($filename);

        foreach ($data as $line) {
            $arr = explode("\t", $line);
            $name = trim($arr[0]);
            $prefix = trim($arr[1]);
            $countryId = trim($arr[2]);

            $this->countryNames[$prefix] = $name;
            $this->countryIds[$prefix] = $countryId;
            $this->countryNamesById[$countryId] = $name;
        }
    }

    public static function getInstance() {
        if (CountryCode::$instance == null) {
            CountryCode::$instance = new CountryCode();
        }

        return CountryCode::$instance;
    }

    public function __getId($msisdn) {
        $prefix = substr($msisdn, 0, 4);
        if (isset($this->countryIds[$prefix])) {
            return $this->countryIds[$prefix];
        }

        throw new \Exception("No route found for msisdn ({$prefix} / {$msisdn})");
    }

    public function __getName($msisdn) {
        $prefix = substr($msisdn, 0, 4);
        if (isset($this->countryNames[$prefix])) {
            return $this->countryNames[$prefix];
        }

        throw new \Exception("No country found for msisdn ({$prefix} {$msisdn}) [{$this->countryNames[$prefix]}]");
    }


    public static function getCountryId($msisdn) {
        $inst = CountryCode::getInstance();
        return $inst->__getId($msisdn);
    }

    public static function getCountryName($msisdn) {
        $inst = CountryCode::getInstance();
        return $inst->__getName($msisdn);
    }

    public function _getCountryNameById($countryId) {
        if(isset($this->countryNamesById[$countryId]))
            return $this->countryNamesById[$countryId];

        throw new \Exception("No country found for id $countryId");
    }

    public static function getCountryNameById($countryId) {
        $inst = CountryCode::getInstance();
        return $inst->_getCountryNameById($countryId);
    }
}
