<?php

class PhotoSubcategory extends Dietary {

	protected $table = 'photo_subcategory';

	public function fetchByCategoryId($category_id = null) {
		$params[":cat_id"] = $category_id;
		$sql = "SELECT * FROM {$this->tableName()} WHERE category_id = :cat_id ORDER BY name ASC";
		return $this->fetchAll($sql, $params);
	}
}