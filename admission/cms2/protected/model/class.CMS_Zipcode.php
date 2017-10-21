<?php
class CMS_Zipcode extends CMS_Table {
	
	public static $table = "zipcode";
	protected static $createStructure = false;
	
	public static $usaStates = array("AL" => "Alabama", "AK" => "Alaska", "AZ" => "Arizona", "AR" => "Arkansas",
		"CA" => "California", "CO" => "Colorado", "CT" => "Connecticut", "DC" => "District of Columbia", "DE" => "Delaware",
		"FL" => "Florida", "GA" => "Georgia", "HI" => "Hawaii", "ID" => "Idaho", "IL" => "Illinois",
		"IN" => "Indiana", "IA" => "Iowa", "KS" => "Kansas", "KY" => "Kentucky", "LA" => "Louisiana",
		"ME" => "Maine", "MD" => "Maryland", "MA" => "Massachusetts", "MI" => "Michigan",
		"MN" => "Minnesota", "MS" => "Mississippi", "MO" => "Missouri", "MT" => "Montana",
		"NE" => "Nebraska", "NV" => "Nevada", "NH" => "New Hampshire", "NJ" => "New Jersey",
		"NM" => "New Mexico", "NY" => "New York", "NC" => "North Carolina", "ND" => "North Dakota",
		"OH" => "Ohio", "OK" => "Oklahoma", "OR" => "Oregon", "PA" => "Pennsylvania",
		"RI" => "Rhode Island", "SC" => "South Carolina", "SD" => "South Dakota", "TN" => "Tennessee",
		"TX" => "Texas", "UT" => "Utah", "VT" => "Vermont", "VA" => "Virginia", "WA" => "Washington",
		"WV" => "West Virginia", "WI" => "Wisconsin", "WY" => "Wyoming");
	
	public static $caProvinces = array(
	"BC"=>"British Columbia",
	"ON"=>"Ontario",
	"NF"=>"Newfoundland",
	"NS"=>"Nova Scotia",
	"PE"=>"Prince Edward Island",
	"NB"=>"New Brunswick",
	"QC"=>"Quebec",
	"MB"=>"Manitoba",
	"SK"=>"Saskatchewan",
	"AB"=>"Alberta",
	"NT"=>"Northwest Territories",
	"YT"=>"Yukon Territory"); 	
	
