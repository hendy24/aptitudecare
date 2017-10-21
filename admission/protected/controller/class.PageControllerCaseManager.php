<?php

class PageControllerCaseManager extends PageController {
	
	public function init() {
		Authentication::disallow();
	}
	
	public function searchCaseManagers() {
		$user = auth()->getRecord();
		
		$term = input()->term;
		if ($term != '') {
			$tokens = explode(" ", $term);
			$params = array();
			$sql = "select case_manager.id, case_manager.first_name, case_manager.last_name, case_manager.phone from case_manager inner join hospital on hospital.id = case_manager.hospital_id where ";
			$sql .= " (CONCAT_WS(' ', case_manager.first_name, case_manager.last_name) LIKE '%" . $term . "%'";
			$sql .= " OR CONCAT_WS(', ', case_manager.last_name, case_manager.first_name) LIKE '%" . $term . "%')";
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
				$sql .= " AND (hospital.state = :facilitystate";
				
				foreach ($additional_states as $k => $s) {
					$params[":additional_states{$k}"] = $s->state;
					$sql .= " OR hospital.state = :additional_states{$k}";
				}
					
				$sql .= ") OR hospital.state = ''";
			} elseif (input()->state != "") {
				$params[":state"] = input()->state;
				$sql .= " AND hospital.state = :state";
			}
			
						
			$results = db()->getRowsCustom($sql, $params);
		} else {
			$results = array();
		}
		
		
		json_return($results);
	}
	
	public function add() {
		$schedule = input()->schedule;		
		
		
		if (input()->isMicro == 1) {
			$isMicro = true;
		} else {
			$isMicro = false;
		}

		smarty()->assign("isMicro", $isMicro);
		smarty()->assign("schedule", $schedule);
		smarty()->assign("state", $state);
		
	}
	
	public function addCaseManager() {
		$obj = new CMS_Case_Manager();
						
		if (input()->first_name == "") {
			feedback()->error("Enter the first name and try again.");
			$this->redirect(SITE_URL . "/?page=caseManager&action=add");
		} else {
			$obj->first_name = input()->first_name;
		}
		
		
		if (input()->last_name != "") {
			$obj->last_name = input()->last_name;
		} 
		
		if (input()->hospital == '') {
			feedback()->error("Please enter the hospital and try again.");
			$this->redirect(SITE_URL . "/?page=caseManager&action=add");
		} else {
			$obj->hospital_id = input()->hospital;
		}	
				
		if (input()->phone != "") {
			$obj->phone = input()->phone;
		}
		if (input()->fax != "") {
			$obj->fax = input()->fax;
		}
		if (input()->email != "") {
			$obj->email = input()->email;
		}		
		
		
		try {
			$obj->save();
			feedback()->conf($obj->first_name . " " . $obj->last_name . " was successfully added");
		} catch (ORMException $e) {
			feedback()->error("An error was encountered while trying to save the new physician.");
		}
				
		if (feedback()->wasError()) {
			$this->redirect(SITE_URL . "/?page=caseManager&action=add&");
		} else {
			$this->redirect(SITE_URL . "/?page=caseManager&action=manage");
					
		}
	}
	
	
		public function addShadowboxCaseManager() {
		$obj = new CMS_Case_Manager();
						
		if (input()->first_name == "") {
			feedback()->error("Enter the first name and try again.");
			$this->redirect(SITE_URL . "/?page=caseManager&action=add");
		} else {
			$obj->first_name = input()->first_name;
		}
		
		
		if (input()->last_name == "") {
			feedback()->error("Enter the last name and try again.");
			$this->redirect(SITE_URL . "/?page=caseManager&action=add");
		} else {
			$obj->last_name = input()->last_name;
		}
		
		if (input()->hospital == '') {
			feedback()->error("Please enter the hospital and try again.");
			$this->redirect(SITE_URL . "/?page=caseManager&action=add");
		} else {
			$obj->hospital_id = input()->hospital;
		}	
				
		if (input()->phone != "") {
			$obj->phone = input()->phone;
		}
		if (input()->fax != "") {
			$obj->fax = input()->fax;
		}
		if (input()->email != "") {
			$obj->email = input()->email;
		}		
		
		
		try {
			$obj->save();
			feedback()->conf($obj->first_name . " " . $obj->last_name . " was successfully added");
		} catch (ORMException $e) {
			feedback()->error("An error was encountered while trying to save the new physician.");
		}
				
		if (feedback()->wasError()) {
			$this->redirect(SITE_URL . "/?page=caseManager&action=add&");
		} else {
			$this->redirect(SITE_URL . "/?page=patient&action=close_window");
					
		}
	}

	
	public function manage() {
		if (auth()->getRecord()->isAdmissionsCoordinator() != 1) {
			redirect(auth()->getRecord()->homeURL());
		}
		
		$facilities = auth()->getRecord()->getFacilities();
		$states = CMS_Facility::getStates($facilities);
		$user = auth()->getRecord();
		
		// Get list of all case managers
		$getter = CMS_Case_Manager::generate();
		$getter->paginationOn();
		$getter->paginationSetSliceSize(25);
		
		$slice = trim(strip_tags(input()->slice));
			if ($slice == '' || ! Validate::is_natural($slice)->success()) {
  			$slice = 1;
		}	
		$getter->paginationSetSlice($slice);
		
		if (input()->state != "") {
			$state = input()->state;
		} else {
			$facility = new CMS_Facility($user->default_facility);
			$state = $facility->state;
		}
		
		// CURRENTLY WORKING ON: Need to re-factor the function below to get case managers by state
		$case_managers = $getter->findCaseManagers($state);
		
		
		
				
		smarty()->assign('caseManagers', $case_managers);
		smarty()->assignByRef('getter', $getter);
		smarty()->assign('state', $state);
		smarty()->assign('states', $states);
	}
	
	
	
	
	/*
	 * -------------------------------------------------------------
	 *  EDIT CASE MANAGER
	 * -------------------------------------------------------------
	 * 
	 */
	
	public function edit() {
		if (auth()->getRecord()->isAdmissionsCoordinator() != 1) {
			redirect(auth()->getRecord()->homeURL());
		}
		$cm = new CMS_Case_Manager(input()->case_manager);
		
		if (input()->isMicro == 1) {
			$isMicro = true;
		} else {
			$isMicro = false;
		}

		smarty()->assign("isMicro", $isMicro);
		smarty()->assign('cm', $cm);
	}
	
	public function submitEdit() {
		if (auth()->getRecord()->isAdmissionsCoordinator() != 1) {
			redirect(auth()->getRecord()->homeURL());
		}
		$cm = new CMS_Case_Manager(input()->case_manager);
		
		if (input()->first_name != '') {
			$cm->first_name = input()->first_name;
		}
		
		if (input()->last_name != '') {
			$cm->last_name = input()->last_name;
		}
		
		if (input()->hospital_id != '') {
			$cm->hospital_id = input()->hospital_id;
		}
		
		if (input()->phone != '') {
			$cm->phone = input()->phone;	
		}
		
		if (input()->fax != '') {
			$cm->fax = input()->fax;	
		}
		
		if (input()->email != '') {
			$cm->email = input()->email;	
		}
							
		try {
			$cm->save();
			feedback()->conf("The information for {$cm->first_name} {$cm->last_name} has been saved.");
		} catch (ORMException $e) {
			feedback()->error("Could not save the case manager information.");
		}
		
		if (feedback()->wasError()) {
			$this->redirect(SITE_URL . "/?page=caseManager&action=edit&case_manager={$cm->pubid}");
		} else {
			$this->redirect(SITE_URL . "/?page=caseManager&action=manage");
		}
	}
	
	public function submitShadowboxEdit() {
		if (auth()->getRecord()->isAdmissionsCoordinator() != 1) {
			redirect(auth()->getRecord()->homeURL());
		}
		$cm = new CMS_Case_Manager(input()->case_manager);
		
		if (input()->first_name != '') {
			$cm->first_name = input()->first_name;
		}
		
		if (input()->last_name != '') {
			$cm->last_name = input()->last_name;
		}
		
		if (input()->hospital_id != '') {
			$cm->hospital_id = input()->hospital_id;
		}
		
		if (input()->phone != '') {
			$cm->phone = input()->phone;	
		}
		
		if (input()->fax != '') {
			$cm->fax = input()->fax;	
		}
		
		if (input()->email != '') {
			$cm->email = input()->email;	
		}
							
		try {
			$cm->save();
			feedback()->conf("The information for {$cm->first_name} {$cm->last_name} has been saved.");
		} catch (ORMException $e) {
			feedback()->error("Could not save the case manager information.");
		}
		
		if (feedback()->wasError()) {
			$this->redirect(SITE_URL . "/?page=caseManager&action=edit&case_manager={$cm->pubid}");
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
		$cm = new CMS_Case_Manager(input()->case_manager);
				
		if ($cm->deleteCaseManager($cm->id)) {
			feedback()->conf("{$cm->first_name} {$cm->last_name} was successfully deleted.");
			$this->redirect(SITE_URL . "/?page=caseManager&action=manage");
		} else {
			feedback()->error("Could not delete the case manager.");
			$this->redirect(SITE_URL . "/?page=caseManager&action=manage");
		}
	}

}