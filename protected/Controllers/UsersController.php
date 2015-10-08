<?php

/**
 * UsersController class
 *
 * @package AptitudeFramework v1.0
 * @author Kemish W. Hendershot (kwh)
 **/

class UsersController extends MainPageController {

	/* 
	 * Manage all users 
	 *	
	 */
	public function manage() {
		smarty()->assign('title', "Manage Users");
		// Fetch all users for the location
		if (isset (input()->location)) {
			$loc_id = input()->location;
		} else {
			//	Fetch the users default location
			$user = auth()->getRecord();
			$loc_id = $user->default_location;
		}
		$location = $this->loadModel('Location', $loc_id);
		smarty()->assign('location_id', $location->public_id);

		input()->type = "users";
		$users = $this->loadModel('User')->fetchManageData($location);
		smarty()->assignByRef('users', $users);

	}



	/* 
	 * Add a new user page 
	 *	
	 */
	// public function add() {
	// 	//	We are only going to allow facility administrators and better to add data
	// 	if (!auth()->hasPermission("add_user")) {
	// 		$this->redirect();
	// 	}

	// 	smarty()->assign('title', "Add a new User");
	// 	smarty()->assign('location_id', input()->location);

	// 	if (isset (input()->location)) {
	// 		$location = $this->loadModel('Location', input()->location);
	// 	} else {
	// 		//	Get the users default location
	// 		$location = $this->loadModel('Location', auth()->getDefaultLocation());
	// 	}

	// 	//	Fetch locations to which the user can add new users
	// 	$locations = $this->loadModel('User')->fetchUserLocations();
	// 	smarty()->assign('available_locations', $this->loadModel('User')->fetchUserLocations());

	// 	//	Fetch groups to which the user has permission to add users
	// 	smarty()->assign('groups', $this->loadModel('Group')->fetchAll());

	// 	// Get available modules
	// 	smarty()->assignByRef('available_modules', $this->loadModel('Module')->fetchAll());

	// 	//	Fetch clinician types
	// 	$clinicianTypes = $this->loadModel('Clinician')->fetchAll();
	// 	smarty()->assign('clinicianTypes', $clinicianTypes);

	// }





	/* 
	 * Edit page for an existing user  
	 *	
	 */
	public function user() {

		if (input()->type == "add") {
			// this is a new user being added
			smarty()->assign('title', "Add a new User");
			smarty()->assign('page_header', "Add a New User");
			smarty()->assign('existing', false);

			if (!auth()->hasPermission("add_user")) {
				session()->setFlash("You do not have permission to add new users.", 'error');
				$this->redirect();
			}
			$user = $this->loadModel("User");
		} elseif (input()->type == "edit") {
			// we are editing an already existing user
			smarty()->assign('title', 'Edit User');
			smarty()->assign('page_header', "Edit User");
			smarty()->assign('existing', true);

			if (input()->id != '') {
				$user = $this->loadModel('User', input()->id);
			} else {
				session()->setFlash("Could not find the selected user", "error");
				$this->redirect(input()->currentUrl);
			}
		}

		if (input()->location != "") {
			smarty()->assign('current_location', input()->location);
		} else {
			smarty()->assign('current_location', "");
		}
		

		smarty()->assign('group_id', $user->group_id);
		smarty()->assign('default_location', $user->default_location);
		smarty()->assign('public_id', $user->public_id);
		smarty()->assign('default_mod', $user->default_module);

		//	Fetch the users additional locations
		$additional_locations = $user->fetchUserLocations();
		//	Fetch locations for which the user is already assigned
		$assigned_locations = $user->fetchUserAssignedLocations($user->id);
		smarty()->assignByRef('additional_locations', $additional_locations);
		smarty()->assignByRef('assigned_locations', $assigned_locations);


		//	Get Locations
		//	Get the logged in user info to pull locations
		$currentUser = auth()->getRecord();
		//	Fetch only home health locations until we are ready to release the admissions update
		if ($currentUser->is_site_admin) {
			$location_options = $this->loadModel('Location')->fetchAll();
			
		} else {
			$location_options = $this->loadModel('Location')->fetchHomeHealthLocations($additional_locations);
		}

		smarty()->assignByRef('available_locations', $location_options);
		

		// Get available modules
		smarty()->assignByRef('available_modules', $this->loadModel('Module')->fetchAll());

		// fetch the modules to which the user has access
		smarty()->assignByRef('assigned_modules', $this->loadModel('UserModule')->fetchAssignedModules($user->id));


		//	Get Groups
		smarty()->assignByRef('groups', $this->loadModel('Group')->fetchAll());

		// fetch the user assigned groups
		smarty()->assign('user_groups', $this->loadModel('UserGroup')->fetchAssignedGroups($user->id));

		$clinicianTypes = $this->loadModel('Clinician')->fetchAll();
		smarty()->assign('clinicianTypes', $clinicianTypes);

		smarty()->assignByRef('user', $user);
	}





