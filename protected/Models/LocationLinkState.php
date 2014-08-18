<?php

class LocationLinkState extends AppModel {

	public $table = 'location_link_state';
	public $belongsTo = array(
		'location' => array(
			'inner_key' => 'location_id',
			'foreign_key' => 'id'
		)
	);

	public function getAdditionalStates($facility_id) {
		$params[":id"] = $facility_id;
			
			$sql = "SELECT * FROM {$this->table} WHERE location_id = :id";
			
			return $this->fetchAll($sql, $params);
	}

}