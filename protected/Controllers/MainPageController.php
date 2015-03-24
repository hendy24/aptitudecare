<?php

class MainPageController extends MainController {
	
		
	public function index() {
		// Check if user is logged in, if not redirect to login page
		if (!auth()->isLoggedIn()) {
			$this->redirect(array('page' => 'Login', 'action' => 'index'));
		}
		
	}


	public function getLocations() {
		
		$selectedLocation = null;

		// get all the locations to which the user has access
		if (isset(input()->module)) {
			// if the module is home health, select only home health locations
			if (input()->module == "HomeHealth") {
				$locations = $this->loadModel('Location')->fetchHomeHealthLocations($this->module);

				// get either the selected location or the users' default location
				$location = $this->getSelectedLocation($locations);

				// need to get the other locations to which the user has access and assign them as areas
				$areas = $this->loadModel('Location')->fetchFacilitiesByHomeHealthId($location->id);
			} elseif (input()->module == "Dietary") {
				// Select only facilities
				$locations = $this->loadModel('Location')->fetchFacilities();
				// get either the selected location or the users' default location
				$location = $this->getSelectedLocation($locations);
			}

		} else {
			$locations = $this->loadModel('Location')->fetchAllLocations();
			$location = $this->getSelectedLocation($locations);
		}
		
		
		// get the users default location
		smarty()->assignByRef('locations', $locations);
		smarty()->assign('selectedLocation', $location);
		smarty()->assignByRef('areas', $areas);
	}	


	private function getSelectedLocation($locations) {
		$user = auth()->getRecord();
		

		if (isset (input()->location)) {
			$location = $this->loadModel('Location', input()->location);
		} else {
			$location = $this->loadModel('Location', $user->default_location);
		}
		

		if (isset (input()->module)) {
			// if we are on the homehealth module and the users default location type is 1
			// then we need to get the associated homehealth location
			if (input()->module == "HomeHealth" && $location->location_type == 1) {
				$location = $location->fetchHomeHealthLocation();
			}
		}

		return $location;
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



}