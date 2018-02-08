<?php

class DmEquipment extends HomeHealth {

	protected $table = 'dme';

	public function fetchEquipment() {
		$sql = "SELECT * FROM {$this->tableName()} ORDER BY description ASC";
		return $this->fetchAll($sql);
	}

}