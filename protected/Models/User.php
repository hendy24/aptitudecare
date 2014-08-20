<?php

class User extends AppModel {
	protected $table = 'user';
	private $username_field = 'email';
	private $password_field = 'password';
	public $public_id;

	protected $belongsTo = array(
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

	protected $hasMany = array(

	);

	protected $_manage_fields = array(
		'public_id',
		'first_name',
		'last_name',
		'phone',
		'location',
	);

	protected $_add_fields = array(
		'first_name',
		'last_name',
		'email',
		'password',
		'phone'
	);



}