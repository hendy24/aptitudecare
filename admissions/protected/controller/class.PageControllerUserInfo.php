<?php

class PageControllerUserInfo extends PageController {
	
	public function init() {
		Authentication::disallow();
		parent::init();
	}

	public function index() {
		
		if (auth()->valid()) {
			$facilities = auth()->getRecord()->getFacilities();
			$facilities_default = auth()->getRecord()->getDefaultFacility();
		} else {
			$facilities = array();
		}		

		smarty()->assignByRef("facilities", $facilities);
		smarty()->assign("defaultFacility", $facilities_default);
		// leave this here
		parent::init();
		
	}

	public function submitUserInfo() {
		$obj = auth()->getRecord();
		if (input()->first != '') {
			$obj->first = input()->first;
		} else {
			feedback()->error("You must provide a first name.");	
		}
		if (input()->last != '') {
			$obj->last = input()->last;	
		} else {
			feedback()->error("You must provide a last name.");
		}
		if (input()->phone != '') {
			$obj->phone = input()->phone;	
		} else {
			feedback()->error("You must provide a phone number.");
		}
		
		if (input()->password1 != '' && input()->password2 != '') {
			if (input()->password1 != input()->password2) {
				feedback()->error("Passwords do not match.");
			}
		}

		if (input()->password1 != '' && input()->password2 != '') {
			auth()->getRecord()->password = input()->password1;
		}
			
		if (input()->facility != '') {
			$facility = new CMS_Facility(input()->facility);
			if (! $facility->valid() ) {
				feedback()->error("Invalid facility specified.");	
			} else {
				$obj->default_facility = $facility->id;	
			}
		}
		
		if (! feedback()->wasError() ) {
			try { 
				$obj->save();
			} catch (Exception $e) {
				feedback()->error("Unable to save record.");	
			}
		}

		if (feedback()->wasError()) {
			$this->redirect(SITE_URL . "/?page=userInfo");
		} else {
			$this->redirect(auth()->getRecord()->homeURL());	
		}
	}
			
		
}








