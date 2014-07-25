<?php

class MainPageController extends MainController {
	
		
	public function index() {
		
		// Check if user is logged in, if not redirect to login page
		$this->redirect(array('page' => 'User', 'action' => 'login'));
	}
	
}