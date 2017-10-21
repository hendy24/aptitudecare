<?php 

class CMS_Physician extends CMS_Table {

	public static $table = "physician";
	public static $modelTitle = "Physicians";
	protected static $metadata = array();
	public static $in_admin = true;

	public static function getPhysicians() {
		$obj = static::generate();
		$sql = "select * from physician";
		return $obj->fetchCustom($sql);
	}

	public function physicianName() {
		return $this->last . ", " . $this->first;
	}
	
	public function findPhysicians($state = null) {
		$sql = "select * from physician where state=:state order by last_name asc";
		$params[":state"] = $state;
		return $this->fetchCustom($sql, $params);
	}
	
	public function deletePhysician($id) {
		db()->query("delete from physician where id=:pid", array(
			":pid" => $id
		));
		
		return true;
	}
}