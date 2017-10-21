<?php

class PageControllerSiteUser extends PageController {
	
	
	
	/*
	 * -------------------------------------------------------------
	 *  MANAGE SITE USERS
	 * -------------------------------------------------------------
	 * 
	 */
	
	
	public function manage() {
		if (auth()->getRecord()->isAdmissionsCoordinator() != 1) {
			redirect(auth()->getRecord()->homeURL());
		}
		// Get the users' facilities
		$facilities = auth()->getRecord()->getFacilities();	
		
		if (input()->facility != '') {
			$facility = new CMS_Facility(input()->facility);
		} else {
			$facility = new CMS_Facility(auth()->getRecord()->default_facility);
		}
		
		// Get the users for the selected facility
		$objUser = CMS_Site_User::generate();
		if ($facility->id != '') {
			$users = $objUser->findUsersByFacility($facility->id);
		} else {
			$users = $objUser->findUsersByFacility(auth()->getRecord()->default_facility);
		}
						
		smarty()->assign('facilities', $facilities);
		smarty()->assign('facility', $facility);
		smarty()->assign('users', $users);
		
		
		
	}
	
	
	
	
	
	
	/*
	 * -------------------------------------------------------------
	 *  EDIT THE SELECTED USER
	 * -------------------------------------------------------------
	 * 
	 */
	
	public function edit() {
		if (auth()->getRecord()->isAdmissionsCoordinator() != 1) {
			redirect(auth()->getRecord()->homeURL());
		}
		//$user = new CMS_Site_User(input()->user);
		$facilityObj = CMS_Facility::generate();
		$facility = $facilityObj->findByFacilityPubid(input()->facility);

		$userObj = CMS_Site_User::generate();
		$user = $userObj->findUser(input()->user, $facility[0]->id);
				
		// Get user roles
		$obj = new CMS_Role();
		$roles = $obj->getRoles();
				
		smarty()->assign('user', $user[0]);
		smarty()->assign('facility', $facility[0]);
		smarty()->assign('roles', $roles);
		
		
	}
	
	public function submitEdit() {
		if (auth()->getRecord()->isAdmissionsCoordinator() != 1) {
			redirect(auth()->getRecord()->homeURL());
		}
		$editUser = new CMS_Site_User(input()->user);
		$facility = new CMS_Facility(input()->facility);
		$role = new CMS_Site_User_Role();
		
		$editUser->first = input()->first;
		$editUser->last = input()->last;
		$editUser->email = input()->email;
		$editUser->phone = input()->phone;
		$editUser->is_coordinator = input()->is_coordinator;
				
		if (input()->user_role != '') {
			$role->site_user = $editUser->id;
			$role->facility = $facility->id;
			$role->role = input()->user_role;
		} 
		
					
		try {
			$editUser->save();
			if (input()->user_role != '') {
				$role->save();
			} else {
				$role->deleteUserRole($editUser->id, $facililty->id);
			}
			feedback()->conf("The information for {$editUser->first} {$editUser->last} has been saved.");
		} catch (ORMException $e) {
			feedback()->error("Could not save the user information.");
		}
		
		if (feedback()->wasError()) {
			$this->redirect(SITE_URL . "/?page=siteUser&action=edit&facility={$facility->pubid}&user={$editUser->pubid}");
		} else {
			$this->redirect(SITE_URL . "/?page=siteUser&action=manage&facility={$facility->pubid}");
		}
		
	}
	
	
	
	
	
	/*
	 * -------------------------------------------------------------
	 *  RESET THE USER PASSWORD
	 * -------------------------------------------------------------
	 * 
	 */
	
	public function reset_password() {
		if (auth()->getRecord()->isAdmissionsCoordinator() != 1) {
			redirect(auth()->getRecord()->homeURL());
		}
		$user = new CMS_Site_User(input()->user);
		
		smarty()->assign('user', $user);
	}
	
