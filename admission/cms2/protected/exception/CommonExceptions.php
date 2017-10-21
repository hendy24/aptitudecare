<?php

class BaseValidationException extends Exception {  }
class ValidationException extends Exception {

	public function __construct($table, $errors) {

		$message = "Please correct the following problems:<br />";
		$message .= "<ul class=\"validation-exception\">";
		foreach ($errors as $e) {
			//$message .= "<li>{$field} "
		}
		parent::__construct($message, $code, $previous);
	}

}

class ORMException extends Exception { }
class ORMUniqueException extends ORMException { }
class PrimaryKeyORMException extends ORMException { }
class AuthenticationDisallowedException extends Exception { };

function handle_pdo_exception($e, $cls) {
	// table not found -- possibly a missing shadow table.
	if (preg_match("/^SQLSTATE\[42S02\]: Base table or view not found: 1146 Table '(.*)' does/", $e->getMessage(), $matches)) {
		if (preg_match("/\.?_(.*)_fulltext/", $matches[1], $matches2)) {
			$baseTable = $matches2[1];
			if ($baseTable == $cls::$table) {
				$cls::createSearchShadowTable();
			} else {
				$cls2 = Model::clsname($baseTable);
				$cls2::createSearchShadowTable();
			}
			return true;
		}
	} elseif (preg_match("/Integrity constraint violation \: 1062 Duplicate entry/", $e->getMessage(), $matches)) {
		throw new ORMUniqueException($e->getMessage(), $e->getCode());
	} else {
		throw new ORMException($e->getMessage(), $e->getCode());
	}
	throw $e;
	
}