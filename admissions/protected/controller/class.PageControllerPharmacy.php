<?php

class PageControllerPharmacy extends PageController {
	
	public function init() {
		Authentication::disallow();
	}
	
	public function searchPharmacies() {
		$user = auth()->getRecord();
		if (input()->facility != "") {
			$facility = new CMS_Facility(input()->facility);
		} else {
			$facility = new CMS_Facility($user->default_facility);
		}
		
		
		$term = input()->term;
		if ($term != '') {
			$tokens = explode(" ", $term);
			$params = array();
			$params[":facilitystate"] = $facility->state;
			$sql = "select * from pharmacy where ";
			foreach ($tokens as $idx => $token) {
				$token = trim($token);
				$sql .= " name like :term{$idx} AND";
				$params[":term{$idx}"] = "%{$token}%";
			}
			$sql = rtrim($sql, " AND");
			$sql .= " AND pharmacy.state = :facilitystate";
			$results = db()->getRowsCustom($sql, $params);
		} else {
			$results = array();
		}

		json_return($results);
	}
	
	/*
	 * -------------------------------------------------------------
	 *  MANAGE PHARMACIES
	 * -------------------------------------------------------------
	 * 
	 */
	 
	public function manage() {
		if (auth()->getRecord()->isAdmissionsCoordinator() != 1) {
			redirect(auth()->getRecord()->homeURL());
		}
		
		$facilities = auth()->getRecord()->getFacilities();
		$states = CMS_Facility::getStates($facilities);
		
		$user = auth()->getRecord();
		
		// Get list of all case managers
		$getter = CMS_Pharmacy::generate();
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
		
		$pharmacies = $getter->findPharmacies($state);
						
		smarty()->assign('pharmacies', $pharmacies);
		smarty()->assignByRef('getter', $getter);
		smarty()->assign('state', $state);
		smarty()->assign('states', $states);

	}
	
	
	
	/*
	 * -------------------------------------------------------------
	 *  ADD NEW PHARMACY
	 * -------------------------------------------------------------
	 * 
	 */
	 
	public function add() {
		if (input()->schedule != '') {
			$schedule = input()->schedule;
		} else {
			$schedule = '';
		}
		
		if (input()->isMicro == 1) {
			$isMicro = true;
		} else {
			$isMicro = false;
		}

		smarty()->assign("isMicro", $isMicro);	
		smarty()->assign('schedule', $schedule);

	}
	
	public function addLocation() {
		$loc = new CMS_Pharmacy();
		$state = input()->state;
		
		if (input()->location_name == "") {
			feedback()->error("Enter the pharmacy name and try again.");
			$this->redirect(SITE_URL . "?page=pharmacy&action=add");
		} else {
			$loc->name = input()->location_name;
		}
		
		
		if (input()->address != "") {
			$loc->address = input()->address;
		}
		
		if (input()->city != "") {
			$loc->city = input()->city;
		}
		
		if (input()->state_id == "") {
			feedback()->error("Please enter the state in which the facility is located.");
			$this->redirect(SITE_URL . "?page=pharmacy&action=add");
		} else {
			$validate = Validate::is_USAState(input()->state);
			if ($validate->success() == false) {
				feedback()->error("Please enter a valid state");
			} else {
				$loc->state = input()->state_id;
			}

		} 
		
		if (input()->zip != "") {
			$loc->zip = input()->zip;
		}
		if (input()->phone != "") {
			$loc->phone = input()->phone;
		}
		if (input()->fax != "") {
			$loc->fax = input()->fax;
		}
		
		if (input()->schedule != '') {
			$schedule = input()->schedule;
		}
		
		try {
			$loc->save();
			feedback()->conf($loc->name . " was successfully added");
		} catch (ORMException $e) {
			feedback()->error("An error was encountered while trying to save the new location.");
		}
				
		if (feedback()->wasError()) {
			$this->redirect(SITE_URL . "/?page=coord");
		} else {
			if ($schedule != '') {
				$this->redirect(SITE_URL . "/?page=patient&action=inquiry&schedule={$schedule}&weekSeed=&mode=edit");
			} else {
				$this->redirect(SITE_URL . "/?page=pharmacy&action=manage&state={$state}");
			}			
		}
		
	}
	
	
	public function addShadowboxLocation() {
		$loc = new CMS_Pharmacy();
		
		if (input()->name == "") {
			feedback()->error("Enter the pharmacy name and try again.");
			$this->redirect(SITE_URL . "?page=coord&action=admit");
		} else {
			$loc->name = input()->name;
		}
		
		
		if (input()->address != "") {
			$loc->address = input()->address;
		}
		
		if (input()->city != "") {
			$loc->city = input()->city;
		}
		
		if (input()->state == "") {
			feedback()->error("Please enter the state in which the facility is located.");
			$this->redirect(SITE_URL . "?page=coord&action=admit");
		} else {
			$validate = Validate::is_USAState(input()->state);
			if ($validate->success() == false) {
				feedback()->error("Please enter a valid state");
			} else {
				$loc->state = input()->state;
			}

		} 
		
		if (input()->zip != "") {
			$loc->zip = input()->zip;
		}
		if (input()->phone != "") {
			$loc->phone = input()->phone;
		}
		if (input()->fax != "") {
			$loc->fax = input()->fax;
		}
		
		if (input()->schedule != '') {
			$schedule = input()->schedule;
		}
		
		try {
			$loc->save();
			feedback()->conf($loc->name . " was successfully added");
		} catch (ORMException $e) {
			feedback()->error("An error was encountered while trying to save the new location.");
		}
				
		if (feedback()->wasError()) {
			$this->redirect(SITE_URL . "/?page=coord");
		} else {
			$this->redirect(SITE_URL . "/?page=patient&action=close_window");
		}			
		
	}
	
	
		/*
	 * -------------------------------------------------------------
	 *  EDIT PHARMACY
	 * -------------------------------------------------------------
	 * 
	 */
	 
	
	public function edit() {
		if (auth()->getRecord()->isAdmissionsCoordinator() != 1) {
			redirect(auth()->getRecord()->homeURL());
		}
		$pharmacy = new CMS_Pharmacy(input()->pharmacy);		
		
		if (input()->isMicro == 1) {
			$isMicro = true;
		} else {
			$isMicro = false;
		}

		smarty()->assign("isMicro", $isMicro);
		smarty()->assign('pharmacy', $pharmacy);
		
		
	}
	
