<?php

/*
Testing Prodcedure for Authorize.Net Stores:

Authorize.net's API has different ways of allowing you to submit test transactions.

If possible, login to the Authorize.net merchant control panel and set the site to "Test Mode" while developing the site.
Then, set "isTest" to FALSE in cms_config.php. This code will think the transactions are live, they'll go to the live server,
you'll get proper responses but no money changes hands.

If you can't turn on the account's "Test Mode" (for example, you're doing a site redesign and the existing site is processing live transactions with the same account), you can still force the code to only submit test transactions. Just set "isTest" to TRUE in cms_config.php, and all transactions will be sent to the 'certification' (test) server.

To go live, login to the Authorize.net merchant control panel and turn off "Test Mode", if it's on.  Then make sure "isTest" is FALSE in cms_config.php.

Once live, you can run one-off test transactions by using the following CC numbers:

Amex: 370000000000002
Discover: 6011000000000012
Mastercard: 5424000000000015
Visa: 4007000000027


*/

class AuthorizeNet {

	private static $isTest = false;
	private static $login_id = false;
	private static $tran_key = false;
	private static $cp_login = false;
	private static $cp_pass = false;
	
	private static $testCards = array(
		"Amex" => "370000000000002",
		"Discover" => "6011000000000012",
		"Master" => "5424000000000015",
		"Visa" => "4007000000027"
	);

	// maps card "Code" names to their real names
	private static $cardNames = array(
		"Visa" => "Visa",
		"Master" => "MasterCard",
		"Amex" => "American Express",
		"Discover" => "Discover",
		"Diners" => "Diners Club",
		"EnRoute" => "EnRoute",
		"JCB" => "JCB"
	);


	private static $allowVisa = true;
	private static $allowMaster = true;
	private static $allowAmex = false;
	private static $allowDiscover = false;
	private static $allowDiners = false;
	private static $allowJCB = false;
	private static $allowEnRoute = false;


	public static function init($login_id, $tran_key, $cp_login = false, $cp_pass = false) {
		self::$login_id = $login_id;
		self::$tran_key = $tran_key;
		self::$isTest = $isTest;
		self::$cp_login = $cp_login;
		self::$cp_pass = $cp_pass;
	}

	public static function getAllowedCards() {
		$cards = array();
		foreach (self::$cardNames as $code => $name) {
			$propName = "allow{$code}";
			if (self::${$propName} == true) {
				$cards[$code] = $name;
			}
		}
		return $cards;
	}

	public static function getTestCardNumber($card_code) {
		return self::$testCards[$card_code];
	}

	public static function getTestCardExpDate() {
		return date("m/Y", strtotime("+3 months"));
	}

	public static function setTestMode($bool) {
		if ( $bool !== true && $bool !== false) {
			throw new Exception("Supplied value must be either TRUE or FALSE.");
		}
		self::$isTest = $bool;
	}

	public static function getTestMode() {
		return self::$isTest;
	}

	public static function formatExpDate($str) {
		return date("mY", strtotime($str));
	}

	public static function getCardName($card_code) {
		return self::$cardNames[$card_code];
	}

	public static function allowVisa($bool) {
		if ( $bool !== true && $bool !== false) {
			throw new Exception("Supplied value must be either TRUE or FALSE.");
		}
		self::$allowVisa = $bool;
	}

	public static function allowMaster($bool) {
		if ( $bool !== true && $bool !== false) {
			throw new Exception("Supplied value must be either TRUE or FALSE.");
		}
		self::$allowMaster = $bool;
	}

	public static function allowAmex($bool) {
		if ( $bool !== true && $bool !== false) {
			throw new Exception("Supplied value must be either TRUE or FALSE.");
		}
		self::$allowAmex = $bool;
	}

	public static function allowDiscover($bool) {
		if ( $bool !== true && $bool !== false) {
			throw new Exception("Supplied value must be either TRUE or FALSE.");
		}
		self::$allowDiscover = $bool;
	}

	public static function allowDiners($bool) {
		if ( $bool !== true && $bool !== false) {
			throw new Exception("Supplied value must be either TRUE or FALSE.");
		}
		self::$allowDiners = $bool;
	}

	public static function allowEnRoute($bool) {
		if ( $bool !== true && $bool !== false) {
			throw new Exception("Supplied value must be either TRUE or FALSE.");
		}
		self::$allowEnRoute = $bool;
	}

	public static function allowJCB($bool) {
		if ( $bool !== true && $bool !== false) {
			throw new Exception("Supplied value must be either TRUE or FALSE.");
		}
		self::$allowJCB = $bool;
	}

	
	/* Format of $pairs:
	
	(1) For one-time sale or delayed-authorization sale:
				"x_type"			=> "AUTH_CAPTURE" or "AUTH_ONLY",
				"x_card_num"			=> "",
				"x_exp_date"			=> ",
				"x_description"			=> "{transaction description}",
				"x_card_code"			=> "{cvv code}",
				"x_amount"			=> "{total amount}",
				"x_first_name"			=> "",
				"x_last_name"			=> "",
				"x_address"			=> "",
				"x_city"			=> "",
				"x_state"			=> "",
				"x_zip"				=> "",
				"x_phone"			=> "",
				"x_country"			=> "US",
				"x_email"			=> "",
				"x_merchant_email" => defined('DEVELOPMENT') ? $_cfg["test_email"] : $_cfg["email"],
				"x_email_customer" => FALSE,
				"x_email_merchant" => FALSE,
				"x_cust_id" => 		"",
				"x_ship_to_first_name"		=> "",
				"x_ship_to_last_name"		=> "",
				"x_ship_to_address"		=> "",
				"x_ship_to_city"		=> "",
				"x_ship_to_state"		=> "",
				"x_ship_to_zip"			=> "",
				"x_ship_to_country"		=> "US"
			
	(2) For performing the "capture" end of a previously-initiated delayed-authorization sale:
				"x_trans_id"		=> $trans_id,
				"x_type"			=> "AUTH_CAPTURE"
		
	(3) To void out a transaction
				"x_trans_id"		=> $trans_id,
				"x_type"			=> "VOID"
	
	(4) To issue a credit
				"x_trans_id"		=> $trans_id,
				"x_type"			=> "CREDIT",
				"x_method"			=> "CC",
				"x_tran_key"			=> self::$tran_key,
				"x_card_num"	=>		""
	
	*/
	
	public static function submit($pairs) {
		//if (self::$isTest == true) {
		//	$url = "https://certification.authorize.net/gateway/transact.dll";
		//} else {
		$url = "https://secure.authorize.net/gateway/transact.dll";
		//}


		$pairs["x_test_request"] = (self::$isTest == true) ? "TRUE" : "FALSE";
		$pairs["x_login"] = self::$login_id;
		$pairs["x_tran_key"] = self::$tran_key;
		$pairs["x_method"] = "CC";
		$pairs["x_version"] = "3.1";
		$pairs["x_delim_char"] = "|";
		$pairs["x_delim_data"] = "TRUE";
		$pairs["x_relay_response"] = "FALSE";
		
		$fields = "";
		foreach( $pairs as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " ));
		### curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response. ###
		$resp = curl_exec($ch);
		curl_close ($ch);
		
		return $resp;
	}
	
	public static function parseResponse($resp) {
		$data = explode("|", $resp);
		
		if ($data[0] == 1) {
			$bool = true;
			$msg = "";
		} else {
			$bool = false;
			$msg = $data[3];
		}
		
		$vals = array("bool" => $bool, "msg" => $msg, "ref" => $data[6], "data" => $data);
		return $vals;
		
	}

}

?>
