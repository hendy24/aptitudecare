<?php

class AdmissionDashboardLocations extends AppModel {

	protected $prefix = false;
	protected $table = "x_site_user_link_facility";

	public function __construct() {
		$this->dbname = db()->dbname2;
	}


	
}