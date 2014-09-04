<?php 

class Physician extends AppModel {

	protected $table = 'physician';
	public $public_id;

	protected $_manage_fields = array(
		'public_id',
		'first_name',
		'last_name',
		'city',
		'state',
		'phone',
		'email'
	);

	protected $_add_fields = array(
		'first_name',
		'last_name',
		'address',
		'city',
		'state',
		'zip',
		'phone',
		'fax',
		'email'
	); 


}