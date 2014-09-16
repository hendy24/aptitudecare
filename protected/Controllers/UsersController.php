<?php

class UsersController extends MainController {


	/*
	 * -------------------------------------------------------------------------
	 *  SAVE A NEW USER TO THE DATABASE
	 * -------------------------------------------------------------------------
	 */


	public function manage() {

	}

	public function add() {

		//	We are only going to allow facility administrators and better to add data
		if (!auth()->has_permission(input()->action, 'site_users')) {
			$this->redirect();
		}

		$model = depluralize(ucfirst(camelizeString(input()->page)));

		if (isset ($this->module)) {
			$module = $this->module;
		} else {
			$user = auth()->getRecord();
			$module = $user->default_module;
		}

		smarty()->assign('module', $module);
		smarty()->assign('title', "Add New {$model}");
		smarty()->assign('headerTitle', $model);

		$class = $this->loadModel($model);
		$columns = $class->fetchColumnNames();
		$data = $this->getColumnHeaders($columns, $class);
		if (in_array('password', $class->fetchColumnsToInclude())) {
			array_splice($data, 4, 0, 'verify_password');

			//	Get list of available modules
			$available_modules = $this->loadModel('Module')->fetchAllData();
			smarty()->assignByRef('available_modules', $available_modules);

			//	Get list of available locations to which the currently logged in user has access
			//	Fetch the users additional locations
			$user = $this->loadModel('User', auth()->getRecord()->id);
			$additional_locations = $user->fetchUserLocations();
			smarty()->assignByRef('additional_locations', $additional_locations);
			if ($user->is_site_admin) {
				smarty()->assignByRef('available_locations', $this->loadModel('Location')->fetchAll());
			} else {
				smarty()->assignByRef('available_locations', $this->loadModel('Location')->fetchHomeHealthLocations($additional_locations));
			}
			// $available_locations = $this->loadModel('Location')->fetchAllData();
			// smarty()->assignByRef('available_locations', $available_locations);
			smarty()->assignByRef('available_locations', $additional_locations);


			// 	Get list of available roles
			$groups = $this->loadModel('Group')->fetchAllData();
			smarty()->assignByRef('groups', $groups);
		}
		smarty()->assign('columns', $data);

		$clinicianTypes = $this->loadModel('Clinician')->fetchAll();
		smarty()->assign('clinicianTypes', $clinicianTypes);

	}


	public function edit() {
		if (input()->id != '') {
			$user = $this->loadModel('User', input()->id);
		} else {
			session()->setFlash("Could not find the selected user", "error");
			$this->redirect(input()->currentUrl);
		}

		smarty()->assign('title', 'Edit User');

		if (!empty ($user)) {
			smarty()->assign('group_id', $user->group_id);
			smarty()->assign('default_location', $user->default_location);
			smarty()->assign('public_id', $user->public_id);
			smarty()->assign('default_mod', $user->default_module);

			//	Fetch the users additional locations
			$additional_locations = $user->fetchUserLocations();
			smarty()->assignByRef('additional_locations', $additional_locations);
		}

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

		//	Get Groups
		smarty()->assignByRef('groups', $this->loadModel('Group')->fetchAll());

		$clinicianTypes = $this->loadModel('Clinician')->fetchAll();
		smarty()->assign('clinicianTypes', $clinicianTypes);

		smarty()->assignByRef('user', $user);
	}


	public function submitAdd() {
		if (!auth()->has_permission('add', 'site_users')) {
			$error_messages[] = "You do not have permission to add new users";
			session()->setFlash($error_messages, 'error');
			$this->redirect();
		}

		if (isset (input()->id)) {
			$id = input()->id;
		} else {
			$id = null;
		}

		$user = $this->loadModel('User', $id);

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
			// 	Set the default module based on the users group
			// 	Get the default module for the selected group
			$module = $this->loadModel('Group', input()->group)->fetchModule();
			$user->default_module = $module->module_id;
		} else {
			$error_messages[] = "Select a group for this user";
		}

		//	BREAKPOINT
		if (!empty ($error_messages)) {
			session()->setFlash($error_messages, 'error');
			$this->redirect(input()->path);
		}

		
		if (isset ($user->location)) {
			unset ($user->location);
		}

		if (isset ($user->module)) {
			unset ($user->module);
		}


		if (isset (input()->clinician)) {
			$userClinician = $this->loadModel('UserClinician');
			$userClinician->clinician_id = input()->clinician;
		}

		//	If we've made it this far then save the new user data
		$user_id = $user->save();
		if ($user_id != '') {
			if (!empty (input()->user_location)) {
				$user_location = $this->loadModel('UserLocation');

				if (isset ($user->id)) {
					$user_id = $user->id;
				}

				// 	Need to empty all records for this user from the location table 
				//	before saving them again.  This will allow us to de-select
				//	locations that have been selected previously.
				$this->loadModel('UserLocation')->deleteCurrentLocations($user->id);
				foreach (input()->user_location as $loc) {
					$user_location->user_id = $user_id;
					$user_location->location_id = $loc; 
					$user_location->save();
				}
			}

			if (isset ($userClinician)) {
				$userClinician->user_id = $user_id;
				$userClinician->save();
			}

			session()->setFlash("Successfully added/edited {$user->first_name} {$user->last_name}", 'success');
			$this->redirect(array('page' => 'data', 'action' => 'manage', 'type' => 'users'));

		} else {
			session()->setFlash("Could not save the user.  Please try again.", 'error');
			$this->redirect(input()->path);
		}


	}



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
				$user->temp_password = true;
			} else {
				$user->temp_password = false;
			}
			

			if ($user->save()) {
				session()->setFlash("The password has been changed for {$user->fullName()}", 'success');
				if (isset (input()->reset)) {
					$this->redirect();
				} else {
					$this->redirect(array('page' => 'data', 'action' => 'manage', 'type' => 'users'));
				}
				
			}

		}
	}



	public function verify_user() {
		$user = $this->loadModel('User')->findByEmail(input()->term);
		if ($user->id != '') {
			json_return(true);
		}
		json_return (false);

	}


}