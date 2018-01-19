<?php

class PhotoCategory extends Dietary {

	protected $table = 'photo_category';

	public function fetchCategories() {
		$sql = "SELECT * FROM {$this->tableName()} ORDER BY name ASC";
		return $this->fetchAll($sql);
	}
	
}