	public static $countries = array(
	"US"=>"UNITED STATES",
	"AF"=>"AFGHANISTAN",
	"AX"=>"ALAND ISLANDS",
	"AL"=>"ALBANIA",
	"DZ"=>"ALGERIA",
	"AS"=>"AMERICAN SAMOA",
	"AD"=>"ANDORRA",
	"AO"=>"ANGOLA",
	"AI"=>"ANGUILLA",
	"AQ"=>"ANTARCTICA",
	"AG"=>"ANTIGUA AND BARBUDA",
	"AR"=>"ARGENTINA",
	"AM"=>"ARMENIA",
	"AW"=>"ARUBA",
	"AU"=>"AUSTRALIA",
	"AT"=>"AUSTRIA",
	"AZ"=>"AZERBAIJAN",
	"BS"=>"BAHAMAS",
	"BH"=>"BAHRAIN",
	"BD"=>"BANGLADESH",
	"BB"=>"BARBADOS",
	"BY"=>"BELARUS",
	"BE"=>"BELGIUM",
	"BZ"=>"BELIZE",
	"BJ"=>"BENIN",
	"BM"=>"BERMUDA",
	"BT"=>"BHUTAN",
	"BO"=>"BOLIVIA",
	"BA"=>"BOSNIA AND HERZEGOVINA",
	"BW"=>"BOTSWANA",
	"BV"=>"BOUVET ISLAND",
	"BR"=>"BRAZIL",
	"IO"=>"BRITISH INDIAN OCEAN TERRITORY",
	"BN"=>"BRUNEI DARUSSALAM",
	"BG"=>"BULGARIA",
	"BF"=>"BURKINA FASO",
	"BI"=>"BURUNDI",
	"KH"=>"CAMBODIA",
	"CM"=>"CAMEROON",
	"CA"=>"CANADA",
	"CV"=>"CAPE VERDE",
	"CI"=>"CâTE D'IVOIRE",
	"KY"=>"CAYMAN ISLANDS",
	"CF"=>"CENTRAL AFRICAN REPUBLIC",
	"TD"=>"CHAD",
	"CL"=>"CHILE",
	"CN"=>"CHINA",
	"CX"=>"CHRISTMAS ISLAND",
	"CC"=>"COCOS (KEELING) ISLANDS",
	"CO"=>"COLOMBIA",
	"KM"=>"COMOROS",
	"CG"=>"CONGO",
	"CD"=>"CONGO, THE DEMOCRATIC REPUBLIC of THE",
	"CK"=>"COOK ISLANDS",
	"CR"=>"COSTA RICA",
	"HR"=>"CROATIA",
	"CU"=>"CUBA",
	"CY"=>"CYPRUS",
	"CZ"=>"CZECH REPUBLIC",
	"DK"=>"DENMARK",
	"DJ"=>"DJIBOUTI",
	"DM"=>"DOMINICA",
	"DO"=>"DOMINICAN REPUBLIC",
	"EC"=>"ECUADOR",
	"EG"=>"EGYPT",
	"SV"=>"EL SALVADOR",
	"GQ"=>"EQUATORIAL GUINEA",
	"ER"=>"ERITREA",
	"EE"=>"ESTONIA",
	"ET"=>"ETHIOPIA",
	"FK"=>"FALKLAND ISLANDS (MALVINAS)",
	"FO"=>"FAROE ISLANDS",
	"FJ"=>"FIJI",
	"FI"=>"FINLAND",
	"FR"=>"FRANCE",
	"GF"=>"FRENCH GUIANA",
	"PF"=>"FRENCH POLYNESIA",
	"TF"=>"FRENCH SOUTHERN TERRITORIES",
	"GA"=>"GABON",
	"GM"=>"GAMBIA",
	"GE"=>"GEORGIA",
	"DE"=>"GERMANY",
	"GH"=>"GHANA",
	"GI"=>"GIBRALTAR",
	"GR"=>"GREECE",
	"GL"=>"GREENLAND",
	"GD"=>"GRENADA",
	"GP"=>"GUADELOUPE",
	"GU"=>"GUAM",
	"GT"=>"GUATEMALA",
	"GN"=>"GUINEA",
	"GW"=>"GUINEA-BISSAU",
	"GY"=>"GUYANA",
	"HT"=>"HAITI",
	"HM"=>"HEARD ISLAND AND MCDONALD ISLANDS",
	"VA"=>"HOLY SEE (VATICAN CITY STATE)",
	"HN"=>"HONDURAS",
	"HK"=>"HONG KONG",
	"HU"=>"HUNGARY",
	"IS"=>"ICELAND",
	"IN"=>"INDIA",
	"ID"=>"INDONESIA",
	"IR"=>"IRAN ISLAMIC REPUBLIC of",
	"IQ"=>"IRAQ",
	"IE"=>"IRELAND",
	"IL"=>"ISRAEL",
	"IT"=>"ITALY",
	"JM"=>"JAMAICA",
	"JP"=>"JAPAN",
	"JO"=>"JORDAN",
	"KZ"=>"KAZAKHSTAN",
	"KE"=>"KENYA",
	"KI"=>"KIRIBATI",
	"KP"=>"KOREA DEMOCRATIC PEOPLE\'S REPUBLIC of",
	"KR"=>"KOREA REPUBLIC of",
	"KW"=>"KUWAIT",
	"KG"=>"KYRGYZSTAN",
	"LA"=>"LAO PEOPLE\'S DEMOCRATIC REPUBLIC",
	"LV"=>"LATVIA",
	"LB"=>"LEBANON",
	"LS"=>"LESOTHO",
	"LR"=>"LIBERIA",
	"LY"=>"LIBYAN ARAB JAMAHIRIYA",
	"LI"=>"LIECHTENSTEIN",
	"LT"=>"LITHUANIA",
	"LU"=>"LUXEMBOURG",
	"MO"=>"MACAO",
	"MK"=>"MACEDONIA, THE FORMER YUGOSLAV REPUBLIC of",
	"MG"=>"MADAGASCAR",
	"MW"=>"MALAWI",
	"MY"=>"MALAYSIA",
	"MV"=>"MALDIVES",
	"ML"=>"MALI",
	"MT"=>"MALTA",
	"MH"=>"MARSHall ISLANDS",
	"MQ"=>"MARTINIQUE",
	"MR"=>"MAURITANIA",
	"MU"=>"MAURITIUS",
	"YT"=>"MAYOTTE",
	"MX"=>"MEXICO",
	"FM"=>"MICRONESIA, FEDERATED STATES of",
	"MD"=>"MOLDOVA, REPUBLIC of",
	"MC"=>"MONACO",
	"MN"=>"MONGOLIA",
	"MS"=>"MONTSERRAT",
	"MA"=>"MOROCCO",
	"MZ"=>"MOZAMBIQUE",
	"MM"=>"MYANMAR",
	"NA"=>"NAMIBIA",
	"NR"=>"NAURU",
	"NP"=>"NEPAL",
	"NL"=>"NETHERLANDS",
	"AN"=>"NETHERLANDS ANTILLES",
	"NC"=>"NEW CALEDONIA",
	"NZ"=>"NEW ZEALAND",
	"NI"=>"NICARAGUA",
	"NE"=>"NIGER",
	"NG"=>"NIGERIA",
	"NU"=>"NIUE",
	"NF"=>"NORFOLK ISLAND",
	"MP"=>"NORTHERN MARIANA ISLANDS",
	"NO"=>"NORWAY",
	"OM"=>"OMAN",
	"PK"=>"PAKISTAN",
	"PW"=>"PALAU",
	"PS"=>"PALESTINIAN TERRITORY, OCCUPIED",
	"PA"=>"PANAMA",
	"PG"=>"PAPUA NEW GUINEA",
	"PY"=>"PARAGUAY",
	"PE"=>"PERU",
	"PH"=>"PHILIPPINES",
	"PN"=>"PITCAIRN",
	"PL"=>"POLAND",
	"PT"=>"PORTUGAL",
	"PR"=>"PUERTO RICO",
	"QA"=>"QATAR",
	"RE"=>"REUNION",
	"RO"=>"ROMANIA",
	"RU"=>"RUSSIAN FEDERATION",
	"RW"=>"RWANDA",
	"SH"=>"SAINT HELENA",
	"KN"=>"SAINT KITTS AND NEVIS",
	"LC"=>"SAINT LUCIA",
	"PM"=>"SAINT PIERRE AND MIQUELON",
	"VC"=>"SAINT VINCENT AND THE GRENADINES",
	"WS"=>"SAMOA",
	"SM"=>"SAN MARINO",
	"ST"=>"SAO TOME AND PRINCIPE",
	"SA"=>"SAUDI ARABIA",
	"SN"=>"SENEGAL",
	"CS"=>"SERBIA AND MONTENEGRO",
	"SC"=>"SEYCHELLES",
	"SL"=>"SIERRA LEONE",
	"SG"=>"SINGAPORE",
	"SK"=>"SLOVAKIA",
	"SI"=>"SLOVENIA",
	"SB"=>"SOLOMON ISLANDS",
	"SO"=>"SOMALIA",
	"ZA"=>"SOUTH AFRICA",
	"GS"=>"SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS",
	"ES"=>"SPAIN",
	"LK"=>"SRI LANKA",
	"SD"=>"SUDAN",
	"SR"=>"SURINAME",
	"SJ"=>"SVALBARD AND JAN MAYEN",
	"SZ"=>"SWAZILAND",
	"SE"=>"SWEDEN",
	"CH"=>"SWITZERLAND",
	"SY"=>"SYRIAN ARAB REPUBLIC",
	"TW"=>"TAIWAN PROVINCE of CHINA",
	"TJ"=>"TAJIKISTAN",
	"TZ"=>"TANZANIA UNITED REPUBLIC of",
	"TH"=>"THAILAND",
	"TL"=>"TIMOR-LESTE",
	"TG"=>"TOGO",
	"TK"=>"TOKELAU",
	"TO"=>"TONGA",
	"TT"=>"TRINIDAD AND TOBAGO",
	"TN"=>"TUNISIA",
	"TR"=>"TURKEY",
	"TM"=>"TURKMENISTAN",
	"TC"=>"TURKS AND CAICOS ISLANDS",
	"TV"=>"TUVALU",
	"UG"=>"UGANDA",
	"UA"=>"UKRAINE",
	"AE"=>"UNITED ARAB EMIRATES",
	"GB"=>"UNITED KINGDOM",
	"US"=>"UNITED STATES",
	"UM"=>"UNITED STATES MINOR OUTLYING ISLANDS",
	"UY"=>"URUGUAY",
	"UZ"=>"UZBEKISTAN",
	"VU"=>"VANUATU",
	"VE"=>"VENEZUELA",
	"VN"=>"VIETNAM",
	"VG"=>"VIRGIN ISLANDS BRITISH",
	"VI"=>"VIRGIN ISLANDS U.S.",
	"WF"=>"WallIS AND FUTUNA",
	"EH"=>"WESTERN SAHARA",
	"YE"=>"YEMEN",
	"ZM"=>"ZAMBIA",
	"ZW"=>"ZIMBABWE"); 
	
	
	function __construct() {
	}
	
