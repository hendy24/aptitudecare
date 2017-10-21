<?php

class PageControllerHome extends PageController {

	public function init() {
		Authentication::disallow();
	}
	
		
	public function index() {
		$this->redirect(auth()->getRecord()->homeURL());
		
	}
	
	/*
	public function setHospitalNames() {
		// inquiry records
		$pObj = CMS_Patient_Admit::generate();
		$patients = $pObj->fetch();
		foreach ($patients as $p) {
			if ($p->hospital != '' && $p->referral_org_name == '') {
				$hospital = new CMS_Hospital($p->hospital);
				$p->referral_org_name = $hospital->name;
				$p->save();
			}
		}
		
		// AHRs
		$aObj = CMS_Schedule_Hospital::generate();
		$ahrs = $aObj->fetch();
		foreach ($ahrs as $ahr) {
			if ($ahr->hospital != '') {
				$hospital = new CMS_Hospital($ahr->hospital);
				$ahr->hospital_name = $hospital->name;
				$ahr->save();
			}			
		}
		
		exit;
	}
	*/
	
	public function notifyRoleUsers() {
		// glendale
		$facility = new CMS_Facility(1);
		
		// event: schedule changed
		$event = new CMS_Notify_Event(3);
		
		// roles
		$roles = $event->getRolesToNotify();
		
		// users
		foreach ($roles as $role) {
			echo "For role {$role->name}:<br />";
			$users = $role->getUsers($facility);
			vd($users);
		}
		
		exit;
		
	}

/*
	public function adc() {

		//start date
		$start_date = '2011-06-01';
		$facility = 3;
		$first_day = date('01', strtotime($start_date));
		$last_day = date('t', strtotime($start_date));
		$censusTotal = 0;

		// Need to date start date and cycle though each day through the current date
		 while (strtotime($start_date) <= strtotime('now')) {
			$dailyCensus[$start_date] = CMS_Schedule::getADC($facility, $start_date);
						
			if ($first_day == $last_day) {
				//$start_date = date('Y-m-01', strtotime($start_date . " + 1 month"));
								
				foreach ($dailyCensus as $census) {
					foreach ($census as $c) {
						$censusTotal += $c->census;
					}
				}
				
				$adc[] = array('facility' => $facility, 'time_period' => date('Y-m-t', strtotime($start_date)), 'census_value' => round ($censusTotal / $last_day, 2));
				$first_day = 0;
				$dailyCensus = array();	
			} 
			

			$first_day++;
			$start_date = date('Y-m-d', strtotime($start_date . " + 1 day"));
			$censusTotal = 0;
			$last_day = date('t', strtotime($start_date));
			

		}
		
		$obj = new CMS_Census_Data();

		foreach ($adc as $k) {
			$obj->facility_id = $k['facility'];
			$obj->time_period = $k['time_period'];
			$obj->census_value = $k['census_value'];
			
			$obj->save();
		}

		
		die();


	}
*/


}
