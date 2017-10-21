<?php

function strip_newlines($str) {
	return str_replace("\r", "", str_replace("\n", "", str_replace("\r\n", "", $str)));
}

function renderEscape($str) {
	return htmlspecialchars($str, ENT_QUOTES, SMARTY_RESOURCE_CHAR_SET);
}

function is_CLI() {
	return php_sapi_name() == 'cli';
}

function redirect($url) {
	session_write_close();
	header("Location: $url");
	exit;
}

if (! function_exists('style_debug')) {
	function style_debug($filename, $line) {
		$basename = basename($filename);
		$dirname = dirname($filename);
		$styled_msg = "<span style='font-weight: bold;'>$dirname/<span style='color:red'>$basename</span>:<span style='color:blue'>$line</span></span>";
		return $styled_msg;
	}
}

function vd($data) {
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}

function br2nl($string) {
	return preg_replace('#<br\s*?/?>#i', "\n", $string); 
}

function arr_trim(&$item) {
	$item = trim($item);
}

function json_return($data) {
	header("Content-type: text/javascript");
	echo json_encode($data);
	session_write_close();
	exit;
}

function return_json($data) {
	json_return($data);
}

function getAge($p_strDate) {
    list($Y,$m,$d) = explode("-",$p_strDate);
    $years = date("Y") - $Y;
    if( date("md") < $m.$d ) { $years--; }
    return $years;
}

function strip_only_tags($str, $tags, $stripContent=false) {
    $content = '';
    if(!is_array($tags)) {
        $tags = (strpos($str, '>') !== false ? explode('>', str_replace('<', '', $tags)) : array($tags));
        if(end($tags) == '') array_pop($tags);
    }
    foreach($tags as $tag) {
        if ($stripContent)
             $content = '(.+</'.$tag.'(>|\s[^>]*>)|)';
         $str = preg_replace('#</?'.$tag.'(>|\s[^>]*>)'.$content.'#is', '', $str);
    }
    return $str;
}

function posessive($str) {
	if (preg_match("/s$/i", $str)) {
		return $str . "'";
	} else {
		return $str . "'s";
	}
}

function ordinal($num) {
	$last=substr($num,-1);
	if($last>3 or $last==0) $ext='th';
	else if($last==3) $ext='rd';
	else if($last==2) $ext='nd';
	else $ext='st';
	return $num.$ext;
}


// DEPRECATED
function generate_pubid($length = 10) {
	$pubid = random_string($length);
	return $pubid;
}

//DEPRECATED
function is_pubid($val) {
	//based on a random string, length 10, available chars are 0123456789abcdfghjkmnpqrstvwxyz
	if (preg_match("/[A-Za-z0-9]{10}/", $val) || preg_match("/[A-Za-z0-9]{50}/", $val)) {
		return true;
	}
	return false;
}

/**
 * datetimeTZ
 * 
 * @param String $zone eg "America/New_York" or "UTC"
 * @param String $str  "now" or, eg. "+2 hours" or "-1 year" or any date/time string. Must be expressed in server's time zone.
 * 
 * @return String    Y-m-d H:i:s formatted datetime string, converted to the timezone requested as $zone
 */
function datetimeTZ($zone, $str = "now", $format = "Y-m-d H:i:s") {
		
	if (is_null($str) ) {
		$dt = new Datetime("now");
	}
	else {
		$dt = new Datetime($str);
	}

	$tz = new DateTimeZone($zone);
	$dt->setTimezone($tz);
	//return $dt->format($format) . " GMT";
	return $dt->format($format);
}

function datetime($stamp = null) {
	if (! is_null($stamp) ) {
		return date("Y-m-d H:i:s", $stamp);
	}
	else {
		return date("Y-m-d H:i:s");
	}
}

function datetime_format($datetime) {
	return strftime("%b %e, %Y %l:%M %p", strtotime($datetime));
}

function datetime_format_fromUTC($datetime) {
	return strftime_fromUTC("%b %e, %Y %l:%M %p", strtotime($datetime));
}

/**
 * Returns formatted date using date() according to globally defined timezone,
 * assuming $timestamp is in UTC
 * 
 * @param String $format    format according to PHP date()
 * @param int $timestamp UTC timestamp
 * 
 * @return string
 */
function date_fromUTC($format, $timestamp) {
	return date($format, strtotime(date("Y-m-d H:i:s", $timestamp) . " GMT"));
}