	public static function getStateName($str) {
		return static::$usaStates[$str];
	}
	
	public function getStateCode($str) {
		
	}
	
	
	public static function mysqlHaversine($lat = 0, $lon = 0, $measure = "miles") { 
		if ($measure == "kilometers") {
			// SQL FOR KILOMETERS
	
			$sql = "( 6371 * acos( cos( radians( {$lat} ) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians( {$lon} ) ) + sin( radians( {$lat} ) ) * sin( radians( latitude ) ) ) ) ";
	
		} elseif ($measure == "miles") {
			// SQL FOR MILES
	
			$sql = "( 3959 * acos( cos( radians( {$lat} ) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians( {$lon} ) ) + sin( radians( {$lat} ) ) * sin( radians( latitude ) ) ) ) ";
		}
		return $sql;
	}


	public static function getZipsInRange($zip, $radius, $measure = "miles") {
		$mainZip = static::getZip($zip);
		
		$sql = "SELECT zipcode, " . static::mysqlHaversine($mainZip->latitude, $mainZip->longitude, $measure) . " AS distance FROM `" . static::$table . "` HAVING distance <= {$radius} ORDER BY distance";
	
		$stmt = db()->db->prepare($sql);
		$stmt->execute();
	
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	
	}
	
	public static function getDistance($lat_A, $long_A, $lat_B, $long_B) {
		$distance = sin(deg2rad($lat_A))
						* sin(deg2rad($lat_B))
						+ cos(deg2rad($lat_A))
						* cos(deg2rad($lat_B))
						* cos(deg2rad($long_A - $long_B));
	
		$distance = (rad2deg(acos($distance))) * 69.09;
	
		return $distance;
	}	
	
