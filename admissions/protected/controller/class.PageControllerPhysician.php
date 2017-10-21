<?php

class PageControllerPhysician extends PageController {

	public function init() {
		Authentication::disallow();
	}
	
	public function searchPhysicians() {
		$user = auth()->getRecord();
		
		if (input()->state != "") {
			$state = input()->state;
		} else {
			$state = $facility->state;
		}
				
		$term = input()->term;
		if ($term != '') {
			$tokens = explode(" ", $term);
			$params = array();
			$sql = "select * from physician where ";
			$sql .= " (CONCAT_WS(' ', physician.first_name, physician.last_name) LIKE '%" . $term . "%'";
			$sql .= " OR CONCAT_WS(', ', physician.last_name, physician.first_name) LIKE '%" . $term . "%')";
/*
			foreach ($tokens as $idx => $token) {
				$token = trim($token);
				$sql .= " CONCAT_WS(' ', physician.first_name, physician.last_name) LIKE :term{$idx}";
				$sql .= " OR CONCAT_WS(', ', physician.last_name, physician.first_name) LIKE :term{$idx}";
				$sql .= " last_name like :term{$idx} OR first_name like :term{$idx}";
				$params[":term{$idx}"] = "%{$token}%";
			}
*/
			$sql = rtrim($sql, " AND");
			
			if (input()->facility != "") {
				$facility = new CMS_Facility(input()->facility);
				$as = new CMS_Facility_Link_States();
				$additional_states = $as->getAdditionalStates($facility->id);
								
				$params[":facilitystate"] = $facility->state;
				$sql .= " AND (physician.state = :facilitystate";
				
				foreach ($additional_states as $k => $s) {
					$params[":additional_states{$k}"] = $s->state;
					$sql .= " OR physician.state = :additional_states{$k}";
				}
					
				$sql .= ") OR physician.state = ''";
			} elseif (input()->state != "") {
				$params[":state"] = input()->state;
				$sql .= " AND physician.state = :state";
			}
						
			$results = db()->getRowsCustom($sql, $params);
		} else {
			$results = array();
		}
		
		json_return($results);
	}
	
	public function add() {
		if (input()->isMicro == 1) {
			$isMicro = true;
		} else {
			$isMicro = false;
		}
	
		smarty()->assign("isMicro", $isMicro);
		smarty()->assign("physicianType", input()->type);
		smarty()->assign("schedule", input()->schedule);
	}
	
	public function addPhysician() {
		$obj = new CMS_Physician();
		$schedule = input()->schedule;
		$type = input()->type;
		
		if (input()->first_name == "") {
			feedback()->error("Enter the first name and try again.");
			$this->redirect(SITE_URL . "?page=physician&action=add&type=$type");
		} else {
			$obj->first_name = input()->first_name;
		}
		
		if (input()->last_name == "") {
			feedback()->error("Enter the last name and try again.");
			$this->redirect(SITE_URL . "?page=physician&action=add&type=$type");
		} else {
			$obj->last_name = input()->last_name;
		}
				
		if (input()->address != "") {
			$obj->address = input()->address;
		}
		if (input()->city != "") {
			$obj->city = input()->city;
		}
						
		if (input()->state_id == "") {
			feedback()->error("Please enter the state in which the provider is located.");
			$this->redirect(SITE_URL . "?page=physician&action=add");
		} else {
			$validate = Validate::is_USAState(input()->state);
			if ($validate->success() == false) {
				feedback()->error("Please enter a valid state");
			} else {
				$obj->state = input()->state_id;
			}

		} 
		
		if (input()->zip != "") {
			$obj->zip = input()->zip;
		}
		if (input()->phone != "") {
			$obj->phone = input()->phone;
		}
		if (input()->fax != "") {
			$obj->fax = input()->fax;
		}
		
		try {
			$obj->save();
			feedback()->conf($obj->first_name . " " . $obj->last_name . " was successfully added");
		} catch (ORMException $e) {
			feedback()->error("An error was encountered while trying to save the new physician.");
		}
				
		if (feedback()->wasError()) {
			$this->redirect(SITE_URL . "/?page=physician&action=add&type=$type");
		} else {
			$this->redirect(SITE_URL . "/?page=physician&action=manage");
					
		}

	}
	
	
	public function addShadowboxPhysician() {
		$obj = new CMS_Physician();
		$schedule = input()->schedule;
		$type = input()->type;
		
		if (input()->first_name == "") {
			feedback()->error("Enter the first name and try again.");
			$this->redirect(SITE_URL . "?page=physician&action=add&type=$type");
		} else {
			$obj->first_name = input()->first_name;
		}
		
		if (input()->last_name == "") {
			feedback()->error("Enter the last name and try again.");
			$this->redirect(SITE_URL . "?page=physician&action=add&type=$type");
		} else {
			$obj->last_name = input()->last_name;
		}
				
		if (input()->address != "") {
			$obj->address = input()->address;
		}
		if (input()->city != "") {
			$obj->city = input()->city;
		}
						
		if (input()->state_id == "") {
			feedback()->error("Please enter the state in which the provider is located.");
			$this->redirect(SITE_URL . "?page=physician&action=add");
		} else {
			$validate = Validate::is_USAState(input()->state);
			if ($validate->success() == false) {
				feedback()->error("Please enter a valid state");
			} else {
				$obj->state = input()->state_id;
			}

		} 
		
		if (input()->zip != "") {
			$obj->zip = input()->zip;
		}
		if (input()->phone != "") {
			$obj->phone = input()->phone;
		}
		if (input()->fax != "") {
			$obj->fax = input()->fax;
		}
		
		try {
			$obj->save();
			feedback()->conf($obj->first_name . " " . $obj->last_name . " was successfully added");
		} catch (ORMException $e) {
			feedback()->error("An error was encountered while trying to save the new physician.");
		}
				
		if (feedback()->wasError()) {
			$this->redirect(SITE_URL . "?page=physician&action=add&type=$type");
		} else {
			$this->redirect(SITE_URL . "?page=patient&action=close_window");				
		}

	}
	
