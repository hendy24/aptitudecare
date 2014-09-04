<?php

class DmEquipment extends AppModel {

	protected $table = 'dme';

	public function fetchEquipment() {
		$sql = "SELECT * FROM {$this->table}";
		return $this->fetchAll($sql);
	}
}