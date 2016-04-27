<?php

class PhotoTag extends Dietary {
	
	protected $table = "photo_tag";


	public function find_existing($name) {
		$sql = "SELECT * FROM {$this->tableName()} WHERE name = :name LIMIT 1";
		$params[":name"] = $name;
		return $this->fetchOne($sql, $params);
	}


	public function fetchTags($photo_id) {
		$photo_link_tag = $this->loadTable('PhotoLinkTag');
		$sql = "SELECT name FROM {$this->tableName()} pt INNER JOIN {$photo_link_tag->tableName()} plt ON plt.tag_id = pt.id WHERE plt.photo_id = :photo_id";
		$params[":photo_id"] = $photo_id;
		return $this->fetchAll($sql, $params);
	}


	public function fetchByName($tag_name) {
		$sql = "SELECT id FROM {$this->tableName()} WHERE name = :tag_name LIMIT 1";
		$params[":tag_name"] = $tag_name;
		return $this->fetchOne($sql, $params);
	}


	public function fetchBySearch($term) {
		$dietary_photo = $this->loadTable('Photo');

		// $sql = "(SELECT pt.name FROM {$this->tableName()} pt WHERE pt.name LIKE :term) UNION (SELECT p.name FROM {$dietary_photo->tableName()} p WHERE p.name LIKE :term) UNION (SELECT p.description FROM {$dietary_photo->tableName()} p WHERE description LIKE :term)";
		$sql = "SELECT DISTINCT p.filename, p.name, p.description FROM dietary_photo p LEFT JOIN dietary_photo_link_tag plt ON plt.photo_id = p.id LEFT JOIN dietary_photo_tag pt ON pt.id = plt.tag_id WHERE p.name LIKE :term OR pt.name LIKE :term";
		$params[":term"] = "%" . $term . "%";
		return $this->fetchAll($sql, $params);
	}
		
}