	/*
	 *  Expects:
	 * 
	 *  Boise, Idaho 83702
	 *  or
	 *  Boise, Idaho
	 *  or
	 *  New York, New York 10025
	 *  or
	 *  New York, New York
	 *  or
	 *  Black Rock City, Nevada
	 * 
	 */
	public static function parseLocationQuery($string) {
		$string = trim($string);

		/* split by comma */
		$parts = preg_split("/,/", $string);
		
		/* clean up */
		foreach ($parts as $k=> $v) {
			$parts[$k] = trim($v);
		}
		
		$retval["city"] = $parts[0];
		$subparts = preg_split("/\s+/", $parts[1]);
		$last_part = array_pop(array_slice($subparts, -1));
		if (is_numeric($last_part)) {		// we found a zip code at the end
			$retval["zip"] = array($last_part);		// "zip" should be an array of zips, in this case we only have one.
			$retval["state"] = implode(" ", array_slice($subparts, 0, count($subparts) - 1));
		} else {
			$retval["state"] = $parts[1];
			$retval["zip"] = static::getZipByCity($retval["city"], $retval["state"], 0);
		}
		return $retval;
	}
	
	public function __strip_commas(&$val) {
		$val = preg_replace("/\s*,\s*/", "", $val);
	}
	
	public function getCitiesByState($state) {
		$obj = static::generate();
		$sql = "SELECT distinct city FROM ". static::$table . " WHERE `state`=:state and `city`!=:city order by `city`";
		return $obj->fetchCustom($sql, array(
			":state" => $state,
			":city" => ''
		));
	}
	
	public static function getZip($zip) {
		$obj = static::generate();
		return current($obj->fetch(array('zipcode' => $zip)));
	}
	
	public static function getCityStateByZip($zip) {
		$z = static::getZip($zip);
		return $z->NZ;
	}
	
	/* By default, only returns the first result, but can return some or all (set $count=0 for all) */
	public function getZipByCity($city, $state, $count = 1) {
		$obj = static::generate();
		$sql = "SELECT * FROM " . static::$table . " WHERE lower(city)=lower(:city) AND lower(state)=lower(:state)";
		$results = $obj->fetchCustom($sql, array(
			":city" => $city,
			":state" => $state
		));
		if ($count == 1) {
			return current($results);
		} elseif ($count == 0) {
			return $results;
		} else {
			if (is_natural($count)) {
				return array_slice($results, 0, $count);
			} else {
				return current($results);
			}
		}
	}
	
	public function getZipDistance($zip1, $zip2) {
		$z1 = static::getZip($zip1);
		$z2 = static::getZip($zip2);
		return static::getDistance($z1->geo_lat, $z1->geo_lon, $z2->geo_lat, $z2->geo_lon);
	}
		
}