	public function manage() {
		if (auth()->getRecord()->isAdmissionsCoordinator() != 1) {
			redirect(auth()->getRecord()->homeURL());
		}

		$facilities = auth()->getRecord()->getFacilities();
		$states = CMS_Facility::getStates($facilities);
		
		$user = auth()->getRecord();
		
		
		
		$getter = CMS_Physician::generate();
		$getter->paginationOn();
		$getter->paginationSetSliceSize(25);
		
		$slice = trim(strip_tags(input()->slice));
			if ($slice == '' || ! Validate::is_natural($slice)->success()) {
  			$slice = 1;
		}	
		$getter->paginationSetSlice($slice);
			
		if (input()->state != "") {
			// Get list of all the physicians
			$state = input()->state;
			$physicians = $getter->findPhysicians($state);
		} else {
			$facility = new CMS_Facility($user->default_facility);
			$state = $facility->state;
			$physicians = $getter->findPhysicians($facility->state);
		}
		
		smarty()->assign('physicians', $physicians);
		smarty()->assignByRef('getter', $getter);
		smarty()->assign('state', $state);
		smarty()->assign('states', $states);

	}
	
	public function edit() {
		if (auth()->getRecord()->isAdmissionsCoordinator() != 1) {
			redirect(auth()->getRecord()->homeURL());
		}
		$p = new CMS_Physician(input()->physician);
		
		if (input()->isMicro == 1) {
			$isMicro = true;
		} else {
			$isMicro = false;
		}
		
		smarty()->assign('isMicro', $isMicro);
		smarty()->assign('p', $p);
	}
	
	public function submitEdit() {
		if (auth()->getRecord()->isAdmissionsCoordinator() != 1) {
			redirect(auth()->getRecord()->homeURL());
		}
		$p = new CMS_Physician(input()->physician);
		
		if (input()->first_name != '') {
			$p->first_name = input()->first_name;
		}
		
		if (input()->last_name != '') {
			$p->last_name = input()->last_name;
		}
		
		if (input()->address != '') {
			$p->address = input()->address;	
		}
		
		if (input()->city != '') {
			$p->city = input()->city;	
		}
		
		if (input()->state != '') {
			$p->state = input()->state;	
		}
		
		if (input()->zip != '') {
			$p->zip = input()->zip;	
		}
		
		if (input()->phone != '') {
			$p->phone = input()->phone;	
		}
		if (input()->fax != '') {
			$p->fax = input()->fax;
		}
					
		try {
			$p->save();
			feedback()->conf("The information for {$p->first_name} {$p->last_name} has been saved.");
		} catch (ORMException $e) {
			feedback()->error("Could not save the physician information.");
		}
		
		if (feedback()->wasError()) {
			$this->redirect(SITE_URL . "/?page=physician&action=edit&physician={$p->pubid}");
		} else {
			$this->redirect(SITE_URL . "/?page=physician&action=manage");
		}

	}
	
	
	public function submitShadowboxEdit() {
		if (auth()->getRecord()->isAdmissionsCoordinator() != 1) {
			redirect(auth()->getRecord()->homeURL());
		}
		$p = new CMS_Physician(input()->physician);
		
		if (input()->first_name != '') {
			$p->first_name = input()->first_name;
		}
		
		if (input()->last_name != '') {
			$p->last_name = input()->last_name;
		}
		
		if (input()->address != '') {
			$p->address = input()->address;	
		}
		
		if (input()->city != '') {
			$p->city = input()->city;	
		}
		
		if (input()->state != '') {
			$p->state = input()->state;	
		}
		
		if (input()->zip != '') {
			$p->zip = input()->zip;	
		}
		
		if (input()->phone != '') {
			$p->phone = input()->phone;	
		}
		if (input()->fax != '') {
			$p->fax = input()->fax;
		}
					
		try {
			$p->save();
			feedback()->conf("The information for {$p->first_name} {$p->last_name} has been saved.");
		} catch (ORMException $e) {
			feedback()->error("Could not save the physician information.");
		}
		
		if (feedback()->wasError()) {
			$this->redirect(SITE_URL . "/?page=physician&action=edit&physician={$p->pubid}");
		} else {
			$this->redirect(SITE_URL . "/?page=patient&action=close_window");
		}

	}

	
	
	
	
	/*
	 * -------------------------------------------------------------
	 *  DELETE THE CASE MANAGER
	 * -------------------------------------------------------------
	 * 
	 */
	 
	public function delete() {
		if (auth()->getRecord()->isAdmissionsCoordinator() != 1) {
			redirect(auth()->getRecord()->homeURL());
		}
		$p = new CMS_Physician(input()->physician);
				
		if ($p->deletePhysician($p->id)) {
			feedback()->conf("{$p->first_name} {$p->last_name} was successfully deleted.");
			$this->redirect(SITE_URL . "/?page=physician&action=manage");
		} else {
			feedback()->error("Could not delete the physician.");
			$this->redirect(SITE_URL . "/?page=physician&action=manage");
		}
	}


}