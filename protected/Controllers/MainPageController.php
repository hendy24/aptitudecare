<?php

class MainPageController extends MainController {

	public $module;
	protected $locations;
	protected $location;
	protected $areas;
	protected $area;


/* 
 *  These are PDF pages to which access is allowed for PDF creation. When trying to generage a PDF from an HTML
 *  page mPDF does not keep the current session login connection, so this has be be overridden allow access to 
 *  these pages even when not logged in.
 *
 * 	NOTE: Need to check security on these pages as users who are not logged in could actually access these pages.
 *	
 */
	public function allow_access() {
		return array("meal_order_form", "adaptive_equipment", "allergies", "beverages", "meal_tray_card", "diet_census", "snack_report", "snack_labels");
	}


/* 
 *  An array of PDF pages which need to be printed in landscape mode. This array is searched from the framework's
 *  MainController.php page in the createPDF() method.
 *	
 */
	public function landscape_array($action) {
		$landscape_actions = array("meal_tray_card");
		if (in_array($action, $landscape_actions)) {
			return true;
		}
		return false;
	}




	public function index() {
		// Check if user is logged in, if not redirect to login page
		if (!auth()->isLoggedIn()) {
			$this->redirect(array('page' => 'Login', 'action' => 'index'));
		}

	}


	public function getSiteInfo() {
		// if there is a company logo in the img directory... load it, otherwise use the aptitudecare logo
		if (file_exists(SITE_DIR . '/public/img/logo.jpg')) {
			$logo = IMAGES . '/logo.jpg';
		} elseif (file_exists(SITE_DIR . '/public/img/logo.png')) {
			$logo = IMAGES . '/logo.png';
		} else {
			$logo = FRAMEWORK_IMAGES . '/aptitudecare.png';
		}
		smarty()->assign('logo', $logo);


		// If the user is logged in get the locations and area to which the user has access
		if (auth()->valid()) {
			$this->fetchLocations();
			if ($this->module == "HomeHealth") {
				$this->fetchArea();
			}
		} 

		// Fetch the modules to which the user has access
		if (auth()->isLoggedIn()) {
			$modules = $this->loadModel('Module')->fetchUserModules(auth()->getPublicId());
			smarty()->assign('modules', $modules);
		} 

		//	If no module variable is present get the session module
		if ($this->module == '') {
			$this->module = session()->getModule();
		} else {
			// if there is module set then set it to the session
			session()->setModule($this->module);
		}

		// Set the content from the controller and cooresponding view
		$this->setContent();
		

		if (auth()->isLoggedIn()) {
			if ($this->action == "admission_login") {
				// this is a hack to allow switching between modules until the admission module is rebuilt.
				$this->module = "HomeHealth";
			} else {
				$this->module = session()->getModule();
			}

			if ($this->action !== "user") {
				if (!$this->verifyModuleAccess($modules, $this->module)) {
					$this->redirect(array("module" => $this->loadModel("Module")->fetchDefaultModule()->name));
				}
			}
		} 
		smarty()->assign('module', $this->module);
	}


	private function setContent() {
		//	If the module is specified in the url we will look in the module directory first for the view file.
		//	If it is not there we will look next in the default view directory.
		if ($this->module != "") {
			if (file_exists(MODULES_DIR . DS . $this->module . DS . 'Views/' . underscoreString($this->page) . DS . $this->action . '.tpl')) {
				smarty()->assign('content', MODULES_DIR . DS . $this->module . DS . 'Views/' . underscoreString($this->page) . '/' . $this->action . '.tpl');
			} else {
				smarty()->assign('content', underscoreString($this->page) . '/' . $this->action . '.tpl');
			}

		//	If no module is set then we will get the content from the default view directory.
		} else {
			if (file_exists (VIEWS . DS . underscoreString($this->page) . DS . $this->action . '.tpl')) {
				smarty()->assign('content', underscoreString($this->page) . '/' . $this->action . '.tpl');
			} else {
				smarty()->assign('content', "error/no-template.tpl");
			}

		}
	}

	private function fetchLocations() {
		$areas = null;
		$selectedArea = null;
		if ($this->module == "HomeHealth") {
			$locations = $this->loadModel('Location')->fetchHomeHealthLocations();
			if (empty($locations)) {
				$locations = $this->loadModel('Location')->fetchHomeHealthLocationByLocation(auth()->getRecord()->default_location);
			}
			$location = $this->getSelectedLocation();
			// need to get the other locations to which the user has access and assign them as areas
			$areas = $this->loadModel('Location')->fetchFacilitiesByHomeHealthId($location->id);

			if (isset (input()->area)) {
				$selectedArea = $this->loadModel("Location", input()->area);
			} else {
				$selectedArea = "all";
			}
		} elseif ($this->module == "Dietary") {
			// Select only facilities
			$locations = $this->loadModel('Location')->fetchFacilities();
			// get either the selected location or the users' default location
			$location = $this->getSelectedLocation();
		} else {
			$locations = $this->loadModel('Location')->fetchAllLocations();
			$location = $this->getSelectedLocation($locations);
		}


		$this->locations = $locations;
		$this->location = $location;
		$this->areas = $areas;
		smarty()->assignByRef('locations', $locations);
		smarty()->assign('selectedLocation', $location);
		smarty()->assignByRef('areas', $areas);
		smarty()->assign('selectedArea', $selectedArea);
	}

