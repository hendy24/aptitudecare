<?php

class AdmissionDashboardUser extends AppModel {
	protected $table = "site_user";

	public function checkForExisting($public_id) {
		$sql = "SELECT * FROM " . db()->dbname2 . ".site_user WHERE pubid = :public_id";
		$params[":public_id"] = $public_id;
		$result = $this->fetchOne($sql, $params);

		if (!empty ($result)) {
			return $result;
		} else {
			return $this->fetchColumnNames();
		}
	}

}	