<?php

class CaseManager extends Data {
	protected $table = 'case_manager';
	protected $belongsTo = array(
		'HealthcareFacility' => array(
			'table' => 'healthcare_facility',
			'join_type' => 'INNER',
			'inner_key' => 'healthcare_facility_id',
			'foreign_key' => 'id',
			'join_field' => array(
				'column' => 'name',
				'name' => 'healthcare_facility'
			)
		)
	);
	protected $_manage_fields = array(
		'public_id',
		'first_name',
		'last_name',
		'phone',
		'email',
		'healthcare_facility'
	);

	protected $_add_fields = array(
		'first_name',
		'last_name',
		'email',
		'address',
		'city',
		'state',
		'zip',
		'phone',
		'fax'
	); 





}