<?php

class UserLocation extends AppModel {

	protected $table = 'user_location';


	public function deleteCurrentLocations($user_id) {
		$sql = "DELETE FROM {$this->table} WHERE user_id = :user_id";
		$params[":user_id"] = $user_id;

		db()->update($sql, $params);
		return true;
	}

}