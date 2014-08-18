<?php

class CaseManager extends Data {
	public $table = 'case_manager';
	public $belongsTo = array(
		'HealthcareFacility' => array(
			'table' => 'healthcare_facility',
			'join_type' => 'INNER',
			'inner_key' => 'healthcare_facility_id',
			'foreign_key' => 'id'
		)
	);
	public $_manage_fields = array(
		'public_id',
		'first_name',
		'last_name',
		'phone',
		'email',
		'name'
	);
}