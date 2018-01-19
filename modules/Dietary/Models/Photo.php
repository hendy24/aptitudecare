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
 * FETCH THE PHOTOS FOR THE ADMIN MANAGE PHOTOS PAGE
 * -------------------------------------------------------------------------
 */
	public function fetchAllPhotos($location_id, $current_page = false) {
		$params = array();
		$user = $this->loadTable('User');
		$location = $this->loadTable('Location');

		// count the number of photos for pagination
		$sql = "SELECT count(id) AS items FROM {$this->tableName()} photo";
		if ($location_id != "") {
			$sql .= " WHERE photo.location_id = :location_id";
			$params[":location_id"] = $location_id;
		}
		$count = $this->fetchOne($sql, $params);


		// fetch the photos
		$sql = "SELECT photo.*, CONCAT(user.first_name, ' ', user.last_name) AS username, location.name AS location_name FROM {$this->tableName()} photo INNER JOIN {$user->tableName()} user ON user.id = photo.user_created INNER JOIN {$location->tableName()} location ON location.id = photo.location_id";
		if ($location_id != "") {
			$sql .= " WHERE photo.location_id = :location_id";
		}
		
		// paginate the photos
		$pagination = new Paginator();
		$pagination->default_ipp = 5;
		$pagination->items_total = $count->items;
		if ($current_page) {
			$pagination->current_page = $current_page;
		}

		return $pagination->paginate($sql, $params, $this);
	}





/*
 * -------------------------------------------------------------------------
 *  PAGINATE & FETCH APPROVED PHOTOS
 * -------------------------------------------------------------------------
 */
	public function paginateApprovedPhotos($current_page = false, $location_id = false) {
		$sql = "SELECT count(id) AS items FROM {$this->tableName()} WHERE {$this->tableName()}.approved = 1";
		$count = $this->fetchOne($sql);
		$params = null;

		$sql = "SELECT * FROM {$this->tableName()} photo WHERE photo.approved = 1";

		if ($location_id != "" && $location_id != false) {
			$sql .= " AND photo.location_id = :location_id";
			$params[":location_id"] = $location_id;
		}

		$pagination = new Paginator();
		$pagination->default_ipp = 25;
		$pagination->items_total = $count->items;
		if ($current_page) {
			$pagination->current_page = $current_page;
		}

		return $pagination->paginate($sql, $params, $this);
	}

	public function fetchBySubcategory($subcat_id) {
		$params[":subcat"] = $subcat_id;
		$sql = "SELECT * FROM {$this->tableName()} WHERE subcategory = :subcat ORDER BY datetime_created ASC";
		
		return $this->fetchAll($sql, $params);
	}

	public function fetchByCategory($cat_id) {
		$params[":cat"] = $cat_id;
		$sql = "SELECT * FROM {$this->tableName()} WHERE category = :cat ORDER BY datetime_created ASC";

		return $this->fetchAll($sql, $params);
	}

	public function fetchByFacility($facility_id) {
		$params[":facility"] = $facility_id;
		$sql = "SELECT * FROM {$this->tableName()} WHERE location_id = :facility ORDER BY datetime_created ASC";

		return $this->fetchAll($sql, $params);
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
	public function fetchBySearch($term, $location_id = false) {
		$photo_tag = $this->loadTable('PhotoTag');
		$photo_link_tag = $this->loadTable('PhotoLinkTag');

		$sql = "SELECT DISTINCT p.filename, p.name, p.description FROM {$this->tableName()} p LEFT JOIN {$photo_link_tag->tableName()} plt ON plt.photo_id = p.id LEFT JOIN {$photo_tag->tableName()} pt ON pt.id = plt.tag_id WHERE p.name LIKE :term OR pt.name LIKE :term";

		if ($location_id != "" && $location_id) {
			$sql .= " AND p.location_id = :location_id";
			$params[":location_id"] = $location_id;
		}
		$params[":term"] = "%" . $term . "%";
		return $this->fetchAll($sql, $params);
	}

}