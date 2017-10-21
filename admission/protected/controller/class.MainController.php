<?php

class MainController extends MainControllerBase {



	public function init() {		// disabled for my local aptitude dev server
		// if (SECURE === FALSE) {
		// 	redirect(SITE_URL);
		// }
		
		smarty()->assign("isMicro", input()->isMicro);
		
		if (preg_match("/Large Screen/",$_SERVER['HTTP_USER_AGENT'])) {
			smarty()->assign("isTV", 1);	
		} else {
			smarty()->assign("isTV", 0);
		}
		
		if (input()->resOverride != "") {
			if (input()->resOverride == "TV") {
				smarty()->assign("isTV", 1);	
			} elseif (input()->resOverride == "desktop") {
				smarty()->assign("isTV", 0);	
			}
		}
		if (auth()->valid()) {
			$facilities = auth()->getRecord()->getFacilities();			// my facilities
		} else {
			$facilities = array();
		}		

		smarty()->assignByRef("myFacilities", $facilities);
		
		$today11am = mktime(11, 0, 0, date("m"), date("d"));
		$tomorrow11am = mktime(11, 0, 0, date("m", strtotime("+1 day")), date("d", strtotime("+1 day")));
		$today1pm = mktime(13, 0, 0, date("m"), date("d"));
		$tomorrow1pm = mktime(13, 0, 0, date("m", strtotime("+1 day")), date("d", strtotime("+1 day")));

		if ($today1pm > time()) {
			$datetimeAdmitDefault = $today1pm;
		} else {
			$datetimeAdmitDefault = $tomorrow1pm;
		}
		
		if ($today11am > time()) {
			$datetimeDischargeDefault = $today11am;
		} else {
			$datetimeDischargeDefault = $tomorrow11am;
		}
		smarty()->assign("datetimeAdmitDefault", $datetimeAdmitDefault);
		smarty()->assign("datetimeDischargeDefault", $datetimeDischargeDefault);
		
		$GLOBALS["datetimeAdmitDefault"] = $datetimeAdmitDefault;
		$GLOBALS["datetimeDischargeDefault"] = $datetimeDischargeDefault;
		
		
		/*
		 *  Look for custom logo
		 *
		 */
		 
		if (file_exists(APP_PUBLIC_PATH . '/images/logo.jpg')) {
			$logo = 'logo.jpg';
		} elseif (file_exists(APP_PUBLIC_PATH . '/images/logo.png')) {
			$logo = 'logo.png';
		} else {
			$logo = 'aptitudecare.png';
		}
		
		smarty()->assign('logo', $logo);
		
		/*
		 * Look for additional CSS files 
		 *
		 */
		 
		$css_dir = preg_grep('/^([^.])/', scandir(APP_PUBLIC_PATH . '/css/site_css'));
		$css = '';
		foreach ($css_dir as $k => $v) {
			if ($v != '.' || $v != '..') {
				$css = $v;
			}
		}
				
		smarty()->assign('siteCss', $css);	
		
		
		// leave this here
		parent::init();
	}

	public function prepare() {

		// it's probably a very good idea to leave this here.
		parent::prepare();
	}

	public function run() {

		// it's probably a very good idea to leave this here.
		parent::run();
	}


}
