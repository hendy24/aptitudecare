<?php

class LoginController extends MainPageController {

/*
 * -------------------------------------------
 * LOGIN PAGE
 * -------------------------------------------
 *
 */
	public function index() {	
		
		if (auth()->isLoggedIn()) {
			$this->redirect(array('module' => auth()->getRecord()->module_name));
		}
		// Check session for errors to be displayed
		session()->checkFlashMessages();
				
		// Check for a global company email extension
		$emailExt = Company::getEmailExt();	
		
		smarty()->assign('site_email', $emailExt->global_email_ext);
		smarty()->assign('title', 'Login');


		 // LOGIN FORM HAS BEEN SUBMITTED
		 //		 
		 		 
		if (input()->is('post')) {
			
			// Verify that the email field is not blank
			if (input()->email != '') {
			
				// Check the email for the '@' symbol
				if (strstr(input()->email, '@')) {
					$username = input()->email;
				} elseif ($emailExt) {  // Check the db for the global company email extension, if it exists add it here
					$username = input()->email . $emailExt->global_email_ext;
				} else { // if there is no '@' symbol and no global email extension use aptitudecare.com
					$username = input()->email . '@aptitudecare.com';
				}
			} else {
				$error_messages[] = 'Enter your username';
			}

			
			if (input()->password != '') {
				$password = input()->password;
			} else {
				$error_messages[] = 'Enter your password';
			}
			
			
			// If error messages, then set messages and redirect back to login page
			if (!empty($error_messages)) {
				session()->setFlash($error_messages, 'error');
				$this->redirect(input()->path);
			}			
			
			// If the username and password are correctly entered, validate the user
			if (auth()->login($username, $password)) {
				// redirect to users' default home page
				//$this->redirect(array('module' => session()->getSessionRecord('default_module')));
				$user = auth()->getRecord();
				if ($user->temp_password) {
					$this->redirect(array('page' => 'users', 'action' => 'reset_password', 'id' => $user->public_id));
				} elseif ($user->module_name == "Admission") {
					$this->redirect(array('module' => $user->module_name, 'page' => 'login', 'action' => 'index', 'user' => $user->public_id));
				} else {
					$this->redirect(array('module' => $user->module_name));
				}
				
				
			} else { // send them back to the login page with an error
				session()->setFlash(array('Could not authenticate the user'), 'error');
				$this->redirect(input()->path);
			}
					
		} 
				

	}	
	
	public function logout() {
		auth()->logout();
		$this->redirect(array('page' => 'login', 'action' => 'index'));
	}
	
	
}