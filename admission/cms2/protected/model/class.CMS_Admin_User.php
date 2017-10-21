<?php

class CMS_Admin_User extends CMS_Table {

	public static $table = "admin_user";
	public static $inAdmin = true;
	public static $enableAdminNew = true;		//true, false, or "root"
	public static $enableAdminEdit = true;	//true, false, or "root"
	public static $enableAdminDelete = true;	//true, false, or "root"
	public static $modelTitle = "CMS / Admin Users";
	protected static $enableCreateStructure = true;


	protected $roleInfo = array();

	protected static $metadata = array(
		"fullname" => array(
			"label" => "Name",
			"widget" => "text"
		),
		"email" => array(
			"label" => "Email",
			"widget" => "text"
		),
		"password" => array(
			"label" => "Password",
			"widget" => "password",
			"options" => array(
				"type" => "encrypted"
			),
			"callback_beforeSave" => "Authentication_Admin::encryptPassword"
		),
		"datetime_seen" => array(
			"widget" => "immutable",
			"label" => "Last Login"
		),
		"admin_role" => array(
			"widget" => "related_single",
			"label" => "User Permission Level"
		)
	);

	public function getTitle() {
		return $this->fullname;
	}

	public function hasAccess($cls) {
		$roleInfo = $this->related("admin_role");
		$acl = unserialize($roleInfo->acl);
		if ($this->is_root == 1 || $this->admin_role == '' || (is_array($acl) && in_array($cls, $acl))) {
			return true;
		}
		return false;
	}



}