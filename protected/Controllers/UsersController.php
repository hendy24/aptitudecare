<?php

class UsersController extends MainPageController {


	/*
	 * -------------------------------------------------------------------------
	 *  SAVE A NEW USER TO THE DATABASE
	 * -------------------------------------------------------------------------
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

	public function add() {
		//	We are only going to allow facility administrators and better to add data
		if (!auth()->has_permission(input()->action, 'site_users')) {
			$this->redirect();
		}

		smarty()->assign('title', "Add a new User");
		smarty()->assign('location_id', input()->location);

		if (isset (input()->location)) {
			$location = $this->loadModel('Location', input()->location);
		} else {
			//	Get the users default location
			$location = $this->loadModel('Location', auth()->getDefaultLocation());
		}

		//	Fetch locations to which the user can add new users
		$locations = $this->loadModel('User')->fetchUserLocations();
		smarty()->assign('available_locations', $this->loadModel('User')->fetchUserLocations());

		//	Fetch groups
		smarty()->assign('groups', $this->loadModel('Group')->fetchAll());


		//	Fetch clinician types
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
		smarty()->assign('current_location', input()->location);

		if (!empty ($user)) {
			smarty()->assign('group_id', $user->group_id);
			smarty()->assign('default_location', $user->default_location);
			smarty()->assign('public_id', $user->public_id);
			smarty()->assign('default_mod', $user->default_module);

			//	Fetch the users additional locations
			$additional_locations = $user->fetchUserLocations();
			//	Fetch locations for which the user is already assigned
			$assigned_locations = $user->fetchUserLocations($user->id);
			smarty()->assignByRef('additional_locations', $additional_locations);
			smarty()->assignByRef('assigned_locations', $assigned_locations);
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
			$user_location->deleteCurrentLocations($user->id);
			//	We will always save at least the default location
			$user_location->user_id = $user->id;
			$user_location->location_id = $user->default_location;
			$user_location->save();

			if (!empty (input()->user_location)) {
				foreach (input()->user_location as $loc) {
					$add_locations = $this->loadModel('UserLocation');
					$add_locations->user_id = $user->id;
					$add_locations->location_id = $loc; 	
					$add_locations->save();
				}
			} 

			if (isset ($userClinician)) {
				$userClinician->user_id = $user->id;
				$userClinician->save();
			}

			// Save the user to the admission dashboard
			if ($user->default_module == 1) {
				$siteUser = new AdmissionDashboardUser;
				$siteUser->pubid = $user->public_id;
				$siteUser->password = $user->password;
				$siteUser->email = $user->email;
				$siteUser->first = $user->first_name;
				$siteUser->last = $user->last_name;
				$siteUser->phone = $user->phone;

				if ($user->group_id == 2) {
					$siteUser->is_coordinator = 1;
				} else {
					$siteUser->is_coordinator = 0;
				}

				$siteUser->default_facility = $user->default_location;
				$siteUser->timeout = 1;
				$siteUser->save($siteUser, db()->dbname2);

				// Need to save additional locations for admissions
				if (!empty ($add_locations)) {
					foreach (input()->user_location as $loc) {
						$admit_locations = new AdmissionDashboardLocations;
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



	public function verify_user() {
		$user = $this->loadModel('User')->findByEmail(input()->term);
		if ($user->id != '') {
			json_return(true);
		}
		json_return (false);

	}


	public function fetchModulesByGroup() {
		$availableModules = $this->loadModel('Group')->fetchModules(input()->group);
		json_return($availableModules);
	}


	//	This is a function to be used once when the home health app goes live and user management is moved to the new framework
	
	// public function resetUserPasswords() {

	// 	$passwordArray = array();
	// 	$users = $this->loadModel('User')->fetchCustom("SELECT * FROM user");
		
	// 	foreach ($users as $u) {
	// 		$password = getRandomString();
	// 		$u->password = auth()->encrypt_password($password);
	// 		$u->temp_password = true;

	// 		$passwordArray[$u->default_location][] = array('first_name' => $u->first_name, 'last_name' => $u->last_name, 'email' => $u->email, 'password' => $password);
			
	// 		if ($u->save()) {
	// 			$mail = new PHPMailer;

	// 			$mail->isSMTP();
	// 			$mail->Host = "smtp.gmail.com";
	// 			$mail->SMTPDebug = 0;
	// 			$mail->SMTPAuth = true;
	// 			$mail->SMTPSecure = "ssl";
	// 			$mail->Port = 465;
	// 			$mail->Username = "kemish@aptitudeit.net";
	// 			$mail->Password = "TSoGlafib!2";

	// 			$mail->From = "no-reply@aptitudecare.com";
	// 			$mail->FromName = "AptitudeCare";
	// 			$mail->AddReplyTo ("helpdesk@aptitudecare.com", "AptitudeCare Help Desk");
	// 			$mail->AddAddress($u->email, $u->fullName());

	// 			$mail->WordWrap = 150;
	// 			$mail->Subject = "Admission Dashboard Password Reset";
	// 			$mail->Body = "Due to a recent update it was neccessary to reset all user passwords for the Admission Dashboard.  Your password has been reset to {$password}. You will be prompted to reset it the next time you login at http://ahc.aptitudecare.com.  If you have any question please send an email to helpdesk@aptitudecare.com";


	// 			if (!$mail->Send()) {
	// 				echo "Mailer Error: " . $mail->ErrorInfo . "<br>";
	// 			} else {
	// 				echo "Message Sent!<br>"; 
					
	// 			}
	// 		}
	// 	}

	// 	$userList = null;

	// 	foreach ($passwordArray as $location => $p) {
	// 		foreach ($users as $u) {
	// 			if ($u->default_location == $location && $u->group_id == 1) {

	// 				foreach ($p as $list) {
	// 					$userList .= "Name: " . $list["first_name"] . " " . $list["last_name"] . "\r\n" .
	// 						"Email (username): " . $list["email"] . "\r\n" .
	// 						"Password: " . $list["password"] . "\r\n\r\n";
	// 				}

	// 				$mail = new PHPMailer;

	// 				$mail->isSMTP();
	// 				$mail->Host = "smtp.gmail.com";
	// 				$mail->SMTPDebug = 0;
	// 				$mail->SMTPAuth = true;
	// 				$mail->SMTPSecure = "ssl";
	// 				$mail->Port = 465;
	// 				$mail->Username = "kemish@aptitudeit.net";
	// 				$mail->Password = "TSoGlafib!2";

	// 				$mail->From = "no-reply@aptitudecare.com";
	// 				$mail->FromName = "AptitudeCare";
	// 				$mail->AddReplyTo ("helpdesk@aptitudecare.com", "AptitudeCare Help Desk");
	// 				$mail->AddAddress($u->email, $u->fullName());

	// 				$mail->WordWrap = 150;
	// 				$mail->Subject = "Password Change List";
	// 				$mail->Body = "Following is a list of the password changes for all the users in your facilily.\r\n\r\n" . $userList;

	// 				if (!$mail->Send()) {
	// 					echo "Mailer Error: " . $mail->ErrorInfo . "<br>";
	// 				} else {
	// 					echo "Message Sent!<br>"; 
						
	// 				}
					
	// 			}
	// 		}
	// 	}

	// 	echo "Success"; die();

	// }



}