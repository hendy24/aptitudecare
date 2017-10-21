<?php

class CMS_Site_User_Role extends CMS_Table {
	
	public static $table = "site_user_role";
	
	public function deleteUserRole($user_id = false, $facility_id = false) {
		db()->query("delete from site_user_role where site_user=:userid and facility=:facilityid", array(
			":facilityid" => $facility_id,
			":userid" => $user_id
		));

	}
	
}