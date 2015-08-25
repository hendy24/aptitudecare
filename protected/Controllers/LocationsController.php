<?php

class LocationsController extends MainPageController {


	/* 
	 * CENSUS PAGE
	 *
	 */

	public function census() {
		smarty()->assign('title', "Census");
		$this->helper = 'PatientMenu';

		$location = $this->getLocation();
		$areas = $this->getArea();

		if (isset (input()->order_by)) {
			$_order_by = input()->order_by;
		} else {
			$_order_by = false;
		}


		//	Get currently admitted patients
		if (empty($areas)) {
			$patients = $this->loadModel('Patient')->fetchCensusPatients($location->id, $_order_by, 'all');
		} else {
			$patients = $this->loadModel('Patient')->fetchCensusPatients($areas->id, $_order_by);
		}

		smarty()->assignByRef('patients', $patients);

		if (isset (input()->export)) {
			$this->exportToExcel($patients, $location);
		}

	}


	/* 
	 * 90-day Census 
	 *	
	 */
	public function ninety_day_census() {
		$this->helper = 'PatientMenu';
		$location = $this->getLocation();
		$areas = $this->getArea();

		if (isset (input()->order_by)) {
			$_order_by = input()->order_by;
		} else {
			$_order_by = false;
		}


		//	Get currently admitted patients
		if (empty($areas)) {
			$patients = $this->loadModel('Patient')->fetch90DayCensusPatients($location->id, $_order_by, 'all');
		} else {
			$patients = $this->loadModel('Patient')->fetch90DayCensusPatients($areas->id, $_order_by);
		}

		smarty()->assignByRef('patients', $patients);

		if (isset (input()->export)) {
			$this->exportToExcel($patients, $location);
		}


	}


	public function recertification_list() {
		smarty()->assign('title', "Re-certification List");
		$this->helper = 'PatientMenu';

		if (isset(input()->location)) {
			// If the location is set in the url, get the location by the public_id
			$location = $this->loadModel('Location', input()->location);

			if (isset (input()->area)) {
				$area = $this->loadModel('Location', input()->area);
			} else {
				$area = $location->fetchLinkedFacility($location->id);
			}
		} else {
			// Get the users default location from the session
			$location = $this->loadModel('Location', auth()->getDefaultLocation());

			// if users' default location is not a home health, need to get home health
			if ($location->location_type == 1) {
				$location = $location->fetchHomeHealthLocation($location->id);
			}

			$area = $location->fetchLinkedFacility($location->id);
		}

		smarty()->assign('loc', $location);
		smarty()->assignByRef('selectedArea', $area);

		$schedule = $this->loadModel('HomeHealthSchedule')->fetchReCertList($area->id);
		smarty()->assignByRef('censusList', $schedule);
	}


	public function fetchAreas() {
		$areas = $this->loadModel('Location', input()->location)->fetchLinkedFacilities();
		json_return ($areas);
	}



	public function exportToExcel($patients, $location) {
		/*
		 * EXPORT THE CENSUS TO AN EXCEL FILE
		 *
		 * NOTE: If "export" exists in the post data then the census data will be exported to
		 * an excel file.
		 *
		 */

		require_once FRAMEWORK_PROTECTED_DIR . DS . "Vendors" . DS . "PHPExcel/Classes/PHPExcel.php";

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getProperties()->setTitle($location->name . " Census")
						 ->setSubject("Current Census")
						 ->setDescription("Current census for " . $location->name . " from the AptitudeCare Home Health Dashboard.")
						 ->setKeywords("aptitudecare census current")
						 ->setCategory("Census file");

		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A1', 'Patient Name')
		            ->setCellValue('B1', 'Referral Date')
		            ->setCellValue('C1', 'Start of Care')
		            ->setCellValue('D1', 'Discharge Date')
		            ->setCellValue('E1', 'Referral Source')
		            ->setCellValue('F1', 'Address')
		            ->setCellValue('G1', 'City')
		            ->setCellValue('H1', 'State')
		            ->setCellValue('I1', 'Zip')
		            ->setCellValue('J1', 'Phone')
		            ->setCellValue('K1', 'Following Physician');

		$objPHPExcel->getActiveSheet()->getStyle("A1:K1")->getFont()->setBold(true)->setSize(14);
		$objPHPExcel->getActiveSheet()->getStyle("A1:K1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 

		// Loop through the patients and set the data in the correct columns
		foreach ($patients as $k => $patient) {
			$row = 2 + $k;
			$objPHPExcel->setActiveSheetIndex(0)
			 		->setCellValue("A{$row}", $patient->fullName())
			 		->setCellValue("B{$row}", display_datetime($patient->referral_date))
			 		->setCellValue("C{$row}", display_datetime($patient->start_of_care))
			 		->setCellValue("D{$row}", display_date($patient->datetime_discharge))
			 		->setCellValue("E{$row}","")
			 		->setCellValue("F{$row}", $patient->address)
			 		->setCellValue("G{$row}", $patient->city)
			 		->setCellValue("H{$row}", $patient->state)
			 		->setCellValue("I{$row}", $patient->zip)
			 		->setCellValue("J{$row}", $patient->phone)
			 		->setCellValue("K{$row}", "");
		}

		foreach(range('A','K') as $columnID) {
		    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
		        ->setAutoSize(true);
		}

		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Census');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="currentCensus.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;

	}






}
