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
		$company = $this->loadModel('Company')->getEmailExt();

		smarty()->assign('site_email', $company->global_email_ext);
		smarty()->assign('title', 'Login');


		 // LOGIN FORM HAS BEEN SUBMITTED
		 //		 
		 		 
		if (input()->is('post')) {
			
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
					$this->redirect(array('module' => 'Admission', 'user' => $user->public_id));
				} else {
					$this->redirect(array('module' => $user->module_name));
				}
				
				
			} else { // send them back to the login page with an error
				session()->setFlash(array('Could not authenticate the user'), 'error');
				$this->redirect(input()->path);
			}
					
		} 
				

	}	


	public function admission_login() {
		//	Check db for username and public_id
		$user = $this->loadModel('User', input()->id);
		$verified = false;
		$_username = $user->email;
		//	Strip everything after @ from email address
		$string = explode('@', input()->username);
		// Check for a global company email extension
		$emailExt = $this->loadModel('Company')->getEmailExt();	

		if (!empty ($emailExt)) {
			$username = array(input()->username, $string[0] . $emailExt->global_email_ext);
			foreach ($username as $uname) {
				if ($uname == $_username) {
					if (auth()->login($user->email, $user->password)) {
						$user = auth()->getRecord();
						$this->redirect(array('module' => 'HomeHealth'));

					} else {
						$this->redirect(array('page' => 'login'));
					}
				}
			}
		} elseif ($_username = input()->username) {
			$this->redirect();
		} else {
			$this->redirect(array('page' => 'login'));
		}
		exit;
	}

	public function admission_logout() {
		auth()->logout();
		$this->redirect(array('page' => 'login', 'action' => 'index'));
	}
	
	public function logout() {
		auth()->logout();
		$this->redirect(array('page' => 'login', 'action' => 'index'));
	}
	

	public function timeout() {
		smarty()->assign('title', "Session Timeout");
		auth()->logout();
		
	}

	public function keepalive() {
		if (isset ($_SESSION['id'])) {
			$_SESSION['id'] = $_SESSION['id'];
		}	
	}
	
}