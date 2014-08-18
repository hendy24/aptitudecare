<?php

class SiteUser extends Data {

	//	Set table specific data

	public $table = 'user';

	public $belongsTo = array(
		'Location' => array(
			'table' => 'location',
			'join_type' => 'INNER',
			'inner_key' => 'default_location',
			'foreign_key' => 'id',
			'join_field' => array(
				'column' => 'name',
				'name' => 'location'
			)
		),
		'Module' => array(
			'table' => 'module',
			'join_type' => 'INNER',
			'inner_key' => 'default_module',
			'foreign_key' => 'id',
			'join_field' => array(
				'column' => 'name',
				'name' => 'module'
			)
		)
	);

	public $hasMany = array(

	);

	public $_manage_fields = array(
		'first_name',
		'last_name',
		'phone',
		'location',
	);
}