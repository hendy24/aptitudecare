<?php

class Room extends Admission {

	protected $table = "room";

	public function fetchEmpty($location_id) {
		$sql = "SELECT * FROM {$this->tableName()} WHERE location_id = :location_id";
		$params[":location_id"] = $location_id;
		return $this->fetchAll($sql, $params);
	}


	public function mergeRooms($rooms, $scheduled) {
		$temp = array();
		$index = array();
		foreach ($rooms as $k => $v) {
			$temp[$v->number] = $v->number;
			$index[$v->number] = array("rooms", $k);
		}
		foreach ($scheduled as $k => $v) {
			$temp[$v->number] = $v->number;
			$index[$v->number] = array("scheduled", $k);
		}

		sort($temp);

		$retval = array();
		foreach ($temp as $number) {
			$which = $index[$number][0];
			$idx = $index[$number][1];
			$retval[] = ${$which}[$idx];
		}

		return $retval;

	}


	public function getRoom($location_id, $number) {
		$sql = "SELECT * FROM {$this->tableName()} WHERE location_id = :location_id AND number = :number";
		$params = array(":location_id" => $location_id, ":number" => $number);
		return $this->fetchOne($sql, $params);
	}


}