	public function submitEdit() {
		if (auth()->getRecord()->isAdmissionsCoordinator() != 1) {
			redirect(auth()->getRecord()->homeURL());
		}
		$pharmacy = new CMS_Pharmacy(input()->pharmacy);
		
		if (input()->name != '') {
			$pharmacy->name = input()->name;
		}
		
		
		if (input()->address != '') {
			$pharmacy->address = input()->address;
		}
		
		if (input()->state != '') {
			$pharmacy->state = input()->state;	
		}
		
		if (input()->city != '') {
			$pharmacy->city = input()->city;	
		}
		
		if (input()->zip != '') {
			$pharmacy->zip = input()->zip;	
		}
		
		if (input()->phone != '') {
			$pharmacy->phone = input()->phone;	
		}
		
		if (input()->fax != '') {
			$pharmacy->fax = input()->fax;	
		}
		
							
		try {
			$pharmacy->save();
			feedback()->conf("The information for {$pharmacy->name} has been saved.");
		} catch (ORMException $e) {
			feedback()->error("Could not save the pharmacy information.");
		}
		
		if (feedback()->wasError()) {
			$this->redirect(SITE_URL . "/?page=pharmacy&action=edit&pharmacy={$pharmacy->pubid}");
		} else {
			$this->redirect(SITE_URL . "/?page=pharmacy&action=manage");
			
		}
	}
	
	
	public function submitShadowboxEdit() {
		if (auth()->getRecord()->isAdmissionsCoordinator() != 1) {
			redirect(auth()->getRecord()->homeURL());
		}
		$pharmacy = new CMS_Pharmacy(input()->pharmacy);
		
		if (input()->name != '') {
			$pharmacy->name = input()->name;
		}
		
		
		if (input()->address != '') {
			$pharmacy->address = input()->address;
		}
		
		if (input()->state != '') {
			$pharmacy->state = input()->state;	
		}
		
		if (input()->city != '') {
			$pharmacy->city = input()->city;	
		}
		
		if (input()->zip != '') {
			$pharmacy->zip = input()->zip;	
		}
		
		if (input()->phone != '') {
			$pharmacy->phone = input()->phone;	
		}
		
		if (input()->fax != '') {
			$pharmacy->fax = input()->fax;	
		}
		
							
		try {
			$pharmacy->save();
			feedback()->conf("The information for {$pharmacy->name} has been saved.");
		} catch (ORMException $e) {
			feedback()->error("Could not save the pharmacy information.");
		}
		
		if (feedback()->wasError()) {
			$this->redirect(SITE_URL . "/?page=pharmacy&action=edit&pharmacy={$pharmacy->pubid}");
		} else {
			$this->redirect(SITE_URL . "/?page=patient&action=close_window");
			
		}
	}
	
	
	
		
	/*
	 * -------------------------------------------------------------
	 *  DELETE THE HOSPITAL
	 * -------------------------------------------------------------
	 * 
	 */
	 
	public function delete() {
		if (auth()->getRecord()->isAdmissionsCoordinator() != 1) {
			redirect(auth()->getRecord()->homeURL());
		}
		$pharmacy = new CMS_Pharmacy(input()->pharmacy);
		
				
		if ($pharmacy->deletePharmacy($pharmacy->id)) {
			feedback()->conf("{$pharmacy->name} was successfully deleted.");
			$this->redirect(SITE_URL . "/?page=pharmacy&action=manage");
		} else {
			feedback()->error("Could not delete the pharmacy.");
			$this->redirect(SITE_URL . "/?page=pharmacy&action=manage");
		}
	}




}