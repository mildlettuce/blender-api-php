<?php
namespace Blender\Client;
require_once(dirname(__FILE__) . "/CountryCode.php");

class AutoRoute {

    private $routePricing = array();
    private $countryRoute = null;
    private $routePricingByCountryId = null;

    function __construct($BulkSMS) {
        $this->routePricing = $BulkSMS->getRoutePricing();
        //print_r($this->routePricing);
        $this->init();
    }

    private function init() {
        $this->countryRoute = array();
        $this->routePricingByCountryId = array();

        foreach ($this->routePricing as $rp) {
            $countryId = $rp->getCountryId();
            $this->countryRoute[$countryId] = $rp->getUserRouteId();
            $this->routePricingByCountryId[$countryId] = $rp;
        }
    }

    public function getRouteId($recipient) {
        $rp = $this->getRoutePricing($recipient);
        return $rp->getUserRouteId();
    }

    public function getRoutePricing($recipient) {
        $countryId = CountryCode::getCountryId($recipient);

        if (isset($this->routePricingByCountryId[$countryId])) {
            $routePricing = $this->routePricingByCountryId[$countryId];
            return $routePricing;
        } else {
            throw new \Exception("ERROR: Unable to get routeId for {$recipient}");
        }

    }

    public function printRoutes() {
        foreach($this->routePricingByCountryId as $key => $cr) {
            echo "Country name: "  . CountryCode::getCountryNameById($cr->getCountryId()) . "\t" . $cr->getPrice() . "\n";
        }
    }

}