<?php

class Photo extends Dietary {

	protected $table = "photo";


	public function fetchApprovedPhotos() {
		// probably will want to sort these by date and paginate eventually
		$sql = "SELECT p.* FROM {$this->tableName()} p WHERE approved = true";
		return $this->fetchAll($sql);
	}


	/*
	 * -------------------------------------------------------------------------
	 *  PAGINATE & FETCH APPROVED PHOTOS
	 * -------------------------------------------------------------------------
	 */
	public function paginateApprovedPhotos($current_page = false) {
		$sql = "SELECT count(id) AS items FROM {$this->tableName()} WHERE {$this->tableName()}.approved = 1";
		$count = $this->fetchOne($sql);
		$params = null;

		$sql = "SELECT * FROM {$this->tableName()} WHERE {$this->tableName()}.approved = 1";

		$pagination = new Paginator();
		$pagination->default_ipp = 25;
		$pagination->items_total = $count->items;
		if ($current_page) {
			$pagination->current_page = $current_page;
		}

		return $pagination->paginate($sql, $params, $this);
	}


	public function fetchPhotosForApproval() {
		$location = $this->loadTable("Location");
		$user = $this->loadTable("User");
		$photo_link_tag = $this->loadTable('PhotoLinkTag');
		$photo_tag = $this->loadTable('PhotoTag');

		$sql = "SELECT p.*, l.name AS location_name, CONCAT(u.first_name, \" \", u.last_name) AS username FROM {$this->tableName()} p INNER JOIN {$location->tableName()} l ON l.id = p.location_id INNER JOIN {$user->tableName()} u ON u.id = p.user_created WHERE approved IS NULL ORDER BY datetime_created ASC";
		return $this->fetchAll($sql);
	}


	public function fetchPhotosWithoutInfo($user_id) {
		$sql = "SELECT * FROM {$this->tableName()} WHERE user_created = :user_id AND info_added = 0";
		$params[":user_id"] = $user_id;
		return $this->fetchAll($sql, $params);
	}


	/*
	 * -------------------------------------------------------------------------
	 *  FETCH PHOTOS USING SEARCH KEYWORDS
	 * -------------------------------------------------------------------------
	 */
	public function fetchPhotosBySearch($term) {

	}

}