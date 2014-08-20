<?php

class HealthcareFacility extends AppModel {

	public $table = 'healthcare_facility';
	public $belongsTo = array(
		'LocationType' => array(
			'table' => 'location_type',
			'join_type' => 'INNER',
			'inner_key' => 'location_type_id',
			'foreign_key' => 'id',
			'join_field' => array(
				'column' => 'description',
				'name' => 'location_type'
			)
		)
	);
	public $_manage_fields = array(
		'public_id',
		'name',
		'city',
		'state',
		'phone',
		'location_type'
	);

}