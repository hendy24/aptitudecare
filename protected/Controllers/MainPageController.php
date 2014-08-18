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
			$tables = array(
				'case_manager' => 'CaseManager',
				'physician' => 'Physician',
				'healthcare_facility' => 'HealthcareFacility'
			);

			$sql = null;

			foreach ($tokens as $idx => $token) {
				$token = trim($token);
				$params[":term{$idx}"] = "%{$token}%";
				foreach ($tables as $k => $t) {
					if ($k != 'healthcare_facility') {
						$sql .= "(SELECT id, public_id, CONCAT(first_name, ' ', last_name) AS name, @type:=\"{$t}\" AS type FROM {$k} WHERE first_name LIKE :term{$idx} OR last_name LIKE :term{$idx}) UNION";
					} else {
						$sql .= "(SELECT id, public_id, name, @type:=\"{$t}\" AS type FROM {$k} WHERE name LIKE :term{$idx}) UNION";
					} 	
				}
				

			}

			$query = trim ($sql, ' UNION');
			$results = db()->fetchRows($query, $params, $tables);
			
		} else {
			$results = array();
		}


		$resultArray = array();
		foreach ($results as $k => $r) {
			$resultArray['suggestions'][$k]['value'] = $r->name;
			$resultArray['suggestions'][$k]['data'] = array('id' => $r->id, 'type' => $r->type);
		}

		json_return($resultArray);
	}






}