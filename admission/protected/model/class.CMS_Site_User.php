<?php

class CMS_Site_User extends CMS_Table {

	public static $table = "site_user";
	protected static $metadata = array(
		"email" => array(
			"widget" => "text"
		),
		"pubid" => array(
			"widget" => "off"
		),
		"password" => array(
			"label" => "Password",
			"widget" => "password",
			"options" => array(
				"type" => "encrypted"
			),
			"callback_beforeSave" => "CMS_Site_User::encryptPassword"
		),
		"default_facility" => array(
			"widget" => "related_single",
			"options" => array(
				"table" => "facility"
			)
		)
	);
	protected static $metaLoaded = false;

	public function getTitle() {
		return "{$this->first} {$this->last}";
	}

	public static function encryptPassword($val, &$obj) {
		return Authentication::encryptPassword($val);
	}

	public function getFullName() {
		return $this->first . " " . $this->last;
	}
	
	public function fullName() {
		return $this->getFullName();
	}
	
	public function hasAccess(CMS_Facility $facility) {
		if (CMS_Table::areRelated($this, $facility) || $this->isAdmissionsCoordinator() == 1) {
			return true;
		}
		return false;
	}
	
	public function guessDefaultFacility() {
		$facilities = $this->related("facility");
		if (count($facilities) > 0) {
			$this->default_facility = $facilities[0]->id;
			$this->save();
		} else {
			return false;
		}
	}
	
	public function getFacilities() {
		if (! isset($this->_facilities) ) {
			$this->_facilities = $this->related("facility");
		}
		return $this->_facilities;
	}
	
	public function getDefaultFacility() {
		if ($this->default_facility != '') {
			$facility = new CMS_Facility($this->default_facility);
			if (! $facility->valid() ) {
				if ($this->guessDefaultFacility() !== false) {
					return $this->getDefaultFacility();
				}
			} else {
				return $facility;
			}
		} else {
			if ($this->guessDefaultFacility() !== false) {
				return $this->getDefaultFacility();
			}
		}
		return false;
	}
	
	public function homeURL() {
		if ($this->isAdmissionsCoordinator() == 1) {
			return SITE_URL . "?page=coord";
		} else {
			if ($this->default_facility != '') {
				$facility = new CMS_Facility($this->default_facility);
				return SITE_URL . "?page=facility&id={$facility->pubid}";
				//return SITE_URL . "/?page=facility&id={$this->default_facility}";
			} else {
				return SITE_URL . "?page=home";
			}
		}
	}
	
	public function isAdmissionsCoordinator() {
		if ($this->is_coordinator == 1) {
			return true;
		} else {
			return $this->hasRole("admissions_coordinator");
		}
	}
	
	public function isFacilityAdmin() {
		if ($this->has_role == 'facility_administrator') {
			return true;
		}
	}
	
	public function getRoles($user_id) {
		$sql = "SELECT `role`.`id`, `role`.`name`, `facility`.`id` as facility_id, `facility`.`name` as facility_name FROM `site_user_role` INNER JOIN `role` on `role`.`id` = `site_user_role`.`role` INNER JOIN `facility` on `facility`.`id` = `site_user_role`.`facility`  WHERE `site_user` = :user_id";
		$params[":user_id"] = $user_id;
		return $this->fetchCustom($sql, $params);
	}
	
	public function canCreateAdmit(CMS_Facility $facility) {
		// admissions coordinators can do this regardless of facility
		if ($this->isAdmissionsCoordinator()) {
			return true;
		}
		// admin assistants for this facility can do this
		if ($this->hasRole("admin_assistant", $facility)) {
			return true;
		} elseif ($this->hasRole("home_health", $facility)) {
			return true;
		}
		// nobody else
		return false;
	}
	
	public function canEditInquiry(CMS_Facility $facility) {
		// these two role/capability combos are currently pinned to each other.
		return $this->canCreateAdmit($facility);
	}
		
	public function canEditNursing(CMS_Facility $facility = null) {
		return
			$this->hasRole("intake_nurse", $facility) ||
			$this->hasRole("admissions_nurse", $facility) ||
			$this->hasRole("admin_assistant", $facility) ||
			$this->hasRole("admissions_coordinator");
	}
	
	public function hasRole($role, CMS_Facility $facility = null) {
		// figure out what was meant by $role: object, id, or name
		if (is_object($role)) {
			if (get_class($role) == "CMS_Role") {
				if ($role->valid()) {
					$roleid = $role->id;
				}
			}
		} else {
			if (Validate::is_natural($role)->success() || Validate::is_pubid($role)->success()) {
				$roleObj = new CMS_Role($role);
				if ($roleObj->valid()) {
					$roleid = $roleObj->id;
				}
			}
			if (! isset($roleid)) {
				$obj = new CMS_Role;
				$roleObj = current($obj->fetch(array('name' => $role)));
				if ($roleObj != false) {
					if ($roleObj->valid()) {
						$roleid = $roleObj->id;
					}
				}
			}
		}
		
		if ($roleid == '' || ! isset($roleid)) {
			return false;
		}
		
		$sql = "select * from site_user_role where site_user=:userid and role=:roleid";
		
		$params = array(
			":userid" => $this->id,
			":roleid" => $roleid
		);
		
		// optionally restrict to a single facility
		if (! is_null($facility) ) {
			$sql .= " and facility=:facilityid";
			$params[":facilityid"] = $facility->id;
		}
		
		$row = db()->getRowCustom($sql, $params);
		if ($row == false) {
			return false;
		}
		return true;
	}
	
	public function setRole(CMS_Role $role, CMS_Facility $facility) {
		db()->simple_insert("site_user_role", array(
			"site_user" => $this->id,
			"facility" => $facility->id,
			"role" => $role->id
		));
	}
	
	public function clearRoles(CMS_Facility $facility) {
		db()->query("delete from site_user_role where site_user=:userid and facility=:facilityid", array(
			":facilityid" => $facility->id,
			":userid" => $this->id
		));
	}
	
	public function deleteUser($user_id) {
		db()->query("delete from site_user where id=:userid", array(
			":userid" => $user_id
		));
		db()->query("delete from site_user_role where site_user=:userid", array(
			":userid" => $user_id
		));
		db()->query("delete from x_site_user_link_facility where site_user=:userid", array(
			":userid" => $user_id
		));
		
		return true;
	}
	
	public function findUsersByFacility($facility_id) {
		$sql = "select 
				site_user.pubid, 
				site_user.first, 
				site_user.last, 
				site_user.email,
				site_user.phone,
				role.description
			from site_user 
			left join x_site_user_link_facility 
				on site_user.id = x_site_user_link_facility.site_user 
				and x_site_user_link_facility.facility = {$facility_id}
			left join site_user_role
				on site_user.id = site_user_role.site_user
				and site_user_role.facility = {$facility_id}
			left join role 
				on site_user_role.role = role.id
			where site_user.default_facility = {$facility_id} OR x_site_user_link_facility.facility = {$facility_id}
			group by site_user.id
			order by site_user.last asc";
		$obj = static::generate();
		return $obj->fetchCustom($sql);
	}
	
	public function findUserByPubid($pubid) {
		$sql = "select * from site_user where pubid = '{$pubid}'";
		$obj = static::generate();
		return $obj->fetchCustom($sql);
	}
	
	public function findUser($pubid = false, $facility = false) {
		$sql = "select * from site_user 
			left join site_user_role
				on site_user.id = site_user_role.site_user
				and site_user_role.facility = {$facility}
			where site_user.pubid = '{$pubid}'";
		$obj = static::generate();
		return $obj->fetchCustom($sql);
		
	}
}
