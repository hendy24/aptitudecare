<?php

class LocationLinkState extends AppModel {

	private $id;

	protected $table = 'location_link_state';
	protected $belongsTo = array(
		'Location' => array(
			'table' => 'location_link_state',
			'inner_key' => 'location_id',
			'foreign_key' => 'id',
			'join_type' => 'inner'
		)
	);

	public function getAdditionalStates($facility_id) {
		$params[":id"] = $facility_id;
			
			$sql = "SELECT * FROM {$this->table} WHERE location_id = :id";
			
			return $this->fetchAll($sql, $params);
	}

	public function fetchLocationStates($location_id) {
		$sql = "(SELECT state FROM {$this->table} WHERE location_id = :id) UNION (SELECT state FROM location WHERE id = :id)";
		$params[":id"] = $location_id;
		return $this->fetchAll($sql, $params);
	}

}