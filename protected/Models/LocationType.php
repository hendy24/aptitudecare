<?php

class LocationType extends AppModel {

	protected $table = 'location_type';

	protected $hasMany = array(
		'HealthcareFacility' => array(
			'table' => 'healthcare_facility',
			'inner_key' => 'id',
			'foreign_key' => 'location_type_id'
		),
		'Location' => array(
			'table' => 'location',
			'inner_key' => 'id',
			'foreign_key' => 'location_type'
		)
	);


	public function getTypes() {
		$sql = "SELECT * FROM `{$this->table}`";
		return $this->fetchAll($sql);
	}
}