<?php

class AdmissionDashboardLocation extends AppModel {

	protected $prefix = false;
	protected $table = "x_site_user_link_facility";
	protected $dbname = null;

	public function __construct() {
		$this->dbname = db()->dbname2;
	}


	
}