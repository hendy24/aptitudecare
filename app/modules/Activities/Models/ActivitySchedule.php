<?php

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class ActivitySchedule extends Info {

	protected $table = "activity_schedule";

	public function fetchSchedule($activity_id) {
		$sql = "SELECT * FROM {$this->tableName()} WHERE activity_id = :activity_id";
		$params[":activity_id"] = $activity_id; 
		return $this->fetchOne($sql, $params);
	}



} // END class AtivitySchedule extends Info 