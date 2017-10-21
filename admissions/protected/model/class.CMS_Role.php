<?php

class CMS_Role extends CMS_Table {
    
    public static $table = "role";
    protected static $metadata = array();
    protected static $adminLockFromDelete = array (1, 2, 3, 4, 5);
    public static $modelTitle = "User Roles";
    
    public function getTitle() {
        return $this->description;    
    }
    
    public function getUsers(CMS_Facility $facility) {
        $sql = "select site_user.* from site_user inner join site_user_role on site_user.id=site_user_role.site_user where site_user_role.role=:roleid and site_user_role.facility=:facilityid";
        $params = array(
            ":roleid" => $this->id,
            ":facilityid" => $facility->id
        );
        $obj = new CMS_Site_User;
        return $obj->fetchCustom($sql, $params);
    }

    public function getUserByID($facility, $roleID) {
        $sql = "select id from site_user inner join site_user_role on site_user.id =  site_user_role.site_user where facility = :facility and role = :roleid";
        $params[":facility"] = $facility;
        $params[":roleid"] = $roleID;
        $obj = new CMS_Site_User;
        return $obj->fetchCustom($sql, $params);
    }
    
    public function getRoles() {
	    $sql = "select * from role";
	    $obj = static::generate();
	    return $obj->fetchCustom($sql);
    }
 
}