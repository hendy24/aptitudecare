<?php

class LocationType extends AppData {

	protected $table = 'location_type';

	protected $hasMany = array(
		'HealthcareFacility' => array(
			'table' => 'ac_healthcare_facility',
			'inner_key' => 'id',
			'foreign_key' => 'location_type_id'
		),
		'Location' => array(
			'table' => 'ac_location',
			'inner_key' => 'id',
			'foreign_key' => 'location_type'
		)
	);


	public function getTypes() {
		$sql = "SELECT * FROM `{$this->tableName()}`";
		return $this->fetchAll($sql);
	}
}