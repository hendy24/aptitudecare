<?php

class PatientsController extends MainPageController {

	public function deleteId() {
		if (input()->id != "") {
			$patient = $this->loadModel("Patient", input()->id);
		} else {
			session()->setFlash("Could not delete the patient. Please try again", 'error');
			$this->redirect(input()->currentUrl);
		}
	
		// if the patient id is set, then delete the patient
		if ($patient->delete()) {
			return true;
		}

		return false;

	}


	
}