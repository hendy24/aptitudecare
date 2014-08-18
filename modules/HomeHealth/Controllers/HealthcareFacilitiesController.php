<?php

class HealthcareFacilitiesController extends MainController {

	public function searchFacilityName() {
		$this->template = 'blank';

		$term = input()->query;
		if ($term != '') {
			$tokens = explode(' ', $term);
			$params = array();

			$sql = "SELECT * FROM healthcare_facility WHERE ";
			foreach ($tokens as $idx => $token) {
				$token = trim($token);
				$sql .= " name like :term{$idx} AND";
				$params[":term{$idx}"] = "%{$token}%";
			}
			$sql = rtrim($sql, ' AND');

			if (isset (input()->location)) {
				$location = $this->loadModel('Location', input()->location);
				// $additionalStates = $this->loadModel('LocationLinkState', $location->id);


				$params[':location_state'] = $location->state;
				$sql .= " AND (healthcare_facility.state = :location_state";

				// foreach ($additionalStates as $k => $s) {
				// 	$params[':add_states{$k}'] = $s->state;
				// 	$sql .= " OR healthcare_facility.state = :add_states{$k}";
				// }

				$sql .= ") OR healthcare_facility.state = ''";
			} elseif (input()->state != '') {
				$params[':state'] = input()->state;
				$sql .= " AND healthcare_facility.state = :state";
			}

			$results = db()->fetchRows($sql, $params, 'healthcare_facility');
		} else {
			$results = array();
		}

		$resultArray = array();
		foreach ($results as $k => $r) {
			$resultArray['suggestions'][$k]['value'] = $r->name . ' (' . $r->state . ')';
			$resultArray['suggestions'][$k]['data'] = $r->id;
		}

		json_return($resultArray);
	}

	public function manage() {
		
	}

	public function add() {
		smarty()->assign('title', 'Add Healthcare Facility');
	}
}