	public function submitResetPassword() {
		if (auth()->getRecord()->isAdmissionsCoordinator() != 1) {
			redirect(auth()->getRecord()->homeURL());
		}
		$obj = new CMS_Site_User(input()->user);
						
		if (input()->password1 != '' && input()->password2 != '') {
			if (input()->password1 != input()->password2) {
				feedback()->error("Passwords do not match.");
			}
		}

		if (input()->password1 != '' && input()->password2 != '') {
			auth()->getRecord()->password = input()->password1;
			$obj->password = auth()->getRecord()->password;
		}
		
		try { 
			$obj->save();
		} catch (Exception $e) {
			feedback()->error("Unable to save record.");	
		}

		if (feedback()->wasError()) {
			$this->redirect(SITE_URL . "/?page=siteUser&action=manage");
		} else {
			$this->redirect(SITE_URL . "/?page=siteUser&action=manage");	
		}
	}
	
	
	
	
	
	/*
	 * -------------------------------------------------------------
	 *  DELETE THE USER
	 * -------------------------------------------------------------
	 * 
	 */
	 
	public function delete() {
		if (auth()->getRecord()->isAdmissionsCoordinator() != 1) {
			redirect(auth()->getRecord()->homeURL());
		}
		$user = new CMS_Site_User(input()->user);
		
		if ($user->deleteUser($user->id)) {
			feedback()->conf("{$user->first} {$user->last} was successfully deleted.");
			$this->redirect(SITE_URL . "/?page=siteUser&action=manage");
		} else {
			feedback()->error("Could not delete the user.");
			$this->redirect(SITE_URL . "/?page=siteUser&action=manage");
		}
	}
	
	
	
	/*
	 * -------------------------------------------------------------
	 *  ADD A NEW USER
	 * -------------------------------------------------------------
	 * 
	 */
	
	public function add() {
		if (auth()->getRecord()->isAdmissionsCoordinator() != 1) {
			redirect(auth()->getRecord()->homeURL());
		}
		$facilities = auth()->getRecord()->getFacilities();
		
		// Get user roles
		$obj = new CMS_Role();
		$roles = $obj->getRoles();
		
		smarty()->assign('facilities', $facilities);
		smarty()->assign('roles', $roles);
	}
	
	public function submitAddUser() {
		if (auth()->getRecord()->isAdmissionsCoordinator() != 1) {
			redirect(auth()->getRecord()->homeURL());
		}
		$user = new CMS_Site_User();
		$facility = new CMS_Facility(input()->facility);
		$role = new CMS_Site_User_Role();
				
		if (input()->first != '') {
			$user->first = input()->first;
		} else {
			feedback()->error("You must provide a first name.");	
		}
		
		if (input()->last != '') {
			$user->last = input()->last;
		} else {
			feedback()->error("You must provide a last name.");	
		}
		
		/*
		 * Now looks for the @ symbol in the email address.  If it does not exist the system will try
		 * to use SITE_EMAIL if defined in bootstrap.php.
		 *
		 */
		 
		// Get site email address from database
		$site_email = CMS_Company::getEmailExt();
		
		if (strpos(input()->email, "@")) {
			$user->email = input()->email;
		} elseif (input()->email != "" && $site_email != "") {
			$user->email = input()->email . $site_email;
		} else {
			feedback()->error("You must provide a username.");	
		}

		if (input()->password != '' && input()->confirm_password != '') {
			if (input()->password != input()->confirm_password) {
				feedback()->error("Passwords do not match.");
			}
		}

		if (input()->password != '' && input()->confirm_password != '') {
			auth()->getRecord()->password = input()->password;
			$user->password = auth()->getRecord()->password;
		}
		
		if (input()->phone != '') {
			$user->phone = input()->phone;
		}
		
		if (input()->is_coordinator != '') {
			$user->is_coordinator = input()->is_coordinator;
		}
		
		if (input()->facility != '') {
			$user->default_facility = $facility->id;
			$user->facility = array($facility);
		} else {
			feedback()->error("You must select a default facility.");	
		}
						
		if (! feedback()->wasError()) {			
			try {
				$user->save();
				feedback()->conf("The information for {$user->first} {$user->last} has been saved.");				
			} catch (ORMException $e) {
				feedback()->error("Could not save the user information.");
			}
		}
				
		if (feedback()->wasError()) {
			$this->redirect(SITE_URL . "/?page=siteUser&action=edit&facility={$facility->pubid}&user={$user->pubid}");
		} else {
			$this->redirect(SITE_URL . "/?page=siteUser&action=manage");
		}
	}
	
}