	protected function getSelectedLocation() {
		$user = auth()->getRecord();

		if (isset (input()->location)) {
			$location = $this->loadModel('Location', input()->location);
		} else {
			$location = $this->loadModel('Location', $user->default_location);
		}

		// if we are on the homehealth module and the users default location type is 1
		// then we need to get the associated homehealth location
		if ($this->module == "HomeHealth" && $location->location_type == 1) {
			$location = $location->fetchHomeHealthLocation();
		}
		smarty()->assign("location", $location);
		return $location;
	}


	private function verifyModuleAccess($user_modules, $module) {
		if (!empty ($user_modules)) {
			foreach ($user_modules as $m) {
				if ($m->name == $module) {
					return true;
				}
			}
		} elseif (auth()->is_admin()) {
			return true;
		}

		return false;
	}


	private function fetchArea() {
		if (isset(input()->location)) {
			// If the location is set in the url, get the location by the public_id
			$location = $this->loadModel('Location', input()->location);
		} else {
			// Get the users default location from the session
			$location = $this->loadModel('Location', auth()->getDefaultLocation());
		}

		//  Check if the default location is home health, if not need to get the associated home health agency
		if ($location->location_type != 2) {
			$location = $location->fetchHomeHealthLocation();
		}

		if (isset (input()->area) && input()->area != "all" && input()->area != "") {
			$area = $this->loadModel('Location', input()->area);
		} else {
			$area = "all";
		}

		smarty()->assignByRef('loc', $location);
		smarty()->assignByRef('selectedArea', $area);
		$this->area = $area;
		return $area;
	}


	public function getModule() {
		return $this->module;
	}

	public function getLocation() {
		if (isset (input()->location)) {
			return $this->loadModel('Location', input()->location);
		} else {
			return $this->location;
		}
		
	}

	public function getArea() {
		if ($this->area != "" && $this->area != "all") {
			return $this->area;
		}
		return false;
	}

	public function getAreas() {
		return $this->areas;
	}

	public function searchReferralSources() {
		$this->template = 'blank';

		$term = input()->query;
		if ($term != '') {
			$tokens = explode(' ', $term);
			$params = array();
			$classes = array(
				'ac_case_manager' => 'CaseManager',
				'ac_physician' => 'Physician',
				'ac_healthcare_facility' => 'HealthcareFacility'
			);

			//	Get the location to which the patient will be admitted
			$location = $this->loadModel('Location', input()->location);
			$additionalStates = $this->loadModel('LocationLinkState')->getAdditionalStates($location->id);
			$params[":state"] = $location->state;
			foreach ($additionalStates as $key => $addState) {
				$params[":add_state{$key}"] = $addState->state;
			}

			$sql = null;

			foreach ($tokens as $idx => $token) {
				$token = trim($token);
				$params[":term{$idx}"] = "%{$token}%";
				foreach ($classes as $k => $t) {
					if ($k != 'ac_healthcare_facility') {
						$sql .= "(SELECT `{$k}`.`id`, `{$k}`.`public_id`, CONCAT(`{$k}`.`first_name`, ' ', `{$k}`.`last_name`) AS name, @type:=\"{$t}\" AS type FROM `{$k}`";
						if ($k == 'case_manager') {
							$sql .= " INNER JOIN `ac_healthcare_facility` ON `ac_healthcare_facility`.`id`=`case_manager`.`healthcare_facility_id`";
						}

						$sql .= " WHERE `{$k}`.`first_name` LIKE :term{$idx} OR `{$k}`.`last_name` LIKE :term{$idx}";
						if ($k == 'home_health_case_manager') {
							$sql .= " AND (`ac_healthcare_facility`.`state` = :state";
							foreach ($additionalStates as $key => $addState) {
								$sql .= " OR `ac_healthcare_facility`.`state` = :add_state{$key}";

							}
						} else {
							$sql .= " AND (`physician`.`state` = :state";
							foreach ($additionalStates as $key => $addState) {
								$sql .= " OR `ac_physician`.`state` = :add_state{$key}";
							}
						}
						$sql .= ")) UNION";
					} else {
						$sql .= "(SELECT `{$k}`.`id`, `{$k}`.`public_id`, `{$k}`.`name`, @type:=\"{$t}\" AS type FROM `{$k}` WHERE name LIKE :term{$idx} AND (`{$k}`.`state` = :state";
						foreach ($additionalStates as $key => $addState) {
							$sql .= " OR `{$k}`.`state` = :add_state{$key}";
						}
						$sql .= ")";
						$sql .= ") UNION";
					}
				}


			}

			$sql = trim($sql, ' UNION');

			foreach ($classes as $k => $c) {
				$class = new $c;
				$results[$k] = db()->fetchRows($sql, $params, $class);
			}

		} else {
			$results = array();
		}


		$resultArray = array();
		foreach ($results as $key => $r) {
			foreach ($r as $k => $i) {
				$resultArray['suggestions'][$k]['value'] = $i->name;
				$resultArray['suggestions'][$k]['data'] = array('id' => $i->id, 'type' => $i->type);
			}
		}

		json_return($resultArray);
	}


	public function search_results() {
		$this->helper = 'PatientMenu';
		$term = input()->term;
		$search_results = $this->loadModel('Patient')->fetchPatientSearch($term);
		smarty()->assignByRef('search_results', $search_results);
	}


	protected function dateDiff($start, $end) {
			$start_ts = strtotime($start);
			$end_ts = strtotime($end);
			$diff = $end_ts - $start_ts;
			return round($diff / 86400);

	}


	public function mealName($meal_id) {
		if ($meal_id == 1) {
			return "Breakfast";
		} elseif ($meal_id == 2) {
			return "Lunch";
		} elseif ($meal_id == 3) {
			return "Dinner";
		} else {
			return false;
		}
	}

}