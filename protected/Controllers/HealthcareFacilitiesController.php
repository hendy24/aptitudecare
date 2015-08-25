<?php

class HealthcareFacilitiesController extends MainPageController {


	/* 
	 * Manage page
	 *	
	 */
	public function manage() {
		if (isset (input()->location)) {
			$loc_id = input()->location;
		} else {
			//	Fetch the users default location
			$user = auth()->getRecord();
			$loc_id = $user->default_location;
		}
		$location = $this->loadModel('Location', $loc_id);
		smarty()->assign('location_id', $location->public_id);

		if (isset (input()->orderBy)) {
			$_orderBy = input()->orderBy;
		} else {
			$_orderBy = false;
		}

		$healthcare_facility = $this->loadModel("HealthcareFacility")->fetchManageData($location, $_orderBy);

		$classArray[0] = array();
		if (!empty ($healthcare_facility)) {
			foreach ($healthcare_facility as $key => $value) {
				foreach ($value as $k => $v) {
					$classArray[$key][$k] = $v;
					if (!in_array($k, $value->fetchFields())) {
						unset($classArray[$key][$k]);
					}

				}

			}
		}

		smarty()->assign('data', $classArray);		
	}


	public function location() {

		if (isset (input()->isMicro)) {
			smarty()->assign('isMicro', true);
		} else {
			smarty()->assign('isMicro', false);
		}

		// get healthcare facility types
		smarty()->assign('facilityTypes', $this->loadModel('LocationType')->getTypes());

		// get content based on whether we are adding a new facility or editing an existing one
		if (input()->type == "add") {
			smarty()->assign('title', "Add New Healthcare Facility");
			
			$l = $this->loadModel('HealthcareFacility');
		} else {
			smarty()->assign('title', "Edit Healthcare Facility");

			$l = $this->loadModel('HealthcareFacility', input()->id);
		}

		// assign the location info to a smarty variable
		smarty()->assignByRef('l', $l);

	}

	/*
	 * -------------------------------------------------------------------------
	 *  AJAX CALL TO SEARCH THE DATABASE FOR FACILITY NAMES
	 * -------------------------------------------------------------------------
	 */

	public function searchFacilityName() {
		$this->template = 'blank';
		$term = input()->query;
		if (isset (input()->location)) {
			$location = input()->location;
		} else {
			$location = false;
		}
		

		if ($term != '') {
			$results = $this->loadModel("HealthcareFacility")->searchFacilities($term, $location);
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
		if (!auth()->hasPermission("manage_healthcare_facilities")) {
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
			if (!input()->isMicro) {
				$this->redirect(array('page' => 'healthcare_facilities', 'action' => 'manage'));
			} else {
				$this->redirect(array('page' => 'data', 'action' => 'close'));
			}
		} else {
			session()->setFlash("Could not save/edit the facility.  Please try again.", 'error');
			$this->redirect(input()->path);
		}

	}



}