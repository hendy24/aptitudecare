<?php
class Google_Maps extends GoogleMapAPI {

    var $_db_cache_table = '_GEOCODES';


	public function __construct($map_id = 'map', $app_id = 'MyMapApp') {

		if (! db()->table_exists("_GEOCODES") ) {
			$sql = file_get_contents(ENGINE_PROTECTED_PATH . "/var/sql/GoogleMapAPI.sql");
			db()->rawQuery($sql);
		}

		parent::__construct($map_id, $app_id);

		$this->setDSN(db()->dsn);
		//$this->disableOnLoad();

	}


    /**
     * get the geocode lat/lon points from cache for given address
     *
     * @param string $address
     */
    function getCache($address) {
        if(!isset($this->dsn))
            return false;

        $_ret = array();

        // PEAR DB
		$_row = db()->getRowCustom("SELECT lon,lat FROM {$this->_db_cache_table} where address = :address", array(":address" => $address));
        if($_row !== false) {
            $_ret['lon'] = $_row->lon;
            $_ret['lat'] = $_row->lat;
        }

        return !empty($_ret) ? $_ret : false;
    }

    /**
     * put the geocode lat/lon points into cache for given address
     *
     * @param string $address
     * @param string $lon the map latitude (horizontal)
     * @param string $lat the map latitude (vertical)
     */
    function putCache($address, $lon, $lat) {
        if(!isset($this->dsn) || (strlen($address) == 0 || strlen($lon) == 0 || strlen($lat) == 0))
           return false;
        db()->rawQuery("insert into $this->_db_cache_table values ('$address', '$lon', '$lat')");

        return true;

    }

    function getReverseGeocode($lat, $lon) {
		// format this string with the appropriate latitude longitude
		$url = 'http://maps.google.com/maps/geo?q=' . $lat . ',' . $lon . '&output=json&sensor=true_or_false&key=' . $this->api_key;

		// make the HTTP request
		$data = @file_get_contents($url);

		// parse the json response
		$jsondata = json_decode($data,true);
		
		// if we get a placemark array and the status was good, get the addres
		if(is_array($jsondata )&& $jsondata ['Status']['code']==200)
		{
			return $jsondata;
		} else {
			return false;
		}
    }
    
    function getZipFromCoords($lat, $lon) {
    	$jsondata = $this->getReverseGeocode($lat, $lon);
    	if ($jsondata == false) {
    		return false;
    	}
    	$zip = $jsondata['Placemark']['0']['AddressDetails']['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['PostalCode']['PostalCodeNumber'];
    	if (! Validate::is_zipcode($zip)->success()) {
    		$zip = $jsondata['Placemark']['0']['AddressDetails']['Country']['AdministrativeArea']["Locality"]["PostalCode"]["PostalCodeNumber"];
    		if (! Validate::is_zipcode($zip)->success()) {
    			return false;
    		}
    	}
    	return $zip;
    	
    }
    
    
}