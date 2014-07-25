<?php

class AdmissionController extends MainPageController {
	
	public function new_admit() {
		smarty()->assign('title', 'New Admission');
		smarty()->assign('headerTitle', 'New Admission Request');
	}
}