/**
 * Returns formatted date using strftime() according to globally defined timezone,
 * assuming $timestamp is in UTC
 * 
 * @param String $format    format according to PHP date()
 * @param int $timestamp UTC timestamp
 * 
 * @return string
 */
function strftime_fromUTC($format, $timestamp) {
	return strftime($format, strtotime(date("Y-m-d H:i:s", $timestamp) . " GMT"));
}


function clsname($str) {
	return preg_replace("/\s/", "_", ucwords(preg_replace("/_/", " ", $str)));
}


function tableToModel($table) {
	//the table "product" should map to the class "Product".
	if (class_exists(ucfirst($table))) {
		return "CMS_" . clsname($table);
	}
}

function modelToTable($model) {
	if (class_exists($model)) {
		$obj = new $model;
		return $obj->getTable();
	} else {
		return false;
	}
}

/* Takes "product_category" and returns "Product Category" */
function guesslabel($str) {
	$str = ucwords(implode(" ", preg_split("/_/", $str)));
	$str = str_replace("Datetime", "Date/Time", $str);
	return $str;
}




/* $only can be "numeric" or "char", or NULL to include both */
function random_string ($length = 8, $only = NULL) {
  // start with a blank password
  $password = "";

  // define possible characters
  if ($only == "numeric")
	  $possible = "0123456789";
  elseif ($only == "char")
	  $possible = "abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
  else
	  $possible = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

  // set up a counter
  $i = 0;

  // add random characters to $password until $length is reached
  while ($i < $length) {
    // pick a random character from the possible ones
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);

    // we don't want this character if it's already in the password
    if (!strstr($password, $char)) {
      $password .= $char;
      $i++;
    }

  }

  // done!
  return $password;

}

/* This function is from the PHP.net documentation/manual */
function stripslashes_deep($value) {
        $value = is_array($value) ?
                    array_map('stripslashes_deep', $value) :
                    stripslashes($value);

        return $value;
}

function get_filename_ext($filename, $tolower = false) {
	$ext = substr(strrchr($filename, "."), 1);
	if ($tolower == true) {
		$ext = strtolower($ext);
	}
	return $ext;
}

function Rmkdir($path, $default_mode = NULL) {
    $exp=explode("/",$path);
    $way='';
    foreach($exp as $n) {
        $way.=$n.'/';
        if (!file_exists($way)) {
        	if (! is_null($default_mode) ) {
	            if (! @mkdir($way, $default_mode) ) {
					throw new Exception("Could not create directory {$way}. Please check your filesystem permissions.");
				}
	         } else {
	         	if (! @mkdir($way) ) {
					throw new Exception("Could not create directory {$way}. Please check your filesystem permissions.");
				}
	         }
        }
    }
}

function csv_explode($str, $delim = ',', $qual = "\"") {
  $len = strlen($str);
  $inside = false;
  $word = '';
  for ($i = 0; $i < $len; ++$i) {
    if ($str[$i]==$delim && !$inside) {
      $out[] = $word;
      $word = '';
    } else if ($inside && $str[$i]==$qual && ($i<$len && $str[$i+1]==$qual)) {
      $word .= $qual;
      ++$i;
    } else if ($str[$i] == $qual) {
      $inside = !$inside;
    } else {
      $word .= $str[$i];
    }
  }
  $out[] = $word;

  return $out;
}

function armor($obj) {
	return base64_encode(serialize($obj));
}

function unarmor($str) {
	return unserialize(base64_decode($str));
}

function to_cents($input) {
	//we always want to start with a string. input coming in from $_POST or from the DB is usually a string, but
	//we ultimately don't know where this is coming from.
	$as_string = (string) $input;

	//sanitize: trim whitespace, remove dollar signs, remove commas.
	$clean = str_replace(array(",","$"), "", trim($as_string));

	//split into dollars and cents pieces -- PHP will make each an integer, so multiplication below will be clean.
	list ($dollars, $cents) = explode('.', $clean);
	$cents = 100 * $dollars + $cents;

	//return
	return $cents;
}

function to_dollars($cents) {
	//we always want to start with an integer.
	$as_int = (int) $cents;

	//divide by 100 and make sure there's only two decimal points.
	$dollars = number_format($as_int / 100, 2, ".", "");

	//danger!! In PHP, $dollars is now a string. Do not try and use this in a calculation,
	//because PHP will cast it to a float. store it in the DB in a decimal() field,
	//write to the screen, but DON'T DO MATH.
	return $dollars;
}

