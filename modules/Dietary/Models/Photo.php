<?php

class Photo extends Dietary {

	protected $table = "photo";


	public function fetchApprovedPhotos() {
		// probably will want to sort these by date and paginate eventually
		$sql = "SELECT p.* FROM {$this->tableName()} p WHERE approved = true";
		return $this->fetchAll($sql);
	}


	public function fetchPhotosForApproval() {
		$location = $this->loadTable("Location");
		$user = $this->loadTable("User");

		$sql = "SELECT p.*, l.name AS location_name, CONCAT(u.first_name, \" \", u.last_name) AS username FROM {$this->tableName()} p INNER JOIN {$location->tableName()} l ON l.id = p.location_id INNER JOIN {$user->tableName()} u ON u.id = p.user_created WHERE approved = false ORDER BY datetime_created ASC";
		return $this->fetchAll($sql);
	}


	public function fetchPhotosWithoutInfo($user_id) {
		$sql = "SELECT * FROM {$this->tableName()} WHERE user_created = :user_id AND info_added = 0";
		$params[":user_id"] = $user_id;
		return $this->fetchAll($sql, $params);
	}


}