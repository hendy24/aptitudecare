<?php

class ReportsController extends DietaryController {

	public $module = "Dietary";
	protected $navigation = "dietary";
	protected $searchBar = "dietary";
	protected $helper = "DietaryMenu";

	public function menu_changes() {
		smarty()->assign('title', "Menu Changes");
		// set the time frame options for the drop down menu
		$this->reportDays();

		$url = SITE_URL . "/?module={$this->module}&page=reports&action=menu_change_details";

		if (isset (input()->days)) {
			$days = input()->days;
			$start_date = false;
			$end_date = false;
			$url .= "&days={$days}";
		} elseif (isset (input()->start_date) && isset (input()->end_date)) {
			$start_date = date("Y-m-d", strtotime(input()->start_date));
			$end_date = date("Y-m-d", strtotime(input()->end_date));
			$days = false;
			$url .= "&start_date={$start_date}&end_date={$end_date}";
		} elseif (isset (input()->start_date) && !isset (input()->end_date)) {
			$start_date = date("Y-m-d", strtotime(input()->start_date));
			$end_date = date("Y-m-d", strtotime("now"));
			$days = false;
			$url .= "&start_date={$start_date}&end_date={$end_date}";
		} elseif (!isset (input()->start_date) && isset (input()->end_date)) {
			$start_date = date("Y-m-d", strtotime(input()->end_date . " - 30 days"));
			$end_date = date("Y-m-d", strtotime(input()->end_date));
			$days = false;
			$url .= "&start_date={$start_date}&end_date={$end_date}";
		} else {
			$days = 30;
			$start_date = false;
			$end_date = false;
			$url .= "&days={$days}";
		}

		$menuChanges = $this->loadModel("MenuMod")->countMenuMods($days, $start_date, $end_date);
		smarty()->assignByRef("menuChanges", $menuChanges);
		smarty()->assign("url", $url);
		smarty()->assign("numDays", $days);

	}

	public function menu_change_details() {
		smarty()->assign("title", "Menu Change Details");
		$this->reportDays();

		$location = $this->getSelectedLocation();


		if (isset (input()->days)) {
			$days = input()->days;
			$start_date = false;
			$end_date = false;
		} else {
			$days = 30;
			$start_date = false;
			$end_date = false;
		}

		if (isset (input()->page)) {
			$page = input()->page;
		} else {
			$page = false;
		}

		$results = $this->loadModel("MenuMod")->paginateMenuMods($location->id, $days, $page);
		foreach ($results as $key => $item) {
			$results[$key]->mod_content = explode("\n", $item->mod_content);
			$results[$key]->content = explode("\n", $item->content);
		}

		smarty()->assignByRef('menuItems', $results);
		smarty()->assign("numDays", $days);
	}

	public function beverages() {
		smarty()->assign('title', "Beverages Report");
		$location = $this->getLocation();


		smarty()->assign('location', $location);

	}

	public function beverages_pdf() {

		$location = $this->getLocation();
		$title = "Beverage report for " . $location->name . " on " . input()->date;
		$date = date('Y-m-d', strtotime(input()->date));
		$beverages = $this->loadModel("PatientBeverage")->fetchBeverageReport($location, $date);
		if(!is_array($beverages)){
			session()->setFlash("No beverages found for this time", "error");
			$this->redirect();
		}

		$html = <<<EOD
<table>
	<thead>
		<tr>
			<th><strong>Beverage</strong></th>
			<th><strong>Count</strong></th>
		</tr>
	</thead>
	<tbody>
EOD;

foreach($beverages as $beverage){
	$html = $html . <<<EOD
		<tr>
			<td>{$beverage->name}</td>
			<td>{$beverage->quantity}</td>
		</tr>
EOD;
}

$html = $html . <<<EOD
		</tbody>
	</table>

EOD;


		$pdfDetails = array("title" => $title, "html" => $html, "header" => true, "footer" => false, "orientation" => "Portrait");

		$this->buildPDFOptions($pdfDetails);

	}

	public function allergies_pdf() {
		$location = $this->getLocation();

		$title = 'Allergy Report for ' . $location->name;

		$currentPatients = $this->loadModel("PatientInfo")->fetchByLocation_allergy($location);

$html = <<<EOD
<table>
	<thead>
		<tr>
			<td><strong>Room</strong></td>
			<td><strong>Patient</strong></td>
			<td><strong>Allergy</strong></td>
		</tr>
	</thead>
	<tbody>
EOD;

foreach($currentPatients as $patient){
	//Build the array of adapt equipt for this patient
	$allergy_array = array();
	foreach ($patient as $value) {
		array_push($allergy_array, $value->name);
	}
	$allergy_array = implode(", ", $allergy_array);

if(property_exists($patient[0], 'first_name')){
$html = $html .
<<<EOD
		<tr>
			<td>{$patient[0]->number}</td>
			<td>{$patient[0]->first_name} {$patient[0]->last_name}</td>
			<td>
				{$allergy_array}
			</td>
		</tr>
EOD;

}
	}
$html = $html . <<<EOD
	</tbody>
</table>
EOD;


		$pdfDetails = array("title" => $title, "html" => $html, "header" => true, "footer" => true, "orientation" => "Landscape");

		$this->buildPDFOptions($pdfDetails);

	}

