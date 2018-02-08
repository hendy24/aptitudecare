<?php

class PhotoLinkTag extends Dietary {

	protected $table = "photo_link_tag";


	public function deleteLinkedTag($photo_id = false, $tag_id = false) {
		if ($photo_id) {
			$params[":photo_id"] = $photo_id;
		}

		if ($tag_id) {
			$params[":tag_id"] = $tag_id;
		}

		$sql = "DELETE FROM {$this->tableName()} WHERE photo_id = :photo_id AND tag_id = :tag_id";

		if ($this->deleteQuery($sql, $params)) {
			return true;
		}

		return false;
	}

}