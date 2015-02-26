<?php

class HealthcareFacilitiesController extends MainPageController {


	/*
	 * -------------------------------------------------------------------------
	 *  AJAX CALL TO SEARCH THE DATABASE FOR FACILITY NAMES
	 * -------------------------------------------------------------------------
	 */

	public function searchFacilityName() {
		$this->template = 'blank';

		$term = input()->query;

		if ($term != '') {
			$tokens = explode(' ', $term);
			$params = array();

			$sql = "SELECT * FROM ac_healthcare_facility WHERE ";
			foreach ($tokens as $idx => $token) {
				$token = trim($token);
				$sql .= " name like :term{$idx} AND";
				$params[":term{$idx}"] = "%{$token}%";
			}
			$sql = rtrim($sql, ' AND');

			if (isset (input()->location)) {
				$location = $this->loadModel('Location', input()->location);
				$additionalStates = $this->loadModel('LocationLinkState')->getAdditionalStates($location->id);

				$params[':location_state'] = $location->state;
				$sql .= " AND (ac_healthcare_facility.state = :location_state";

				foreach ($additionalStates as $k => $s) {
					$params[":add_states{$k}"] = $s->state;
					$sql .= " OR ac_healthcare_facility.state = :add_states{$k}";
				}

				$sql .= ")";
			} elseif (isset (input()->state) && input()->state != '') {
				$params[':state'] = input()->state;
				$sql .= " AND ac_healthcare_facility.state = :state";
			}

			$sql .= " ORDER BY `ac_healthcare_facility`.`name` ASC";

			$class = $this->loadModel('HealthcareFacility');

			$results = db()->fetchRows($sql, $params, $class);

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



	/*
	 * -------------------------------------------------------------------------
	 *  ADD A NEW HEALTHCARE FACILITY
	 * -------------------------------------------------------------------------
	 */

	public function getAdditionalData($data = false) {
		//	Get facility type options
		if ($data) {
			smarty()->assign('location_type_id', $data->location_type_id);
		}
		$facility_types = $this->loadModel('LocationType')->getTypes();
		smarty()->assignByRef('facilityTypes', $facility_types);
	}

	public function submitAdd() {
		if (!auth()->has_permission('add', 'healthcare_facility')) {
			$error_messages[] = "You do not have permission to add new healthcare facilities";
			session()->setFlash($error_messages, 'error');
			$this->redirect();
		}

		if (isset (input()->id)) {
			$id = input()->id;
		} else {
			$id = null;
		}

		$facility = $this->loadModel('HealthcareFacility', $id);

		if (input()->name != '') {
			$facility->name = input()->name;
		} else {
			$error_messages[] = "Enter the facility name";
		}

		if (input()->address != '') {
			$facility->address = input()->address;
		}

		if (input()->city != '') {
			$facility->city = input()->city;
		} else {
			$error_messages[] = "Enter the city";
		}

		if (input()->state != '') {
			$facility->state = input()->state;
		} else {
			$error_messages[] = "Enter the state";
		}

		if (input()->zip != '') {
			$facility->zip = input()->zip;
		} else {
			$error_messages[] = "Enter the zip code";
		}

		if (input()->phone != '') {
			$facility->phone = input()->phone;
		} 

		if (input()->fax != '') {
			$facility->fax = input()->fax;
		} 

		if (input()->location_type != '') {
			$facility->location_type_id = input()->location_type;
		} else {
			$error_messages[] = "Select the location type";
		}

		if (isset ($facility->location_type)) {
			unset($facility->location_type);
		}


		//	BREAKPOINT
		if (!empty ($error_messages)) {
			session()->setFlash($error_messages, 'error');
			$this->redirect(input()->path);
		}

		//	If we've made it this far then save the new facility data
		if ($facility->save()) {
			session()->setFlash("Successfully added/edited {$facility->name}", 'success');
			if (!isset (input()->isMicro)) {
				$this->redirect(array('page' => 'data', 'action' => 'manage', 'type' => 'healthcare_facilities'));
			} else {
				$this->redirect(array('page' => 'data', 'action' => 'close'));
			}
		} else {
			session()->setFlash("Could not save/edit the facility.  Please try again.", 'error');
			$this->redirect(input()->path);
		}

	}



}