	public function snack_labels() {
		smarty()->assign('title', "Snack Labels");
		$location = $this->getLocation();


		smarty()->assign('location', $location);

	}


public function snack_labels_pdf() {
		$location = $this->getLocation();
		$title = 'Snack Labels for '. $location->name . " on " . input()->date;
		$date = date('Y-m-d', strtotime(input()->date));
		$start_posit = (input()->start_posit)-1;

		$snackList = $this->loadModel("PatientSnack")->fetchByLocation($location, $date);
//		$local = $location->sha512();
	$rowcount = count($snackList);

// Declaration of table $data
$data = array();
if($start_posit > 0){
	for($i = 0; $i < $start_posit; $i++){
$html = <<<EOD
<table>
	<tbody>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td></td>
		</tr>
	</tbody>
</table>
EOD;

array_push($data,$html);

	}
}

foreach ($snackList as $snack => $value) {
$html = <<<EOD
<table>
	<tbody>
		<tr>
			<td>{$value->first_name} {$value->last_name}</td>
		</tr>
		<tr>
			<td>{$value->name}</td>
		</tr>
		<tr>
			<td>{$value->time}</td>
		</tr>
	</tbody>
</table>
EOD;

array_push($data,$html);

}


	$pdfDetails = array("title" => $title, "html" => $data, "header" => false, "footer" => false, "orientation" => "Portrait", "label" => true);

	$this->buildPDFOptions($pdfDetails);


}

	public function snack_report() {
		smarty()->assign('title', "Snack Report");
		$location = $this->getLocation();


		smarty()->assign('location', $location);

	}


public function snack_report_pdf() {
		$location = $this->getLocation();
		$title = 'Snack Report for '. $location->name. " on " . input()->date;
		$snackList = $this->loadModel("PatientSnack")->fetchByLocationSnackReport($location);
//		$local = $location->sha512();


$html = <<<EOD
<table>
	<thead>
		<tr>
			<th><strong>Snack</strong></th>
			<th><strong>Count</strong></th>
		</tr>
	</thead>
	<tbody>
EOD;

foreach ($snackList as $snack => $value) {
$html = $html . <<<EOD
<tr>
	<td>{$value->name}</td>
	<td>{$value->Count}</td>	
</tr>
EOD;
}

$html = $html . <<<EOD
	</tbody>
</table>
EOD;

	$pdfDetails = array("title" => $title, "html" => $html, "header" => true, "footer" => false, "orientation" => "Portrait");

	$this->buildPDFOptions($pdfDetails);

}


	/* 
	 * Adaptive Equipment report page 
	 *	
	 */

	public function adaptive_equipment() {
		$location = $this->getLocation();
		$current_patients = $this->loadModel('PatientAdaptEquip')->fetchByLocation($location);

		smarty()->assignByRef('patients', $current_patients);
	}



	public function adaptive_equipment_pdf() {
		$location = $this->getLocation();

		$currentPatients = $this->loadModel("PatientAdaptEquip")->fetchByLocation($location);
$html = <<<EOD
<table style="background-image:purple">
	<thead>
		<tr>
			<td><strong>Room</strong></td>
			<td><strong>Patient</strong></td>
			<td><strong>Adapt Equip</strong></td>
		</tr>
	</thead>
	<tbody>
EOD;

foreach($currentPatients as $patient){
	//Build the array of adapt equipt for this patient
	$adapt_equip_array = array();
	foreach ($patient as $value) {
		array_push($adapt_equip_array, $value->name);
	}
	$adapt_equip_array = implode(", ", $adapt_equip_array);

if(property_exists($patient[0], 'first_name')){
$html = $html .
<<<EOD
		<tr>
			<td>{$patient[0]->number}</td>
			<td>{$patient[0]->first_name} {$patient[0]->last_name}</td>
			<td>
				{$adapt_equip_array}
			</td>
		</tr>
EOD;

}
	}
$html = $html . <<<EOD
	</tbody>
</table>
EOD;

	$title = 'Adaptive Equipment Report for ' . $location->name;

	$pdfDetails = array("title" => $title, "html" => $html, "header" => true, "footer" => true, "orientation" => "Landscape");

	$this->buildPDFOptions($pdfDetails);


	/*
	 * -------------------------------------------------------------------------
	 *  Common functions for report pages
	 * -------------------------------------------------------------------------
	 */
}


	private function reportDays() {
		$numberOfDays = array(0 => "Select timeframe...", 7 => "Last 7 days", 15 => "Last 15 days", 30 => "Last 30 days", 90 => "Last 90 days", 365 => "Last 365 days");
		smarty()->assign('numberOfDays', $numberOfDays);
	}



}