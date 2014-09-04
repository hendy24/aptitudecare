<?php

class MainPageController extends MainController {
	
		
	public function index() {
		
		// Check if user is logged in, if not redirect to login page
		$this->redirect(array('page' => 'Login', 'action' => 'index'));
	}
	

	public function searchReferralSources() {
		$this->template = 'blank';

		$term = input()->query;
		if ($term != '') {
			$tokens = explode(' ', $term);
			$params = array();
			$classes = array(
				'case_manager' => 'CaseManager',
				'physician' => 'Physician',
				'healthcare_facility' => 'HealthcareFacility'
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
					if ($k != 'healthcare_facility') {
						$sql .= "(SELECT `{$k}`.`id`, `{$k}`.`public_id`, CONCAT(`{$k}`.`first_name`, ' ', `{$k}`.`last_name`) AS name, @type:=\"{$t}\" AS type FROM `{$k}`";
						if ($k == 'case_manager') {
							$sql .= " INNER JOIN `healthcare_facility` ON `healthcare_facility`.`id`=`case_manager`.`healthcare_facility_id`";
						}

						$sql .= " WHERE `{$k}`.`first_name` LIKE :term{$idx} OR `{$k}`.`last_name` LIKE :term{$idx}";
						if ($k == 'case_manager') {
							$sql .= " AND (`healthcare_facility`.`state` = :state";
							foreach ($additionalStates as $key => $addState) {
								$sql .= " OR `healthcare_facility`.`state` = :add_state{$key}";
								
							}
						} else {
							$sql .= " AND (`physician`.`state` = :state";
							foreach ($additionalStates as $key => $addState) {
								$sql .= " OR `physician`.`state` = :add_state{$key}";
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
		smarty()->assignByRef('search_results', $this->loadModel('Patient')->fetchPatientSearch($term));

		
	}




}