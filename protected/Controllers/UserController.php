<?php

class UserController extends MainController {


	/*
	 * LOGIN PAGE
	 *
	 */

	public function login() {
		$this->set('title', 'Login');
		
		// Check for a global company email extension
		$c = new Company;
		$company = $c->fetchCompany();

		// Get site_email from db if it exists
		smarty()->assign('site_email', $company->global_email_ext);




		 // LOGIN FORM HAS BEEN SUBMITTED
		 //
		 
		if (isset (input()->submit)) {
			
			// Verify that the email field is not blank
			if (input()->email != '') {
			
				// Check the email for the '@' symbol
				if (strstr(input()->email, '@')) {
					$username = input()->email;
				} elseif ($company->global_email_ext) {  // Check the db for the global company email extension, if it exists add it here
					$username = input()->email . $company->global_email_ext;
				} else { // if there is no '@' symbol and no global email extension use aptitudecare.com
					$username = input()->email . '@aptitudecare.com';
				}
			} else {
				$this->Session->setMessage('Enter your username');
				$this->redirect();
			}
			
			if (input()->password != '') {
				$password = input()->password;
			} else {
				$this->Session->setMessage('Enter your password');
				$this->redirect();
			}
			
			
			// If the username and password are correctly entered, validate the user
			$user = new User;
			$validated_user = $user->validateUser($username, $password);
			
			
			if ($validated_user) { // Get the default home page for the user and re-direct there
				// start a new session
				$session_vals = array(
					'username' => $validated_user->email,
					'fullname' => $validated_user->first_name . ' ' . $validated_user->last_name,
					'default_module' => $validated_user->name,
					'datetime_login' => date('Y-m-d H:i:s', strtotime('now'))
				);
				$this->Session->setVals($session_vals);
				
				// redirect to home page
				
			} else { // send them back to the login page with an error
				$this->error('Could username and/or password could not be verified.  Please try again.');
				$this->redirect();
			}
					
		}
		

	}
}