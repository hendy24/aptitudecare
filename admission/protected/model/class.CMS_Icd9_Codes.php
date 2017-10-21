<?php

class CMS_Icd9_Codes extends CMS_Table {
	
	public static $table = "icd9_codes";
	public static $modelTitle = "ICD-9 Codes";

	public static function getCodes($id) {
		$sql = "select * from `icd9_codes`";
		$sql .= " where `id`={$id}";

		$params = array();
		$obj = static::generate();

		if ($sql != '') {
			$codes = $obj->fetchCustom($sql, $params);
		} else {
			$codes = array();
		}
		return $codes;
	}

	public static function getCodeDescriptions($code) {
		if ($code != '') {
			$sql = "select * from icd9_codes where code = :code";
			$params[":code"] = $code;
			$obj = static::generate();
			return $obj->fetchCustom($sql, $params);
		} else {
			return false;
		}
		
	}

	public static function getCodeName($id) {
		$sql = "select short_desc, code from icd9_codes where id = {$id}";
		$obj = static::generate();
		$code = $obj->fetchCustom($sql, $params);

		foreach ($code as $c) {
			return $c;
		}
	}

	public static function getICD9Codes() {
		$sql = "select * from icd9_codes";
		$params = array();
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
}
