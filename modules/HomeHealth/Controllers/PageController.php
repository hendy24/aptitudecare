<?php

class PageController extends MainPageController {

	public function index() {
		$this->set('title', 'Home Health Dashboard');	
	}

	public function contact() {
		$this->set('title', 'Contact Us');
	}
}