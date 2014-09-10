<?php

class DmEquipment extends AppModel {

	protected $table = 'dme';

	public function fetchEquipment() {
		$sql = "SELECT * FROM {$this->table} ORDER BY description ASC";
		return $this->fetchAll($sql);
	}

}