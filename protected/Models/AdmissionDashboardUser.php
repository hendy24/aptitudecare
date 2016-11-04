<?php

class AdmissionDashboardUser extends AppModel {

	protected $prefix = false;
	protected $table = "site_user";
	protected $dbname = null;

	public function __construct() {
		$this->dbname = db()->dbname2;
	}

	public function checkForExisting($public_id) {
		$sql = "SELECT * FROM " . db()->dbname2 . ".site_user WHERE pubid = :public_id";
		$params[":public_id"] = $public_id;
		$result = $this->fetchOne($sql, $params);

		if (!empty ($result)) {
			return $result;
		} else {
			return $this->fetchColumnNames(db()->dbname2);
		}
	}

	public function deleteSiteUser($public_id) {
		$sql = "DELETE FROM " . db()->dbname2 . ".site_user WHERE pubid = :pubid";
		$params[":pubid"] = $public_id;

		if ($this->deleteQuery($sql, $params)) {
			return true;
		}
		return false;
	}

}	