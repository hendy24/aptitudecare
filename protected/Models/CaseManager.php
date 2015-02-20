<?php

class CaseManager extends AppData {
	protected $table = 'case_manager';
	protected $belongsTo = array(
		'HealthcareFacility' => array(
			'table' => 'ac_healthcare_facility',
			'join_type' => 'INNER',
			'inner_key' => 'healthcare_facility_id',
			'foreign_key' => 'id',
			'join_field' => array(
				'column' => 'name',
				'name' => 'ac_healthcare_facility'
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


	public function fetchCMCount($states) {
		$state = null;
		foreach ($states as $k => $s) {
			$state .= "'{$s->state}', ";
		}
		$state = trim($state, ", ");

		$sql = "SELECT count({$this->tableName()}.id) AS items FROM {$this->tableName()} INNER JOIN ac_healthcare_facility ON ac_healthcare_facility.id = {$this->tableName()}.healthcare_facility_id WHERE ac_healthcare_facility.state IN ({$state})";

		return $this->fetchOne($sql);
	}



}