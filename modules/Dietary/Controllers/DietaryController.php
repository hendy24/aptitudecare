<?php
// Include the main TCPDF library (search for installation path).
require_once('../protected/Libs/Components/tcpdf/tcpdf.php');
require_once('../protected/Libs/Components/tcpdf/class.label.php');
require_once('../protected/Libs/Components/tcpdf/class.labelExemple.php');



class DietaryController extends MainPageController {

	// protected $template = "dietary";
	public $module = "Dietary";
	protected $navigation = 'dietary';
	protected $searchBar = 'dietary';
	protected $helper = 'DietaryMenu';



	public function index() {
		smarty()->assign("title", "Dietary");
		// if user is not authorized to access this page, then re-direct
		if (!auth()->getRecord()) {
			$this->redirect();
		}

		// get the location
		$location = $this->getLocation();

		// check if the user has permission to access this module
		if ($location->location_type != 1) {
			$this->redirect();
		}

		// check if the location is has the admission dashboard enabled
		$modEnabled = ModuleEnabled::isAdmissionsEnabled($location->id);

		// if the facility is using the admission dashboard, then get a list of
		// the current patients from the admission app for the current location.

		// NOTE: if a location is using the admission dashboard they should
		// not have the ability to add or delete patients through the dietary
		// app interface.
		$rooms = $this->loadModel("Room")->fetchEmpty($location->id);
		if ($modEnabled) {
			// until the admission app is re-built and we move to a single database we need to fetch
			// the data from the admission db and save to the master db
			// IMPORTANT: Remove this after admission app is re-built in new framework!!!
			$scheduled = $this->loadModel('AdmissionDashboard')->syncCurrentPatients($location->id);
		} else {
			// if the locations is not using the admission dashboard then load the patients
			// from ac_patient and dietary_patient_info tables
			// fetch current patients
			$scheduled = $this->loadModel("Patient")->fetchPatients($location->id);
		}
		$currentPatients = $this->loadModel("Room")->mergeRooms($rooms, $scheduled);


		smarty()->assign('currentPatients', $currentPatients);
		smarty()->assign('modEnabled', $modEnabled);
	}



	public function normalizeMenuItems($menuItems) {
		$menuWeek = false;
		foreach ($menuItems as $key => $item) {

			if (isset ($item->date) && $item->date != "") {
				$menuItems[$key]->type = "MenuMod";
			} elseif (isset ($item->menu_item_id) && $item->menu_item_id != "") {
				$menuItems[$key]->type = "MenuChange";
			} else {
				$menuItems[$key]->type = "MenuItem";
			}

			// Get the current week
			$menuWeek = floor($item->day / 7);

			$menuItems[$key]->content = nl2br($item->content);

			// explode the tags
			if (strstr($item->content, "<p>")) {
				$menuItems[$key]->content = explode("<p>", $item->content);
				$menuItems[$key]->content = str_replace("</p>", "", $item->content);
			} else {
				$menuItems[$key]->content = explode("<br />", $item->content);
			}

			if (isset ($item->mod_content)) {
				// explode the tags
				if (strstr($item->mod_content, "<p>")) {
					$menuItems[$key]->mod_content = explode("<p>", $item->mod_content);
					$menuItems[$key]->mod_content = str_replace("</p>", "", $item->mod_content);
				} else {
					$menuItems[$key]->mod_content = explode("<br />", $item->mod_content);
				}
			}


		}

		smarty()->assign('count', 0);
		smarty()->assign('menuWeek', $menuWeek);
		smarty()->assignByRef('menuItems', $menuItems);
	}


	public function buildPDFOptions($pdfDetails){

		if (array_key_exists('orientation', $pdfDetails)) {
    		$orientation = $pdfDetails["orientation"];	
		} else{
			$orientation = "Landscape";
		}
		if (array_key_exists('title', $pdfDetails)) {
    		$title = $pdfDetails["title"];	
		} else{
			$title = "Report";
		}
		if (array_key_exists('html', $pdfDetails)) {
    		$html = $pdfDetails["html"];	
		} else{
			$html = "<table></table>";
		}
		if (array_key_exists('header', $pdfDetails)) {
    		$header = $pdfDetails["header"];	
		} else{
			$header = false;
		}
		if (array_key_exists('footer', $pdfDetails)) {
    		$footer = $pdfDetails["footer"];	
		} else{
			$footer = false;
		}
		if (array_key_exists('top_margin', $pdfDetails)){
			$top_margin = $pdfDetails["top_margin"];
		} else{
			$top_margin = PDF_MARGIN_TOP;
		}
		if (array_key_exists('bottom_margin', $pdfDetails)){
			$bottom_margin = $pdfDetails["bottom_margin"];
		} else{
			$bottom_margin = PDF_MARGIN_TOP;
		}
		if (array_key_exists('font_size', $pdfDetails)){
			$font_size = $pdfDetails["font_size"];
		} else{
			$font_size = 12;
		}
		if (array_key_exists('label', $pdfDetails)) {
			$pdf = new labelExemple( 10 , $html , "../protected/Libs/Components/tcpdf/", "labels.xml", false);

			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor("AHC");
			$pdf->SetTitle($title);
			$pdf->SetSubject($title);

			// remove default header/footer
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);

			// remove default margin
//			$pdf->SetHeaderMargin(0);
//			$pdf->SetFooterMargin(0);

			$pdf->SetAutoPageBreak( true, 0);

			//set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 

			$pdf->Addlabel();

		} else{

			// create new PDF document
			$pdf = new TCPDF($orientation . " !", PDF_UNIT, 'LETTER', true, 'UTF-8', false);
			
			// set default header data
			$pdf->SetHeaderData("", PDF_HEADER_LOGO_WIDTH, $title, "", array(0,64,255), array(0,64,128));
			$pdf->setFooterData(array(0,64,0), array(0,64,128));

			// set header and footer fonts
			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			// set margins
			$pdf->SetMargins(2, $top_margin, 2);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

			if($footer == false){
				$pdf->SetPrintFooter(false);
			}

			if($header == false){
				$pdf->SetPrintHeader(false);
			}

			// set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, 0);

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
			$pdf->SetFont('dejavusans', '', $font_size, '', true);

			// Add a page
			// This method has several options, check the source code documentation for more information.
			$pdf->AddPage();

			// set text shadow effect
			$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));


			//For custom footer.  Need to remove the margin on the bottom
			if(array_key_exists('custom_footer', $pdfDetails)){
				
				//$html = $pdfDetails["custom_footer"] . "<br><br>" . $html;// .  . $pdf->getPageHeight();
        		//$pdf->writeHTMLCell($w=0, $h=0, $x=0, $y=200, $pdfDetails["custom_footer"], $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
			}

			// Print text using writeHTMLCell()
			$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

			// ---------------------------------------------------------

		}
		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output($title . '.pdf', 'I');

	}


}
