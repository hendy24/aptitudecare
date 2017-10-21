<?php

class CMS_Admin_Role extends CMS_Table {

	public static $table = "admin_role";
	public static $inAdmin = "root";				//true, false, or "root"
	public static $enableAdminNew = "root";		//true, false, or "root"
	public static $enableAdminEdit = "root";		//true, false, or "root"
	public static $enableAdminDelete = "root";	//true, false, or "root"
	public static $modelTitle = "CMS / Admin Roles";
	protected static $enableCreateStructure = true;
	
	protected static $metadata = array(
		"name" => array(
			"label" => "A name for this role",
			"widget" => "text",
			"instructions" => "Optional. Eg, 'Intern' or 'Customer Service'"
		)
		//,
		//"admin_user" => array(
		//	"label" => "Users",
		//	"widget" => "related_multi"
		//)
		,
		"acl" => array(
			"label" => "Has access to",
			"widget" => "admin_models",
			"callback_beforeSave" => "CMS_Admin_Role::serializeACL"
		)
	);

	public static function serializeACL($val, &$obj) {
		return serialize($val);
	}

	public function getTitle() {
		return $this->name;
	}


}