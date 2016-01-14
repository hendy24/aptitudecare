<?php
// Include the main TCPDF library (search for installation path).
require_once('../protected/Libs/Components/tcpdf/tcpdf.php');

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

	public function adaptive_equipment() {

	}

	public function adaptive_equipment_pdf() {

		// get the location

		$rooms = $this->loadModel("Room")->fetchEmpty($this->getLocation()->id);
		$currentPatients = $this->loadModel("PatientAdaptEquip")->fetchByLocation($this->getLocation());


// create new PDF document
$pdf = new TCPDF("Landscape !", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set default header data
$pdf->SetHeaderData("", PDF_HEADER_LOGO_WIDTH, 'Adaptive Equipment Report', "", array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 12, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

//pr($currentPatients); exit;

$html = <<<EOD
<table>
	<thead>
		<tr>
			<td>Room</td>
			<td>Patient</td>
			<td>AdaptEquip</td>
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

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('example_001asdf.pdf', 'I');



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