function money($dollars) {
	return number_format($dollars, 2, '.', ',');
}

function getUSAStates() {
	return array ("" => "", "AL" => "Alabama", "AK" => "Alaska", "AZ" => "Arizona", "AR" => "Arkansas",
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
}

function getCAProvinces() {
	return array(
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
}

function getCountries(Array $limit = null) {
	$choices = array("" => "",
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
"MH"=>"MARSHALL ISLANDS",
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
"PS"=>"PALESTINIAN TERRITORIES",
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
	
	if (! is_null($limit) ) {
		$selected = array();
		foreach ($limit as $abbr) {
			if (isset($choices[$abbr])) {
				$selected[$abbr] = $choices[$abbr];
			}
		}
		return $selected;
	} else {
		return $choices;
	}
	
	
}

function getTimezones() {
	return DateTimeZone::listIdentifiers();
}

// interval in minutes, 12h or 24h
function getClockTimes($interval = 15, $mode = "12h") {
	$times = array();
	if ($mode == "12h") {
		$hours_am = array("12", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11");
		$hours_pm = array("12", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11");
	} elseif ($mode == "24h") {
		$hours_am = array("00", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11");
		$hours_pm = array("13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23");
	}

	if ($interval == 15) {
		$minutes = array("00", "15", "30", "45");
	} elseif ($interval == 30) {
		$minutes = array("00", "30");
	} elseif ($interval == 20) {
		$minutes = array("00", "20", "40");
	} elseif ($interval == 10) {
		$minutes = array("00", "10", "20", "30", "40", "50");
	} elseif ($interval == 5) {
		$minutes = array("00", "05", "10", "15", "20", "25", "30", "35", "40", "45", "50", "55");
	} elseif ($interval == 60) {
		$minutes = array("00");
	}

	foreach ($hours_am as $hour) {
		foreach ($minutes as $minute) {
			if ($mode == "12h") {
				$times[] = $hour . ":" . $minute . " AM";
			} elseif ($mode == "24h") {
				$times[] = $hour . ":" . $minute;
			}
		}
	}
	foreach ($hours_pm as $hour) {
		foreach ($minutes as $minute) {
			if ($mode == "12h") {
				$times[] = $hour . ":" . $minute . " PM";
			} elseif ($mode == "24h") {
				$times[] = $hour . ":" . $minute;
			}
		}
	}

	return $times;
}


function getExpirationMonths() {
	return array(

		"01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"
	);
}

function getExpirationYears() {
	$years = array();
	for ($i=date("Y"); $i<date("Y", strtotime("+11 years")); $i++) {
		$years[] = $i;
	}
	return $years;
}

function currentURL($hash = '') {
	if (is_CLI()) {
		return '';
	}
	$pageURL = 'http';
	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	if (trim($hash) != '') {
		if (substr($hash, 0, 1) == "#") {
			$pageURL .= trim($hash);
		} else {
			$pageURL .= "#" . trim($hash);
		}
	}
	return $pageURL;
}

function maskedCurrentURL() {
	if (is_CLI()) {
		return '';
	}
	$pageURL = 'http';
	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"];
	}
	if ($_SERVER["QUERY_STRING"] != '') {
		$pageURL .= "/admission/?" . $_SERVER["QUERY_STRING"];
	}

	return $pageURL;
}

function setURLVar($url, $var, $value) {
	$url_parts = parse_url($url);
	$query_str = $url_parts["query"];
	parse_str($query_str, $vars);
	if ($value !== '') {
		$vars[$var] = $value;
	} else {
		unset($vars[$var]);
	}
	$new_str = http_build_query($vars);
	return str_replace($query_str, $new_str, $url);
}


function getCombinations($array) {
	$length=sizeof($array);
	$combocount=pow(2,$length);
	for ($i=0; $i<=$combocount;$i++)
	{
	    $binary=decextbin($i,$length);
		//$combination='';
		$combination = array();
		for($j=0;$j<$length;$j++)
		{
			if($binary[$j]=="1")
				$combination[]=$array[$j];
		}
		$combinationsarray[]=$combination;
		//echo $combination."<br>";
	}
	return $combinationsarray;
}

function decextbin($decimalnumber,$bit) {
    /* decextbin function
            by James Preece (j.preece@gmail.com)
            http://www.lovingit.co.uk */

    $maxval = 1;
    $sumval = 1;
    for($i=1;$i< $bit;$i++)
    {
        $maxval = $maxval * 2;
        $sumval = $sumval + $maxval;
    }

    if ($sumval < $decimalnumber) return 'ERROR - Not enough bits to display this figure in binary.';
    for($bitvalue=$maxval;$bitvalue>=1;$bitvalue=$bitvalue/2)
    {
        if (($decimalnumber/$bitvalue) >= 1) $thisbit = 1; else $thisbit = 0;
        if ($thisbit == 1) $decimalnumber = $decimalnumber - $bitvalue;
    $binarynumber .= $thisbit;
    }
	return $binarynumber;
}

function csv_implode($dataArray,$delimiter=",",$enclosure='"') {
  // Write a line to a file
  // $filePointer = the file resource to write to
  // $dataArray = the data to write out
  // $delimeter = the field separator

  // Build the string
  $string = "";

  // No leading delimiter
  $writeDelimiter = FALSE;
  foreach($dataArray as $dataElement)
   {
    // Replaces a double quote with two double quotes
    $dataElement=str_replace("\"", "\"\"", stripslashes($dataElement));

    // Adds a delimiter before each field (except the first)
    if($writeDelimiter) $string .= $delimiter;

    // Encloses each field with $enclosure and adds it to the string
    $string .= $enclosure . $dataElement . $enclosure;

    // Delimiters are used every time except the first.
    $writeDelimiter = TRUE;
   } // end foreach($dataArray as $dataElement)

  // Append new line
  $string .= "\n";
  
  // Write the string to the file
  //fwrite($filePointer,$string);
  return $string;
}

function htmlToText($html) {
	$text = $html;

	//strip out tabs
	$text = str_replace("\t","", $text);

	//replace any \r\n sequences with \n so we can work consistently
	$text = str_replace("\r\n", "\n", $text);

	//remove all BR's that are followed by newlines. leave the newline behind.
	/* $text = preg_replace("/<br \/?>\n/i", "\n", $text); */

	//remove all Mac newlines
	//$text = str_replace("\r\n", "", $text);

	//replace all double, triple, etc newlines with single-newlines
	//$text = preg_replace("/\n+/", "\n", $text);

	$text = str_replace("\n", "", $text);

	//replace all remaining BR's with newlines
	$text = preg_replace("/<br \/?>/i", "\n", $text);

	//ditch all remaining HTML tags
	$text = strip_tags($text);

	//convert some common html entities
	$text = preg_replace(array("/&mdash;/", "/&amp;/", "/&bull;/"), array("--", "&", "·"), $text);

	return $text;
}

function imagecreatefrombmp($filename) {
 //Ouverture du fichier en mode binaire
   if (! $f1 = fopen($filename,"rb")) return FALSE;

 //1 : Chargement des ent�tes FICHIER
   $FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1,14));
   if ($FILE['file_type'] != 19778) return FALSE;

 //2 : Chargement des ent�tes BMP
   $BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel'.
                 '/Vcompression/Vsize_bitmap/Vhoriz_resolution'.
                 '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1,40));
   $BMP['colors'] = pow(2,$BMP['bits_per_pixel']);
   if ($BMP['size_bitmap'] == 0) $BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
   $BMP['bytes_per_pixel'] = $BMP['bits_per_pixel']/8;
   $BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
   $BMP['decal'] = ($BMP['width']*$BMP['bytes_per_pixel']/4);
   $BMP['decal'] -= floor($BMP['width']*$BMP['bytes_per_pixel']/4);
   $BMP['decal'] = 4-(4*$BMP['decal']);
   if ($BMP['decal'] == 4) $BMP['decal'] = 0;

 //3 : Chargement des couleurs de la palette
   $PALETTE = array();
   if ($BMP['colors'] < 16777216)
   {
    $PALETTE = unpack('V'.$BMP['colors'], fread($f1,$BMP['colors']*4));
   }

 //4 : Cr�ation de l'image
   $IMG = fread($f1,$BMP['size_bitmap']);
   $VIDE = chr(0);

   $res = imagecreatetruecolor($BMP['width'],$BMP['height']);
   $P = 0;
   $Y = $BMP['height']-1;
   while ($Y >= 0)
   {
    $X=0;
    while ($X < $BMP['width'])
    {
     if ($BMP['bits_per_pixel'] == 24)
        $COLOR = unpack("V",substr($IMG,$P,3).$VIDE);
     elseif ($BMP['bits_per_pixel'] == 16)
     {
	   $COLOR = unpack("v",substr($IMG,$P,2));
	   $blue  = ($COLOR[1] & 0x001f) << 3;
	   $green = ($COLOR[1] & 0x07e0) >> 3;
	   $red   = ($COLOR[1] & 0xf800) >> 8;
	   $COLOR[1] = $red * 65536 + $green * 256 + $blue;
     }
     elseif ($BMP['bits_per_pixel'] == 8)
     {
        $COLOR = unpack("n",$VIDE.substr($IMG,$P,1));
        $COLOR[1] = $PALETTE[$COLOR[1]+1];
     }
     elseif ($BMP['bits_per_pixel'] == 4)
     {
        $COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
        if (($P*2)%2 == 0) $COLOR[1] = ($COLOR[1] >> 4) ; else $COLOR[1] = ($COLOR[1] & 0x0F);
        $COLOR[1] = $PALETTE[$COLOR[1]+1];
     }
     elseif ($BMP['bits_per_pixel'] == 1)
     {
        $COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
        if     (($P*8)%8 == 0) $COLOR[1] =  $COLOR[1]        >>7;
        elseif (($P*8)%8 == 1) $COLOR[1] = ($COLOR[1] & 0x40)>>6;
        elseif (($P*8)%8 == 2) $COLOR[1] = ($COLOR[1] & 0x20)>>5;
        elseif (($P*8)%8 == 3) $COLOR[1] = ($COLOR[1] & 0x10)>>4;
        elseif (($P*8)%8 == 4) $COLOR[1] = ($COLOR[1] & 0x8)>>3;
        elseif (($P*8)%8 == 5) $COLOR[1] = ($COLOR[1] & 0x4)>>2;
        elseif (($P*8)%8 == 6) $COLOR[1] = ($COLOR[1] & 0x2)>>1;
        elseif (($P*8)%8 == 7) $COLOR[1] = ($COLOR[1] & 0x1);
        $COLOR[1] = $PALETTE[$COLOR[1]+1];
     }
     else
        return FALSE;
     imagesetpixel($res,$X,$Y,$COLOR[1]);
     $X++;
     $P += $BMP['bytes_per_pixel'];
    }
    $Y--;
    $P+=$BMP['decal'];
   }

 //Fermeture du fichier
   fclose($f1);

 return $res;
}

function urlize($string) {
	$string = preg_replace("/(\.|:|;|-|\"|\/|\(|\)|\'|!|\\$|\@|\%|\#)/", "", strtolower($string));
	$string = preg_replace("/(\s)/", "-", strtolower($string));
	return urlencode($string);
}

function friendlyDateTime($stamp, $include_time = false) {
	$format = "l, F jS, Y";
	if ($include_time) {
		$format .= " g:ia";
	}
	return date($format, $stamp);
}

function parseQueryString($str) { 
    $op = array(); 
    $pairs = explode("&", $str); 
    foreach ($pairs as $pair) { 
        list($k, $v) = array_map("urldecode", explode("=", $pair)); 
        $op[$k] = $v; 
    } 
    return $op; 
}

// $unsorted is an array of objects
function columnSort($unsorted, $column) {
   $sorted = $unsorted;
   for ($i=0; $i < sizeof($sorted)-1; $i++) {
     for ($j=0; $j<sizeof($sorted)-1-$i; $j++)
       if ($sorted[$j]->$column > $sorted[$j+1]->$column) {
         $tmp = $sorted[$j];
         $sorted[$j] = $sorted[$j+1];
         $sorted[$j+1] = $tmp;
     }
   }
   return $sorted;
}

function getMimeType($path) {
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$type = finfo_file($finfo, $path);
	finfo_close($finfo);	
	return $type;
}
if (! function_exists('debugSQL') ) {

	function debugSQL($sql, $params) {
			// check if any of the params are substrings of any other params:
			$checkLater = array();
			foreach ($params as $k => $v) {
					foreach ($params as $k2 => $v2) {
							if ($k != $k2 && strpos($k, $k2) !== false) {
									$checkLater[$k2] = $v2;
									unset($params[$k2]);
							}
					}
			}
			foreach ($params as $k => $v) {
					$sql = str_replace($k, "'{$v}'", $sql);
			}
			if (count($checkLater) > 0) {
					$sql = debugSQL($sql, $checkLater);
			}
			return $sql;
	}	
}
