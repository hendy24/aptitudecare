<?php

class UsersController extends MainController {


	/*
	 * -------------------------------------------------------------------------
	 *  SAVE A NEW USER TO THE DATABASE
	 * -------------------------------------------------------------------------
	 */


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

			//	Get list of available locations
			$available_locations = $this->loadModel('Location')->fetchAllData();
			smarty()->assignByRef('available_locations', $available_locations);


			// 	Get list of available roles
			$groups = $this->loadModel('Group')->fetchAllData();
			smarty()->assignByRef('groups', $groups);
		}
		smarty()->assign('columns', $data);


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

		if (input()->password != '') {
			if (isset (input()->verify_password)) {
				if (input()->password == input()->verify_password) {
					$user->password = auth()->encrypt_password(input()->password);
				} else {
					$error_messages[] = "The passwords do not match";
				}
			}			
		} else {
			$error_messages[] = "Enter a password";
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

		//	If we've made it this far then save the new user data
		$user_id = $user->save();
		if ($user->save()) {
			if (!empty (input()->user_location)) {
				$user_location = $this->loadModel('UserLocation');

				if (isset ($user->id)) {
					$user_id = $user->id;
				}

				foreach (input()->user_location as $loc) {
					$user_location->user_id = $user_id;
					$user_location->location_id = $loc; 
					$user_location->save();
				}
			}
			session()->setFlash("Successfully added/edited {$user->first_name} {$user->last_name}", 'success');
			$this->redirect(array('page' => 'data', 'action' => 'manage', 'type' => 'users'));

		} else {
			session()->setFlash("Could not save the user.  Please try again.", 'error');
			$this->redirect(input()->path);
		}


	}



	public function getAdditionalData($data = false) {
		
		if ($data) {
			smarty()->assign('group_id', $data->group_id);
			smarty()->assign('default_location', $data->default_location);
			smarty()->assign('public_id', $data->public_id);

			//	Fetch the users additional locations
			smarty()->assignByRef('additional_locations', $data->fetchUserLocations());
		}

		//	Get Locations
		smarty()->assignByRef('available_locations', $this->loadModel('Location')->fetchAll());

		//	Get Groups
		smarty()->assignByRef('groups', $this->loadModel('Group')->fetchAll());
	}





}