<?php

class LocationType extends AppModel {

	public $table = 'location_type';

	public $hasMany = array(
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
}