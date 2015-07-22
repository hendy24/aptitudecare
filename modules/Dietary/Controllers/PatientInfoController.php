<?php

class PatientInfoController extends MainPageController {

	public $module = "Dietary";
	protected $navigation = 'dietary';
	protected $searchBar = 'dietary';




	public function diet() {
		smarty()->assign('title', "Edit Diet");

		// fetch the patient info from the id in the url
		if (isset (input()->patient) && input()->patient != "") {
			$patient = $this->loadModel('Patient', input()->patient);
		} else {
			session()->setFlash("Could not find the selected patient, please try again", 'error');
			$this->redirect();
		}

		// get the diet info for the selected patient
		$patientInfo = $this->loadModel('PatientInfo')->fetchDietInfo($patient->id);

		// set arrays for checkboxes, dropdowns, etc.
		$dietOrder = array("None/Regular", "AHA/Cardiac", "No Added Salt", "Renal",
		"2 gram Na", "Fortified/High Calorie", "Other");
		$texture = array("Regular", "Mechanical Soft", "Puree", "Full Liquid",
			"Clear Liquid", "Tube Feeding", "Nectar Thick Liquids", "Honey Thick Liquids",
			"Pudding Thick Liquids", "Other");
		$portionSize = array("Small", "Medium", "Large");
		$orders = array("Isolation", "Fluid Restriction", "Clear Liquid", "Adaptive Equipment", "Other");

		smarty()->assignByRef('patient', $patient);
		smarty()->assignByRef('patientInfo', $patientInfo);
		smarty()->assign("dietOrder", $dietOrder);
		smarty()->assign("texture", $texture);
		smarty()->assign("portionSize", $portionSize);
		smarty()->assign("orders", $orders);	
	}


	public function saveDiet() {
		pr (input()); exit;
	}

	public function traycard() {

	}
}