	/* 
	 * Submit the info for the new user 
	 *	
	 */
	public function save_user() {

		if (!auth()->hasPermission("add_user")) {
			session()->setFlash("You do not have permission to add new users", 'error');
			$this->redirect();
		}

		if (isset (input()->id)) {
			$user = $this->loadModel('User', input()->id);
		} else {
			$user = $this->loadModel('User');
		}


		//	Validate form fields
		if (input()->first_name != '') {
			$user->first_name = input()->first_name;
		} else {
			$error_messages[] = "Enter the users first name";
		}

		if (input()->last_name != '') {
			$user->last_name = input()->last_name;
		} else {
			$error_messages[] = "Enter the users last name";
		}

		if (input()->email != '') {
			$user->email = input()->email;
		} else {
			$error_messages[] = "Enter the users email address";
		}

		if (isset (input()->password)) {
			if (isset (input()->verify_password)) {
				if (input()->password == input()->verify_password) {
					$user->password = auth()->encrypt_password(input()->password);
				} else {
					$error_messages[] = "The passwords do not match";
				}
			} elseif (input()->password == '') {
				$error_messages[] = "Enter a password";
			}		
		} 

		if (isset (input()->temp_password)) {
			$user->temp_password = true;
		} else {
			$user->temp_password = false;
		}

		if (input()->phone != '') {
			$user->phone = input()->phone;
		}

		if (input()->default_location != '') {
			$user->default_location = input()->default_location;
		}


		if (input()->group != '') {
			$user->group_id = input()->group;
		} else {
			$error_messages[] = "Select a group for this user";
		}

		if (input()->default_module != '') {
			$user->default_module = input()->default_module;
		}



		//	BREAKPOINT
		if (!empty ($error_messages)) {
			session()->setFlash($error_messages, 'error');
			$this->redirect(input()->path);
		}


		if (isset (input()->clinician) && input()->clinician != '') {
			$userClinician = $this->loadModel('UserClinician');
			$userClinician->clinician_id = input()->clinician;
		}
		$success = false;
		
		//	If we've made it this far then save the new user data
		//	If user id is not empty then we are editing an existing user
		if ($user->save()) {
			$user_location = $this->loadModel('UserLocation');

			// 	Need to empty all records for this user from the location table 
			// 	before saving them again.  This will allow us to de-select
			// 	locations that have been selected previously.
			$user_location->deleteCurrent($user->id);
			//	We will always save at least the default location
			$user_location->user_id = $user->id;
			$user_location->location_id = $user->default_location;
			$user_location->save();


			// Save the users additional locations
			foreach (input()->additional_locations as $loc) {
				$add_locations = $this->loadModel('UserLocation');
				$add_locations->user_id = $user->id;
				$add_locations->location_id = $loc; 	
				$add_locations->save();
			}

			// If the user is a clinician, save it
			if (isset ($userClinician)) {
				$userClinician->user_id = $user->id;
				$userClinician->save();
			}


			// Delete all groups for the curent user so we can reset and save them again
			$groups = $this->loadModel('UserGroup')->deleteCurrent($user->id);
			// Save the users additional groups
			foreach (input()->additional_groups as $group) {
				if ($group == 1) {
					$admission_access = true;
				}
				$add_groups = $this->loadModel('UserGroup');
				$add_groups->user_id = $user->id;
				$add_groups->group_id = $group;
				$add_groups->save();
			}


			$modules = $this->loadModel('UserModule')->deleteCurrent($user->id);
			// Save the users additional modules
			$i = 0;
			foreach (input()->additional_modules as $mod) {
				if ($mod == 1) {
					$admission_access = true;
				}
				$add_modules = $this->loadModel('UserModule');
				$add_modules->user_id = $user->id;
				$add_modules->module_id = $mod;
				$add_modules->save();
				$i++;
			}
			

			// Save the user to the admission dashboard
			if ($user->default_module == 1 || $admission_access) {
				$obj = new AdmissionDashboardUser;

				// need to check for existing user in admission db
				$siteUser = $obj->checkForExisting($user->public_id);
				$siteUser->pubid = $user->public_id;
				$siteUser->password = $user->password;
				$siteUser->email = $user->email;
				$siteUser->first = $user->first_name;
				$siteUser->last = $user->last_name;
				if ($user->phone != "") {
					$siteUser->phone = $user->phone;
				} else {
					$siteUser->phone = "";
				}
				

				if ($user->group_id == 2) {
					$siteUser->is_coordinator = 1;
				} else {
					$siteUser->is_coordinator = 0;
				}

				if ($i > 1) {
					$siteUser->module_access = 1;
				} else {
					$siteUser->module_access = 0;
				}

				$siteUser->default_facility = $user->default_location;
				$siteUser->timeout = 1;
				
				$siteUser->save($siteUser, db()->dbname2);

				// Need to save additional locations for admissions
				$admitLocation = new AdmissionDashboardLocation;
				$admitLocation->site_user = $siteUser->id;
				$admitLocation->facility = $user->default_location;
				$admitLocation->save($admitLocation, db()->dbname2);


				if (!empty (input()->additional_locations)) {
					foreach (input()->additional_locations as $loc) {
						$admit_locations = new AdmissionDashboardLocation;
						$admit_locations->site_user = $siteUser->id;
						$admit_locations->facility = $loc;	
						$admit_locations->save($admit_locations, db()->dbname2);
					}
					
				} 
			}

			session()->setFlash("Successfully added/edited {$user->first_name} {$user->last_name}", 'success');

			if (isset ($userClinician)) {
				$this->redirect(array('module' => 'HomeHealth', 'page' => 'clinicians', 'action' => 'manage', 'location' => input()->location_public_id));
			} else {
				$this->redirect(array('page' => 'users', 'action' => 'manage', 'location' => input()->location_public_id));
			}
			

		} else {
			session()->setFlash("Could not save the user.  Please try again.", 'error');
			$this->redirect(input()->path);
		}


	}



