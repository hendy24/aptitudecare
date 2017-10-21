<?php

class PageControllerLogin extends PageController {

	public function index() {

		//	Moved the login to the new framework
		$redirect_url = preg_replace('/\W\w+\s*(\W*)$/', '$1', SITE_URL);
		$this->redirect($redirect_url);
		exit;

	
		// Get site email address from database
		$site_email = CMS_Company::getEmailExt();
		if ($site_email != '') {
			smarty()->assign('site_email', $site_email);
		} else {
			smarty()->assign('site_email', false);
		}
		smarty()->assign("path", input()->path);
	}

	public function login() {

		//	Get site email address from database
		$site_email = CMS_Company::getEmailExt();
		
		//Look for @ symbol in username
		if (strpos(input()->post("email"), "@")) {
			auth()->login(input()->post("email"), input()->post("password"));
		} elseif (SITE_EMAIL != "") { // if no @ symbol use the global email address
			$username = input()->post("email") . $site_email;
			auth()->login($username, input()->post("password"));	
		} else {
			$username = input()->post("email") . "@aptitudeit.net";
			auth()->login($username, input()->post("password"));	
		}
					
		if (auth()->valid()) {
			if (input()->path == '') {
				$this->redirect(auth()->getRecord()->homeURL());
			} else {
				$this->redirect(urldecode(input()->path));
			}
		} else {
			$redirect_url = preg_replace('/\W\w+\s*(\W*)$/', '$1', SITE_URL);
			feedback()->error("You provided an invalid username or password.");
			if (input()->path == '') {
				$this->redirect($redirect_url . "/?page=login");
				// $this->redirect(SITE_URL . "/?page=login");
			} else {
				$this->redirect($redirect_url . "/?page=login&path=" . input()->path);
			}
		}
	}

	public function logout() {
		auth()->logout();
		//$this->redirect(SITE_URL . "/?page=home");
		//$this->redirect($redirect_url);
		$redirect_url = preg_replace('/\W\w+\s*(\W*)$/', '$1', SITE_URL);
		$this->redirect($redirect_url . "?page=login&action=admission_logout");

	}

	public function single_sign_on() {
		$user = $_SESSION["AptitudeCare"]["authentication_record"];
		if ($user != "") {
			$user = new CMS_Site_User($user);
		} 



		if (!empty ($user)) {
			auth()->login($user->email, $user->password, true);
		}
		if (auth()->valid()) {
			if (input()->path == '') {
				$this->redirect(auth()->getRecord()->homeURL());
			} else {
				echo "goodbye"; die();
				$this->redirect(urldecode(input()->path));
			}
		} else {
			$redirect_url = preg_replace('/\W\w+\s*(\W*)$/', '$1', SITE_URL);
			$this->redirect($redirect_url . "?page=login&action=logout");
		}


	}

	
	public function timeout() {
		auth()->logout();
		$redirect_url = preg_replace('/\W\w+\s*(\W*)$/', '$1', SITE_URL);
		$this->redirect($redirect_url . "/?page=login&action=timeout");
	}
	
	public function keepalive() {
		if (isset ($_SESSION['id'])) {
			$_SESSION['id'] = $_SESSION['id'];
		}	
	}


}