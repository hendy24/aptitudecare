<?php

/*
 * NOTE: the message should complete the statement:  "The validation failed because your value _________."
*/

class ValidateResult {
	private $result = false;
	private $message;

	public function __construct($result, $message = '') {
		$this->result = $result;
		$this->message = $message;
	}

	public function success() {
		return $this->result;
	}

	public function message() {
		return $this->message;
	}
}

class Validate {
	public static function is_natural($val, $acceptzero = false) {
		$return = ((string)$val === (string)(int)$val);
		if ($acceptzero)
			$base = 0;
		else
			$base = 1;
		if ($return && intval($val) < $base)
			$return = false;

		if ($return == false) {
			if ($acceptzero) {
				return new ValidateResult(false, "is not an integer greater than or equal to zero");
			} else {
				return new ValidateResult(false, "is not an integer greater than zero");
			}
		} else {
			return new ValidateResult(true);
		}

	}

	public static function is_positive($val, $acceptzero = true) {
		if ($acceptzero) {
			if (is_numeric($val) && ($val >= 0)) {
				$return = true;
			} else {
				$return = false;
			}
		} else {
			if (is_numeric($val) && ($val > 0)) {
				$return = true;
			} else {
				$return = false;
			}
		}

		if ($return == false) {
			return new ValidateResult(false, "is not a positive number");
		} else {
			return new ValidateResult(true);
		}

	}

	/**
	 * This method now operates strictly "as opposed to an integer"
	 * @param	mixed	$val	the value to be examined
	 * @param	Integer	$length	the length to check for
	 * @return	Object			ValidateResult
	 */
	public static function is_pubid($val, $length = 10) {
		if (! static::is_natural($val)->success() && preg_match("/[A-Za-z]/", $val) && strlen($val) == $length) {
			return new ValidateResult(true);
		} else {
			$reasons = array();
			if (static::is_natural($val)->success() ) {
				$reasons[] = "appears to be an integer";
			}
			if (! preg_match("/[A-Za-z]/", $val)) {
				$reasons[] = "does not have any alphanumeric characters";
			}
			if (strlen($val) != $length) {
				$reasons[] = "length is not {$length}";
			}
			return new ValidateResult(false, "is not a valid record identifier: " . implode(",", $reasons));
		}
	}


	public static function is_alphanumeric($val) {
		if (! preg_match('/^[a-z0-9]+$/i', $val)) {
			return new ValidateResult(false, "contains non-alphanumeric (A-Z, a-z, 0-9) characters");
		} else {
			return new ValidateResult(true);
		}
	}

	public static function is_text($val) {
		//TODO: implemenet this.
		return new ValidateResult(true);
	}

	public static function is_email($val) {
		if (filter_var($val, FILTER_VALIDATE_EMAIL) === false) {
			return new ValidateResult(false, "is not a valid email address");
		} else {
			return new ValidateResult(true);
		}
	}

	// 01/01/1981 or 01/01/81
	public static function is_american_date($date) {
		if (! preg_match("/^[0-9]{2}\/[0-9]{2}\/(?:[0-9]{4}|[0-9]{2})$/", $date)) {
			return new ValidateResult(false, "is not a valid US-formatted date");
		} else {
			return new ValidateResult(true);
		}
		return false;
	}

	// 1981-01-01
	public static function is_standard_date($date) {
		if (! preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $date)) {
			return new ValidateResult(false, "is not a valid date string (YYYY-MM-DD)");
		} else {
			return new ValidateResult(true);
		}
	}

	// 1981-01-01 23:59:59
	public static function is_standard_datetime($date) {
		if (! preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}\s[0-9]{2}:[0-9]{2}:[0-9]{2}$/", $date)) {
			return new ValidateResult(false, "is not a valid date string (YYYY-MM-DD HH:MM:SS)");
		} else {
			return new ValidateResult(true);
		}
	}

	public static function is_zipcode($str) {
		if (! preg_match("/^\d{5}(-?\d{4})?$/", $str) ) {
			return new ValidateResult(false, "is not a valid US zip code (XXXXX or XXXXX-XXXX)");
		} else {
			return new ValidateResult(true);
		}
	}

	public static function is_url($str) {
		if (! preg_match("/^[a-zA-Z]+[:\/\/]+[A-Za-z0-9\-_]+(\\.+[A-Za-z0-9\.\/%&=\?\-_]+)?$/i", $str) ) {
			return new ValidateResult(false, "is not a valid URL");
		} else {
			return new ValidateResult(true);
		}
	}

	public static function is_phone($str) {
		$regex = "/^(1\s*[-\/\.]?)?(\((\d{3})\)|(\d{3}))\s*[-\/\.]?\s*(\d{3})\s*[-\/\.]?\s*(\d{4})\s*(([xX]|[eE][xX][tT])\.?\s*(\d+))*$/";
		if (! preg_match("$regex", $str) ) {
			return new ValidateResult(false, "is not a valid US phone number");
		} else {
			return new ValidateResult(true);
		}
	}

	public static function is_USAState($val) {
		$states = array_keys(getUSAStates());
		if (in_array($val, $states)) {
			return new ValidateResult(true) ;
		} else {
			return new ValidateResult(false, "is not a valid US state.");
		}
	}
	public static function is_CAProvince($val) {
		$provinces = array_keys(getCAProvinces());
		if (in_array($val, $provinces)) {
			return new ValidateResult(true) ;
		} else {
			return new ValidateResult(false, "is not a valid Canadian province.");
		}
	}

	public static function is_CAPostalCode($val) {
		if (!preg_match("/^[abceghjklmnprstvxy][0-9][a-z]\s{0,1}[0-9][a-z][0-9]$/i", $val)) {
			return new ValidateResult(false, "is not a valid Canadian postal code.");
		} else {
			return new ValidateResult(true);
		}
	}	
	
	public static function is_SSN($val) {
		if (!preg_match("/^[0-9]{3}-[0-9]{2}-[0-9]{4}$/i", $val)) {
			return new ValidateResult(false, "is not a valid social security number.");
		} else {
			return new ValidateResult(true);
		}
	}

}