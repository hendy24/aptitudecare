<?php

function pr($var) {
	echo '<div id="debug">';
	echo '<pre>';
 	print_r($var);
    echo '</pre>';
    echo '</div>';
    echo '<div class="clear"></div>';
}

function debug($string, $array = false) {
	echo '<div id="debug">';
	echo '<h2>Debug Info:</h2>';
	if (is_array($string) || is_object($string)) {
		echo '<pre>';
		print_r ($string);
		echo '</pre>';
	} else {
		echo $string;
	}

	if ($array) {
		echo '<pre>';
		print_r ($array);
		echo '</pre>';
	}
	echo '</div>';
	exit;
}



function camelizeString($string) {
	if (strstr($string, '-')) {
		return lcfirst (str_replace('-', '', implode('-', array_map('ucfirst', explode('-', $string)))));
	} elseif (strstr($string, '_')) {
		return lcfirst (str_replace('_', '', implode('_', array_map('ucfirst', explode('_', $string)))));
	} else {
		return $string;
	}

}


function underscoreString($string) {
	if (strstr($string, '-')) {
		return lcfirst (str_replace('-', '_', implode('-', array_map('lcfirst', explode('-', $string)))));
	} elseif (preg_match('/[A-Z]/', $string)) {
		return strtolower(preg_replace('/\B([A-Z])/', '_$1', $string));
	} else {
		return $string;
	}

}

function hyphenateString($string) {
	$hs = preg_replace('/([^A-Z-])([A-Z])/', '$1-$2', $string);
	return strtolower($hs);
}

function stringify($string) {
	if (strstr($string, '_')) {
		$result = ucfirst (str_replace('_', ' ', implode('_', array_map('ucfirst', explode('_', $string)))));
		if (strstr($result, 'Id')) {
			$result = str_replace(' Id', '', $result);
		} //elseif (strstr($result, 'Default')) {
		// 	$result = str_replace('Default ', '', $result);
		// }

		return $result;
	} else {
		return ucfirst(preg_replace('/([^A-Z-])([A-Z])/', '$1 $2', $string));
	}

}

function depluralize($word) {
	$rules = array(
		'ss' => false,
		'os' => 'o',
		'ies' => 'y',
		'xes' => 'x',
		'oes' => 'o',
		'ves' => 'f',
		's' => '',
		'a' => 'a'
	);

	foreach (array_keys($rules) as $key) {
		if (substr($word, (strlen($key) * -1)) != $key) {
			continue;
		}

		if ($key === false) {
			return $word;
		} else {
			return substr ($word, 0, strlen($word) - strlen($key)) . $rules[$key];
		}
		return $word;
	}
}


function getModelName($string) {
	return ucfirst(camelizeString(depluralize($string)));
}


function getRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$string = "";

	for ($p = 0; $p < $length; $p++) {
		$string .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $string;
}


function currentUrl() {
	return htmlspecialchars_decode('https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
}

function json_return($data) {
	header("Content-type: application/json");
	echo json_encode($data);
	exit;
}

function return_json($data) {
	json_return($data);
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

function getEthnicities() {
	return array(
		"African American",
		"Asian",
		"Caucasian",
		"Hispanic",
		"Other"
	);
}

function getLanguages() {
	return array(
		"English",
		"Spanish",
		"Other"
	);
}

function getMaritalStatuses() {
	return array(
		"Married",
		"Single",
		"Divorced",
		"Widowed"
	);
}


/*
 * -------------------------------------------------------------------------
 * 	DATE FUNCTIONS
 * -------------------------------------------------------------------------
 */



function mysql_datetime($date = null) {
	if ($date == null) {
		$date = 'now';
	}
	return date('Y-m-d H:i:s', strtotime($date));
}

function mysql_datetime_admit($date = null) {
	if ($date == null) {
		$date = 'now';
	}
	return date('Y-m-d 11:00:00', strtotime($date));
}

function mysql_date($date = null) {
	if ($date == null) {
		$date = 'now';
	}
	return date('Y-m-d', strtotime($date));
}

function mysql_time($time = null) {
	if ($time == null) {
		$time = "now";
	}
	return date("H:i:s", strtotime($time));
}


function display_date($date = false) {
	if ($date) {
		return date('m/d/Y', strtotime($date));
	}
	return null;
}

function display_datetime($datetime = false) {
	if ($datetime) {
		return date("m/d/Y H:i a", strtotime($datetime));
	}
	return null;
}

function getAge($dob = false) {
	if ($dob) {
		$dob = explode("/", $dob);
		$curMonth = date("m");
		$curDay = date("j");
		$curYear = date("Y");
		$age = $curYear - $dob[2];
		if ($curMonth < $dob[0] || ($curMonth == $dob[0] && $curDay < $dob[1])) {
			$age--;
		}
		return $age;
	}

	return false;
}