	public function delete_user() {

		//	If the id var is filled then delete the item with that id
		if (input()->id != '') {
			$user = $this->loadModel('User', input()->id);

			if ($user->delete()) {	
				if ($siteUser = $this->loadModel('AdmissionDashboardUser')->deleteSiteUser(input()->id)) {
					return true;
				}
				return false;
			}

			return false;
		}

		return false;
	}




	/* 
	 * Reset a user's password 
	 *	
	 */
	public function reset_password() {
		smarty()->assign('title', 'Reset Password');
		
		if (input()->id != '') {
			$user_id = input()->id;
		} 

		// Get User
		$user = $this->loadModel('User', $user_id);
		smarty()->assignByRef('user', $user);


		if (input()->is('post')) {
			if (input()->password != '') {
				if (input()->password == input()->password2) {
					$user->password = auth()->encrypt_password(input()->password);
				} else {
					$error_messages[] = "The passwords do not match.";
				}
			} else {
				$error_messages[] = "Please enter the new password.";
			}


			if (!empty ($error_messages)) {
				session()->setFlash($error_messages, 'error');
				$this->redirect(input()->path);
			}

			if (isset (input()->temp_password)) {
				$user->temp_password = 1;
			} else {
				$user->temp_password = 0;
			}

			if ($user->save()) {
				session()->setFlash("The password has been changed for {$user->fullName()}", 'success');
				if (isset (input()->reset)) {
					if ($user->default_module == 1) {
						$this->redirect(array('module' => "Admission", 'user' => $user->public_id));
					} else {
						$this->redirect(array('module' => "HomeHealth"));
					}
				} else {
					$this->redirect(array('page' => 'users', 'action' => 'manage'));
				}
				
			}

		}
	}





	/* 
	 * Check if a user exists in the db 
	 *	
	 */
	public function verify_user() {
		$user = $this->loadModel('User')->findByEmail(input()->term);
		if ($user->id != '') {
			json_return(true);
		}
		json_return (false);

	}




	/* 
	 * Get modules available for the group 
	 *	
	 */
	public function fetchModulesByGroup() {
		$availableModules = $this->loadModel('Group')->fetchModules(input()->group);
		json_return($availableModules);
	}



} // END CLASS