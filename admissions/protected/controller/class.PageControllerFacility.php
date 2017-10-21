<?php

class PageControllerFacility extends PageController {
	public function init() {
		Authentication::disallow();	
	}
	
	public function index() {
		// make sure the signed-in user is either a coordinator or has access to this facility
		$facility = new CMS_Facility(input()->id);
		if ($facility->valid() == false) {
			// invalid facility specified
			feedback()->error("Cannot access facility record.");
			$this->redirect(SITE_URL . "/?page=home");
		} else {
			// make sure you can access this record
			if (! auth()->getRecord()->hasAccess($facility) ) {
				feedback()->error("Permission Denied");
				$this->redirect(auth()->getRecord()->homeURL());
			}			
		}
		
		// Optionally use a different week
		if (input()->weekSeed != '') {
			$weekSeed = input()->weekSeed;
			if (Validate::is_american_date($weekSeed)->success() || Validate::is_standard_date($weekSeed)->success()) {
				//$week = Calendar::getDateSequence($weekSeed, 7);
				$week = Calendar::getWeek($weekSeed);
			}
		} else {
		// Default to "this week"
			$weekSeed = date("Y-m-d");
			//$week = Calendar::getDateSequence($weekSeed, 7);
			$week = Calendar::getWeek($weekSeed);		
		}
		
		// Now grab the next week (use 6 days so that this weeks' last day appears as first)
		$nextWeekSeed = date("Y-m-d", strtotime("+7 days", strtotime($week[0])));
		
		smarty()->assign(array(
			"weekSeed" => $weekSeed,
			"nextWeekSeed" => $nextWeekSeed,
			"weekStart" => date('Y-m-d', strtotime($weekSeed))
		));
		
		// admits -- everyone on the docket this week
		$_dateStart = $week[0];		// Sunday of this week
		$_dateEnd = $week[6];		// Saturday	of this week
		$_status = false;			// any status
		$_facility = array($facility);				// my facility
		$_orderby = "datetime_admit ASC";	// order by datetime, sooner at the top.
		$admits = CMS_Schedule::fetchAdmits($_dateStart, $_dateEnd, $_status, $_facility, $_orderby);
		if ($admits == false) {
			$admits = array();
		}
		smarty()->assignByRef("admits", $admits);
		
		// split the admits up by date
		$admitsByDate = array();
		foreach ($admits as $admit) {
			$date = date("Y-m-d", strtotime($admit->datetime_admit));
			if (! isset($admitsByDate[$date]) ) {
				$admitsByDate[$date] = array();
			}
			$admitsByDate[$date][] = $admit;
		}
		smarty()->assignByRef("admitsByDate", $admitsByDate);
				
		// discharges -- everyone this week
		$_dateStart = $week[0];		// Sunday of this week
		$_dateEnd = $week[6];		// Saturday	of this week
		$_facility = array($facility);				// my facility
		$_orderby = "datetime_discharge ASC";	// order by datetime, sooner at the top.
		$discharges = CMS_Schedule::fetchDischarges($_dateStart, $_dateEnd, $_facility, $_orderby);
		if ($discharges == false) {
			$discharges = array();
		}
		smarty()->assignByRef("discharges", $discharges);
		
		// split the discharges up by date
		$dischargesByDate = array();
		foreach ($discharges as $discharge) {
			$date = date("Y-m-d", strtotime($discharge->datetime_discharge));
			if (! isset($dischargesByDate[$date]) ) {
				$dischargesByDate[$date] = array();
			}
			$dischargesByDate[$date][] = $discharge;
			
			// add this discharge to the next few days' visible discharge schedule if it's a bed hold
			if ($discharge->discharge_to == 'Discharge to Hospital (Bed Hold)') {
				
				// init tracking var to the discharge date and start adding days from there.
				$bhd = date("Y-m-d H:i:s", strtotime($discharge->datetime_discharge));

				while(1) {
					// if we made it this far, add this record to the calendar day
					// represented by $bhd
					if (! isset($dischargesByDate[date("Y-m-d", strtotime($bhd))]) ) {
						$dischargesByDate[date("Y-m-d", strtotime($bhd))] = array();
					}
					if (! in_array($discharge, $dischargesByDate[date("Y-m-d", strtotime($bhd))])) {
						$dischargesByDate[date("Y-m-d", strtotime($bhd))][] = $discharge;
					}

					// make sure that we haven't crossed over into a calendar day too far...
					$check1 = date("Y-m-d", strtotime($discharge->datetime_discharge_bedhold_end)) . " 00:00:00";
					$check2 = date("Y-m-d", strtotime("+1 day", strtotime($bhd))) . " 00:00:00";
					if (strtotime($check2) > strtotime($check1)) {
						break;
					}
					
					// add a day to the tracking var and loop...
					$bhd = date("Y-m-d H:i:s", strtotime("+1 day", strtotime($bhd)));
					
				}
			}
		}
		smarty()->assignByRef("dischargesByDate", $dischargesByDate);

		// sent to hospital -- everyone this week
		$sents = CMS_Schedule::fetchSent($_dateStart, $_dateEnd, $_facility, $_orderby);
		if ($sents == false) {
			$sents = array();
		}
		smarty()->assignByRef("sents", $sents);

		// split up sent to hospital by date
		$sentsByDate = array();
		foreach ($sents as $sent) {
			$date = date("Y-m-d", strtotime($sent->datetime_sent));
			if (! isset($sentsByDate[$date]) ) {
				$sentsByDate[$date] = array();
			}
			$sentsByDate[$date][] = $sent;
		}
		smarty()->assignByRef("sentsByDate", $sentsByDate);
		
		smarty()->assign("week", $week);
		smarty()->assignByRef("facility", $facility);
		
		// seeds for up/down links (so that it "scrolls" one week at a time)
		smarty()->assign(array(
			"advanceWeekSeed" => $nextWeekSeed,
			"retreatWeekSeed" => date("Y-m-d", strtotime("-7 days", strtotime($weekSeed))),
		));
		
		// empty room count
		smarty()->assign("emptyRoomCount", count(CMS_Room::fetchEmptyByFacility($facility->id, datetime())));
				
	}	
	
	public function two_week_view() {		
		
		// make sure the signed-in user is either a coordinator or has access to this facility
		$facility = new CMS_Facility(input()->id);
		if ($facility->valid() == false) {
			// invalid facility specified
			feedback()->error("Cannot access facility record.");
			$this->redirect(SITE_URL . "/?page=home");
		} else {
			// make sure you can access this record
			if (! auth()->getRecord()->hasAccess($facility) ) {
				feedback()->error("Permission Denied");
				$this->redirect(auth()->getRecord()->homeURL());
			}			
		}
		
		$emptyRooms = CMS_Room::fetchEmptyByFacility($facility->id, datetime());
					
		// Optionally use a different week
		if (input()->weekSeed != '') {
			$weekSeed = input()->weekSeed;
			if (Validate::is_american_date($weekSeed)->success() || Validate::is_standard_date($weekSeed)->success()) {
				//$week = Calendar::getDateSequence($weekSeed, 7);
				$week = Calendar::getWeek($weekSeed);
			}
		} else {
		// Default to "this week"
			$weekSeed = date("Y-m-d");
			//$week = Calendar::getDateSequence($weekSeed, 7);
			$week = Calendar::getWeek($weekSeed);	
		}
		
		// Now grab the next week (use 6 days so that this weeks' last day appears as first)
		$nextWeekSeed = date("Y-m-d", strtotime("+7 days", strtotime($week[0])));
		$nextWeek = Calendar::getWeek($nextWeekSeed);
				
		// admits -- everyone on the docket this week
		$_dateStart = $week[0];		// Sunday of this week
		$_dateEnd = $week[6];		// Saturday	of this week
		$_status = false;			// any status
		$_facility = array($facility);				// my facility
		$_orderby = "datetime_admit ASC";	// order by datetime, sooner at the top.
		$admits = CMS_Schedule::fetchAdmits($_dateStart, $_dateEnd, $_status, $_facility, $_orderby);
		if ($admits == false) {
			$admits = array();
		}
		
		// split the admits up by date
		$admitsByDate = array();
		foreach ($admits as $admit) {
			$date = date("Y-m-d", strtotime($admit->datetime_admit));
			if (! isset($admitsByDate[$date]) ) {
				$admitsByDate[$date] = array();
			}
			$admitsByDate[$date][] = $admit;
		}
		
		/*
		 *
		 *	
		// admits -- everyone on the next week */
		$_nextDateStart = $nextWeek[0];		// Sunday of next week
		$_nextDateEnd = $nextWeek[6];		// Saturday	of next week
		$nextAdmits = CMS_Schedule::fetchAdmits($_nextDateStart, $_nextDateEnd, $_status, $_facility, $_orderby);
		if ($nextAdmits == false) {
			$nextAdmits = array();
		}
		
		// split the admits up by date
		$nextAdmitsByDate = array();
		foreach ($nextAdmits as $nextAdmit) {
			$nextDate = date("Y-m-d", strtotime($nextAdmit->datetime_admit));
			if (! isset($nextAdmitsByDate[$nextDate]) ) {
				$nextAdmitsByDate[$nextDate] = array();
			}
			$nextAdmitsByDate[$nextDate][] = $nextAdmit;
		}
		
		// discharges -- everyone this week
		$_dateStart = $week[0];		// Sunday of this week
		$_dateEnd = $week[6];		// Saturday	of this week
		$_facility = array($facility);				// my facility
		$_orderby = "datetime_discharge ASC";	// order by datetime, sooner at the top.
		$discharges = CMS_Schedule::fetchDischarges($_dateStart, $_dateEnd, $_facility, $_orderby);
		if ($discharges == false) {
			$discharges = array();
		}
		
		// split the discharges up by date
		$dischargesByDate = array();
		foreach ($discharges as $discharge) {
			$date = date("Y-m-d", strtotime($discharge->datetime_discharge));
			if (! isset($dischargesByDate[$date]) ) {
				$dischargesByDate[$date] = array();
			}
			$dischargesByDate[$date][] = $discharge;
			
			// add this discharge to the next few days' visible discharge schedule if it's a bed hold
			if ($discharge->discharge_to == 'Discharge to Hospital (Bed Hold)') {
				
				// init tracking var to the discharge date and start adding days from there.
				$bhd = date("Y-m-d H:i:s", strtotime($discharge->datetime_discharge));

				while(1) {
					// if we made it this far, add this record to the calendar day
					// represented by $bhd
					if (! isset($dischargesByDate[date("Y-m-d", strtotime($bhd))]) ) {
						$dischargesByDate[date("Y-m-d", strtotime($bhd))] = array();
					}
					if (! in_array($discharge, $dischargesByDate[date("Y-m-d", strtotime($bhd))])) {
						$dischargesByDate[date("Y-m-d", strtotime($bhd))][] = $discharge;
					}

					// make sure that we haven't crossed over into a calendar day too far...
					$check1 = date("Y-m-d", strtotime($discharge->datetime_discharge_bedhold_end)) . " 00:00:00";
					$check2 = date("Y-m-d", strtotime("+1 day", strtotime($bhd))) . " 00:00:00";
					if (strtotime($check2) > strtotime($check1)) {
						break;
					}
					
					// add a day to the tracking var and loop...
					$bhd = date("Y-m-d H:i:s", strtotime("+1 day", strtotime($bhd)));
					
				}
			}
		}
		
		/*
		 *
		 *
		 * get the discharges for the next week */
		$_nextDateStart = $nextWeek[0];		// Sunday of this week
		$_nextDateEnd = $nextWeek[6];		// Saturday	of this week
		$nextWeekDischarges = CMS_Schedule::fetchDischarges($_nextDateStart, $_nextDateEnd, $_facility, $_orderby);
		if ($nextWeekDischarges == false) {
			$nextWeekDischarges = array();
		}
		
		// split the discharges up by date
		$nextDischargesByDate = array();
		foreach ($nextWeekDischarges as $nextDischarge) {
			$nextDate = date("Y-m-d", strtotime($nextDischarge->datetime_discharge));
			if (! isset($nextDischargesByDate[$nextDate]) ) {
				$nextDischargesByDate[$nextDate] = array();
			}
			$nextDischargesByDate[$nextDate][] = $nextDischarge;
			
			// add this discharge to the next few days' visible discharge schedule if it's a bed hold
			if ($discharge->discharge_to == 'Discharge to Hospital (Bed Hold)') {
				
				// init tracking var to the discharge date and start adding days from there.
				$bhd = date("Y-m-d H:i:s", strtotime($nextDischarge->datetime_discharge));

				while(1) {
					// if we made it this far, add this record to the calendar day
					// represented by $bhd
					if (! isset($nextDischargesByDate[date("Y-m-d", strtotime($bhd))]) ) {
						$$nextDischargesByDate[date("Y-m-d", strtotime($bhd))] = array();
					}
					if (! in_array($nextDischarge, $nextDischargesByDate[date("Y-m-d", strtotime($bhd))])) {
						$nextDischargesByDate[date("Y-m-d", strtotime($bhd))][] = $nextDischarge;
					}

					// make sure that we haven't crossed over into a calendar day too far...
					$check1 = date("Y-m-d", strtotime($nextDischarge->datetime_discharge_bedhold_end)) . " 00:00:00";
					$check2 = date("Y-m-d", strtotime("+1 day", strtotime($bhd))) . " 00:00:00";
					if (strtotime($check2) > strtotime($check1)) {
						break;
					}
					
					// add a day to the tracking var and loop...
					$bhd = date("Y-m-d H:i:s", strtotime("+1 day", strtotime($bhd)));
					
				}
			}
		}
		
		// Export doc to either Excel or PDF
		require_once APP_PROTECTED_PATH . "/lib/contrib/Classes/PHPExcel.php";
		
		if (input()->type == "excel") {
			$objPHPExcel = PHPExcel_IOFactory::load(APP_PATH . "/public/templates/template.xlsx");
		}
		
		
		if (input()->type == "pdf") {
			$objPHPExcel = PHPExcel_IOFactory::load(APP_PATH . "/public/templates/two_weeks_pdf.xlsx");
			//$objPHPExcel = new PHPExcel();
			//	Change these values to select the Rendering library that you wish to use
			//		and its directory location on your server
			//$rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
			$rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
			//$rendererName = PHPExcel_Settings::PDF_RENDERER_DOMPDF;
			//$rendererLibrary = 'tcPDF';
			$rendererLibrary = 'mPDF5.3';
			//$rendererLibrary = "domPDF";
			$rendererLibraryPath = APP_PROTECTED_PATH . "/lib/contrib/Libraries/" . $rendererLibrary;
			
			//$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
			//$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
/*
			$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(true);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(true);
*/


			
			if (!PHPExcel_Settings::setPdfRenderer(
				$rendererName,
				$rendererLibraryPath
			)) {
				die(
					'NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
					EOL .
					'at the top of this script as appropriate for your directory structure'
				);
			}
			

		}
		
		$objPHPExcel->getProperties()->setTitle("$facility->name Admission Board");
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader("&24" . $facility->name . ' Admission Board');

		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter("&RPrinted: " . date("m/d/y g:i a", strtotime("now")));

		
					
		// Set properties
		$styleArray = array(
			'font' => array(
				'bold' => true,
			)
		);	
		
		$admitsStyleArray = array(
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '75dc6d')
			)
		);
		
		$dischargeStyleArray = array(
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'FF6A6A')
			)
		);
		
				
		foreach ($week as $day) {
			$admits = $admitsByDate[$day];
			$discharges = $dischargesByDate[$day];
						
			// Compile week 1 admit data into an array			
			if (!empty ($admits)) {
				foreach ($admits as $admit) {
					if ($admit->status == "Approved") {
						$approved = true;
					} else {
						$approved = false;
					}
					if ($day == date("Y-m-d", strtotime($admit->datetime_admit))) {
						$admitData[$day][] = array(
							'room' => $admit->getRoomNumber(),
							'name' => $admit->getPatient()->fullName(),
							'approved' => $approved
						);
					}

				}
			} else {
				$admitData[$day][] = array();
			}
			
			// Compile week 1 discharge info into an array
			if (!empty ($discharges)) {
				foreach ($discharges as $discharge) {	
					if ($day == date("Y-m-d", strtotime($discharge->datetime_discharge))) {				
						$dischargeData[$day][] = array(
							'room' => $discharge->getRoomNumber(),
							'name' => $discharge->getPatient()->fullName(),
						);
					}
				}
			} else {
				$dischargeData[$day][] = array();
			}
				
		}
				
		foreach ($nextWeek as $day) {
			$admits = $nextAdmitsByDate[$day];
			$nextDischarges = $nextDischargesByDate[$day];
				
			if (!empty ($admits)) {
				foreach ($admits as $admit) {	
					if ($admit->status == "Approved") {
						$approved = true;
					} else {
						$approved = false;
					}
					if ($day == date("Y-m-d", strtotime($admit->datetime_admit))) {
						$nextAdmitData[$day][] = array(
							'room' => $admit->getRoomNumber(),
							'name' => $admit->getPatient()->fullName(),
							'approved' => $approved
						);
					} 
			
				} 
			} else {
				$nextAdmitData[$day][] = array();
			}
			
			if (!empty ($nextDischarges)) {
				foreach ($nextDischarges as $discharge) {	
					if ($day == date("Y-m-d", strtotime($discharge->datetime_discharge))) {
						$nextDischargeData[$day][] = array(
							'room' => $discharge->getRoomNumber(),
							'name' => $discharge->getPatient()->fullName(),
						);
					} 
			
				} 
			} else {
				$nextDischargeData[$day][] = array();
			}

			
		}	
									
		/*
		 * -------------------------------------------------------------
		 *  WEEK 1 ADMISSION DATA
		 * -------------------------------------------------------------
		 * 
		 */
		$baseRow = 4;
		$i = 0;
		$d = 0;
		$c = "A";
		
		
		$count = 0;
		
		$weekOne = date("D, F d, Y", strtotime($week[0])) . " - " . date("D, F d, Y", strtotime($week[6]));
		$weekTwo = date("D, F d, Y", strtotime($nextWeek[0])) . " - " . date("D, F d, Y", strtotime($nextWeek[6]));
		
		$count = count (max ($admitData));
							
		foreach ($admitData as $r => $dataRow) {
		
			
			
			foreach ($dataRow as $k => $data) {
				
				if ($k == 0) {
					$d = 0;
				} else {
					$d++;
				}
					
				// Week title info			
				$objPHPExcel->getActiveSheet()
					->setCellValue("A1", $weekOne)
					->mergeCells("A1:G1");
				$objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(18);
				
				// Admit title info
				$objPHPExcel->getActiveSheet()
					->setCellValue("A2", "Admissions")
					->mergeCells("A2:G2");
				$objPHPExcel->getActiveSheet()->getStyle("A2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray($admitsStyleArray)
					->getFont()->setSize(14);
								
				if ($c <= 'G') {					
					if ($d == 0) {
						$i = 0;
						$objPHPExcel->getActiveSheet()->setCellValue($c."3", date("m/d/Y", strtotime($r)))
							->getStyle($c."3")->applyFromArray($styleArray)
							->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					}
					$row = $baseRow + $i;
					if ($data['approved'] == true) {
						$objPHPExcel->getActiveSheet()->getStyle($c.$row)->applyFromArray($styleArray);
					}
					$objPHPExcel->getActiveSheet()->setCellValue($c.$row, $data['room'] . " " . $data['name'])	
						->getStyle($c.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
																									
				}
				$i++;
				
			}
			
			$c++;												
		}
		
		
						
		// Set discharge info for week 1
		/*
		 * -------------------------------------------------------------
		 *  WEEK 1 DISCHARGE INFO
		 * -------------------------------------------------------------
		 * 
		 */
/*
		$titleRow = count(max ($admitData)) + 6;
		$baseRow = $titleRow + 1;
*/
		$titleRow = ($baseRow + $count) + 2;
		$baseRow = $titleRow + 1;
		$i = 0;
		$d = 0;
		$c = "A";
		$count = count (max ($dischargeData));
		
													
		foreach ($dischargeData as $r => $dataRow) {
			foreach ($dataRow as $k => $data) {
				if ($k == 0) {
					$d = 0;
				} else {
					$d++;
				}
								
				$objPHPExcel->getActiveSheet()
					->setCellValue("A".$titleRow, "Discharges")
					->mergeCells("A".$titleRow.":G".$titleRow);
				$objPHPExcel->getActiveSheet()->getStyle("A".$titleRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle("A".$titleRow)->applyFromArray($dischargeStyleArray)
					->getFont()->setSize(14);
								
				if ($c <= 'G') {					
					if ($d == 0) {
						$i = 0;
					}
					$row = $baseRow + $i;
					$objPHPExcel->getActiveSheet()->setCellValue($c.$row, $data['room'] . " " . $data['name'])
						->getStyle($c.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);																					
				}
				$i++;
			}
			
			$c++;												
		}

		/*
		 * -------------------------------------------------------------
		 *  WEEK 2 ADMISSION DATA
		 * -------------------------------------------------------------
		 * 
		 */
/*
		$titleRow = count(max ($nextAdmitData)) + count(max($dischargeData)) + 12;
		$baseRow = $titleRow + 1;
		$dateRow = $baseRow + 1;
*/
		
		$titleRow = ($baseRow + $count) + 2;
		$baseRow = $titleRow + 1;
		$dateRow = $baseRow + 1;
		
		$i = 1;	
		$d = 0;
		$c = "A";
		
		$count = count (max ($nextAdmitData));
		
		// $this->formatDataForExcel($objPHPExcel, $nextAdmitData, $baseRow, $i, $styleArray);
		foreach ($nextAdmitData as $r => $dataRow) {
			foreach ($dataRow as $k => $data) {
				if ($k == 0) {
					$d = 0;
				} else {
					$d++;
				}
				
				$objPHPExcel->getActiveSheet()
					->setCellValue("A".$titleRow, $weekTwo)
					->mergeCells("A".$titleRow.":G".$titleRow);
				$objPHPExcel->getActiveSheet()->getStyle("A".$titleRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle("A".$titleRow)->getFont()->setSize(18);
				
				$objPHPExcel->getActiveSheet()
					->setCellValue("A".$baseRow, "Admissions")
					->mergeCells("A".$baseRow.":G".$baseRow);
				$objPHPExcel->getActiveSheet()->getStyle("A".$baseRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle("A".$baseRow)->applyFromArray($admitsStyleArray)
					->getFont()->setSize(14);
																
				if ($c <= 'G') {					
					if ($d == 0) {
						$i = 1;
						$objPHPExcel->getActiveSheet()->setCellValue($c.$dateRow, date("m/d/Y", strtotime($r)))
							->getStyle($c.$dateRow)->applyFromArray($styleArray)
							->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					}
					$row = $dateRow + $i;
					if ($data['approved'] == true) {
						$objPHPExcel->getActiveSheet()->getStyle($c.$row)->applyFromArray($styleArray);
					}
					$objPHPExcel->getActiveSheet()->setCellValue($c.$row, $data['room'] . " " . $data['name'])
						->getStyle($c.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
																									
				}
				$i++;
			}
			
			$c++;												
		}
				
		/*
		 * -------------------------------------------------------------
		 *  WEEK 2 DISCHARGE DATA
		 * -------------------------------------------------------------
		 * 
		 */
/*
		$titleRow = count(max ($nextAdmitData)) + count(max($admitData)) + count(max($dischargeData)) + 20;
		$baseRow = $titleRow + 1;
*/

		$titleRow = ($dateRow + $count) + 2;
		$baseRow = $titleRow + 1;
		$i = 1;
		$d = 0;
		$c = "A";
		
		$count = count (max ($nextDischargeData));
				
		foreach ($nextDischargeData as $r => $dataRow) {
			foreach ($dataRow as $k => $data) {
				if ($k == 0) {
					$d = 0;
				} else {
					$d++;
				}
				
				$objPHPExcel->getActiveSheet()
					->setCellValue("A".$titleRow, "Discharges")
					->mergeCells("A".$titleRow.":G".$titleRow);
				$objPHPExcel->getActiveSheet()->getStyle("A".$titleRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle("A".$titleRow)->applyFromArray($dischargeStyleArray)
					->getFont()->setSize(14);
								
				if ($c <= 'G') {					
					if ($d == 0) {
						$i = 1;
					}
					$row = $baseRow + $i;
					$objPHPExcel->getActiveSheet()->setCellValue($c.$row, $data['room'] . " " . $data['name'])
						->getStyle($c.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);																					
				}
				$i++;
			}
			
			$c++;												
		}
		
		// Show empty room numbers
		//$roomCountRow = count(max ($nextAdmitData)) + count(max($admitData)) + count(max($dischargeData)) + count(max($nextDischargeData)) + 20;	
		$roomCountRow = ($baseRow + $count)	+ 6;
		$baseRow = $roomCountRow + 1;
		$i = 0;
		$d = 0;
		$c = "A";
		$objPHPExcel->getActiveSheet()
			->setCellValue("A".$roomCountRow, "Empty Rooms")
			->getStyle("A".$roomCountRow)->applyFromArray($styleArray);
		foreach ($emptyRooms as $empty) {
			$row = $baseRow + $i;
			$objPHPExcel->getActiveSheet()->setCellValue($c.$row, $empty->number);
			$i++;
		}


		
		// Include required files
		require_once APP_PROTECTED_PATH . "/lib/contrib/Classes/PHPExcel/IOFactory.php";
		if (input()->type == "excel") {
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			// Output to Excel file
			header('Pragma: ');
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			// Name the file
			header("Content-Disposition: attachment; filename=" . $facility->name . "_" . $_dateStart . ".xlsx");
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');		
		} elseif (input()->type == "pdf") { // If you want to output e.g. a PDF file, simply do:			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
			// Output to PDF file
			header('Pragma: ');
			header("Content-type: application/pdf");
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');		
			// Name the file
			//header("Content-Disposition: attachment; filename=" . $facility->name . "_" . $_dateStart . ".pdf");
		}

		// Write file to the browser
		$objWriter->save("php://output");
		exit;
						
		
	}
			
	public function sendToHospital() {
		if (input()->schedule != '') {
			$schedule = new CMS_Schedule(input()->schedule);
			$facility = new CMS_Facility($schedule->facility);
			smarty()->assign("facility", $facility);
			smarty()->assignByRef("schedule", $schedule);
			smarty()->assign("path", input()->path);
			
			// a specific AHR was given in the URL: check that it's valid
			if (input()->ahr != '') {
				$atHospitalRecord = new CMS_Schedule_Hospital(input()->ahr);
				if ($atHospitalRecord->valid() == false || $atHospitalRecord->is_complete == 1) {
					feedback()->warning("The link you have clicked has expired. The hospital stay it references is most likely no longer being tracked.");
					$this->redirect(auth()->getRecord()->homeURL());
				}
			}
			// $icd9 = CMS_Icd9_Codes::getICD9Codes();
			// smarty()->assign("codes", $icd9);
			
		} else {
			feedback()->error("Invalid schedule record selected.");
			$this->redirect(auth()->getRecord()->homeURL());
		}
	}
	
	
	public function delete() {
		if (input()->schedule_hospital == '') {
			feedback()->error('Select a valid Hospital Visit');
			$this->redirect(input()->_path);
		} else {
			$sh = new CMS_Schedule_Hospital(input()->schedule_hospital);
			
			
			if ($sh->deleteVisit($sh->id)) {
				feedback()->conf("The hospital visit was successfully deleted.");
				$this->redirect(SITE_URL . "/?page=facility&action=census");
			} else {
				feedback()->error('Could not delete the hospital visit.');
				$this->redirect(input()->_path);
			}
		}
		
	}


	
/*
	public function cancelHospitalVisit() {
		if (input()->schedule != '') {
			// Need to get current hospital visit
			$schedule = input()->schedule;
			$hospitalVisit = CMS_Schedule_Hospital::cancelHospitalVisitInfo($schedule);
			if (!empty($hospitalVisit)) {
				foreach ($hospitalVisit as $visit) {
					if (CMS_Schedule_Hospital::delete($visit->id)) {
						$success = true;
					}
					
				} 	
									
			} 
			
			if ($success == true) {
				feedback()->confirm("The hospital stay was cancelled");
				$this->redirect(SITE_URL . "?page=coord");
			} else {
				feedback()->error("There is no current hospital stay for this patient.");
				$this->redirect(SITE_URL . "?page=coord");
			}		

		} else {
			feedback()->error("Invalid schedule record selected.");
			$this->redirect(SITE_URL . "?page=coord");
		}
	}
*/
	
	public function submitSendtoHospital() {
				
		if (input()->schedule != '') {
			$schedule = new CMS_Schedule(input()->schedule);
			//$atHospitalRecord = $schedule->atHospitalRecord();
			$facility = new CMS_Facility($schedule->facility);
			
			if ($atHospitalRecord == '') {
/*
				$atHospitalRecord = CMS_Schedule_Hospital::generate();
				if ($atHospitalRecord->alreadyExists($schedule->id) == true) {
					feedback()->error($schedule->getPatient()->fullName() . " has already been sent to the hospital.");
					$this->redirect($SITE_URL . "/?page=coord&action=trackHospitalVisits");
				}
*/
				$scheduleHospital = CMS_Schedule_Hospital::generate();
				if ($scheduleHospital->alreadyExists($schedule->id) == true) {
					feedback()->error($schedule->getPatient()->fullName() . " has already been sent to the hospital.");
					$this->redirect($SITE_URL . "/?page=coord&action=trackHospitalVisits");
				}
			} 
			
			if ($schedule->valid() == false) {
				feedback()->error("Invalid schedule record specified.");
				$this->redirect(auth()->getRecord()->homeURL());				
			}
			if (input()->id == '') {
				$atHospitalRecord = new CMS_Schedule_Hospital;
				$atHospitalRecord->datetime_created = datetime();
				$atHospitalRecord->discharge_nurse = auth()->getRecord()->id;  // change this to the DoN of the facility
				$isNew = true;
			} else {
				$atHospitalRecord = new CMS_Schedule_Hospital(input()->id);
				$obj_before = clone $atHospitalRecord;
				if ($atHospitalRecord->alreadyExists($schedule->id) == true) {
					feedback()->error($schedule->getPatient()->fullName() . " has already been sent to the hospital.");
					$this->redirect($SITE_URL . "/?page=coord&action=trackHospitalVisits");
				} elseif ($atHospitalRecord->id != $schedule->atHospitalRecord()->id) {
					feedback()->error("A mismatch occured. Please try again.");
					$this->redirect(auth()->getRecord()->homeURL());				
				}
				$isNew = false;
			}
			$atHospitalRecord->datetime_updated = datetime();
						
			try {
				
				// if datetime_sent is empty throw an error
				if (input()->datetime_sent == '') {
					feedback()->error("You must indicate when the patient was sent to the hospital. Please try again.");
					$this->redirect();
				}
				
				// set $atHospitalRecord values
				$atHospitalRecord->schedule = $schedule->id;
				$atHospitalRecord->datetime_sent = datetime(strtotime(input()->datetime_sent));
				$atHospitalRecord->datetime_updated = datetime();
				if (input()->hospital != '') {
					$atHospitalRecord->hospital = input()->hospital;
				} else {
					feedback()->error("You must enter the hospital to which the patient was sent.");
					$this->redirect(SITE_URL . "/?page=facility&action=sendToHospital&schedule={$schedule->pubid}");
				}
				$atHospitalRecord->hospital_contact_name = input()->hospital_contact_name;
				$atHospitalRecord->hospital_contact_phone = input()->hospital_contact_phone;
				// $obj->icd9_id = input()->icd9;
				$atHospitalRecord->comment = input()->comment;
				$atHospitalRecord->scheduled_visit = input()->visit_type;
				if (input()->bedhold_offered == 1 ) {
					$atHospitalRecord->bedhold_offered = 1;
				} else {
					$atHospitalRecord->bedhold_offered = 0;
				}

				if (input()->flag_readmission) {
					$schedule->flag_readmission = input()->flag_readmission;
				}
				 
				
				/*
				 * If patient is a direct_admit need to discharge the patient at time sent
				 */
				
				if (input()->direct_admit == 1) {
					$isDirectAdmit = true;
					if (input()->bedhold_offered == 1) {
						$atHospitalRecord->bedhold_offered = 1;
						if (input()->fdatetime_bedhold_end != '') {
							$bhd = datetime(strtotime(input()->datetime_bedhold_end));
							$schedule->datetime_discharge_bedhold_end = $bhd;
							$schedule->discharge_to = "Discharge to Hospital (Bed Hold)";
						} 
						
					} else {
						$schedule->discharge_to = "Discharge to Hospital";
					}
					
					$schedule->datetime_discharge = date("Y-m-d", strtotime(input()->datetime_sent));
					
					if ($schedule->datetime_discharge_bedhold_end != '') {
						if (strtotime($schedule->datetime_discharge_bedhold_end) < strtotime($schedule->datetime_discharge)) {
							feedback()->error("Oops! It looks like you entered a bed-hold end date in the past. Cannot proceed until this is corrected.");
							$this->redirect(SITE_URL . "/?page=facility&action=sendToHospital&schedule={$schedule->pubid}");
						}
					}
					
					$atHospitalRecord->was_admitted = 1;
					$atHospitalRecord->is_complete = 0;
					$atHospitalRecord->scheduled_visit = input()->visit_type;
					$atHospitalRecord->datetime_returned = NULL;
					
					// save schedule and object
					$schedule->save();
					$atHospitalRecord->save();

					feedback()->conf("The patient has been directly admitted to the hospital and discharged " . date("m/d/Y g:i a", strtotime($schedule->datetime_discharge)));
					$this->redirect($SITE_URL . "/?page=facility&action=census");
					
					/*
					 *
					 ******** This is not generating an email ******/
					CMS_Notify_Event::trigger("send_to_hospital_direct_admit", $schedule, $atHospitalRecord, $facility); 
				}

				if (input()->affirm == '' && $atHospitalRecord->bedhold_offered == 1) {
					if (input()->datetime_bedhold_end == '') {
						feedback()->error("If the patient accepted a bed hold, you must enter the expiration date of the bed hold. Please try again.");
						$this->redirect();
					} if ($schedule->datetime_discharge_bedhold_end != '') {
						$schedule->datetime_discharge_bedhold_end = datetime(strtotime(input()->datetime_bedhold_end));
					} else {
						$atHospitalRecord->datetime_bedhold_end = datetime(strtotime(input()->datetime_bedhold_end));
					}
					// $schedule->datetime_discharge = datetime(strtotime(input()->datetime_bedhold_end));
					// $schedule->datetime_discharge_bedhold_end = datetime(strtotime(input()->datetime_bedhold_end));
					$extraFeedback = " If admitted to the hospital, this patient will be discharged from the facility with a bed-hold in effect until " . date("m/d/Y", strtotime(input()->datetime_bedhold_end)) . " at " . date("g:i a", strtotime(input()->datetime_bedhold_end)) . ".";
				} 

				$atHospitalRecord->datetime_prompt_update = datetime(strtotime("+23 hours"));
				$atHospitalRecord->save();



				/*
				 * If there is an accepted bed hold
				 */
				 
				if (input()->affirm == 'admitted' && $atHospitalRecord->bedhold_offered == 1) {
					if (input()->datetime_bedhold_end == '') {
						feedback()->error("You must provide a date and time for the bed-hold to expire.");
						$this->redirect();
					} elseif (input()->datetime_discharge == '') {
						feedback()->error("You must provide the date and time that the patient was discharged.");
						$this->redirect();
					} elseif (datetime(strtotime(input()->datetime_sent)) > datetime(strtotime(input()->datetime_discharge))) {
						feedback()->error("The time of discharge must be later than the time the patient was sent to the hospital.  Please try again.");
						$this->redirect(SITE_URL . "/?page=facility&action=sendToHospital&schedule={$schedule->pubid}");
					} elseif (datetime(strtotime(input()->datetime_discharge)) > datetime(strtotime(input()->datetime_bedhold_end))) {
						feedback()->error("The time of expiration for the bedhold must be later than the time the patient was discharged.  Please try again.");
						$this->redirect(SITE_URL . "/?page=facility&action=sendToHospital&schedule={$schedule->pubid}");
					} else {
						$atHospitalRecord->was_admitted = 1;
						$atHospitalRecord->is_complete = 0;
						$atHospitalRecord->bedhold_offered = 1;
						$atHospitalRecord->scheduled_visit = input()->visit_type;
						$atHospitalRecord->datetime_returned = NULL;
						$atHospitalRecord->save();

						$schedule->discharge_to = 'Discharge to Hospital (Bed Hold)';
						$schedule->datetime_discharge = datetime(strtotime(input()->datetime_discharge));
						$schedule->datetime_discharge_bedhold_end = datetime(strtotime(input()->datetime_bedhold_end));
						$schedule->save();
						feedback()->conf("The hospital visit has been updated. This patient has been scheduled for discharge at " .date("g:i a", strtotime(input()->datetime_discharge)) . ". Because this patient accepted a bed-hold, he/she will appear in yellow on the dashboard until " .date("m/d/Y", strtotime(input()->datetime_bedhold_end)) . ". You can modify the discharge date by clicking this patient's Wrench Menu and choosing Manage Discharge.");
						$this->redirect(SITE_URL . "/?page=facility&action=census&facility={$facility->pubid}");
					}
				} elseif (input()->affirm == 'admitted' && $atHospitalRecord->bedhold_offered == 0) {
					if (input()->datetime_discharge == '') {
						feedback()->error("You must provide a date/time for discharge.");
						$this->redirect(SITE_URL . "/?page=facility&action=census&facility={$facility->pubid}");
					} elseif (datetime(strtotime(input()->datetime_sent)) > datetime(strtotime(input()->datetime_discharge))) {
						feedback()->error("The time of discharge must be later than the time the patient was sent to the hospital.  Please try again.");
						$this->redirect(SITE_URL . "/?page=facility&action=sendToHospital&schedule={$schedule->pubid}");
					} else {
						$atHospitalRecord->was_admitted = 1;
						$atHospitalRecord->is_complete = 0;
						$atHospitalRecord->bedhold_offered = 0;
						$atHospitalRecord->datetime_returned = NULL;
						$atHospitalRecord->scheduled_visit = input()->visit_type;
						$atHospitalRecord->save();
						
						$schedule->discharge_to = 'Discharge to Hospital';
						$schedule->datetime_discharge = datetime(strtotime(input()->datetime_discharge));
						$schedule->datetime_discharge_bedhold_end = NULL;
						
						$schedule->save();
						feedback()->conf("The hospital visit has been updated. The patient has been scheduled for discharge on " . date("M j, Y", strtotime(input()->datetime_discharge)) . ".");
						$this->redirect(SITE_URL . "/?page=facility&action=census&facility={$facility->pubid}");
					}
				} elseif (input()->affirm == 'not-admitted') {
					$atHospitalRecord->was_admitted = 0;
					$atHospitalRecord->is_complete = 1;
					$atHospitalRecord->datetime_returned = NULL;
					$atHospitalRecord->stop_tracking_reason = "Not admitted to hospital";
					
					$atHospitalRecord->save();
					feedback()->conf("This hospital visit has been updated and the patient will no longer appear on the Return to Hospital page.");				
					$this->redirect();
				} /*
elseif(input()->affirm == 'discharged_home') {
					$atHospitalRecord->is_complete = 1;
					$atHospitalRecord->stop_tracking_reason = "Patient went home";
					$atHospitalRecord->save();
					feedback()->conf("This patient will no longer appear on the Return to Hospital page.");
					$this->redirect(SITE_URL . "/?page=coord&action=trackHospitalVisits&facility=" . $facility->pubid);
				} elseif(input()->affirm == 'discharged_other') {
					$atHospitalRecord->is_complete = 1;
					$atHospitalRecord->stop_tracking_reason = "Patient went to another location";
					$atHospitalRecord()->save();
					feedback()->conf("This patient will no longer appear on the Return to Hospital page.");
					$this->redirect(SITE_URL . "/?page=coord&action=trackHospitalVisits&facility=" . $facility->pubid);
				} elseif(input()->affirm == 'discharged_expired') {
					$atHospitalRecord->is_complete = 1;
					$atHospitalRecord->stop_tracking_reason = "Patient expired at the hospital";
					$atHospitalRecord()->save();
					feedback()->conf("This patient will no longer appear on the Return to Hospital page.");
					$this->redirect(SITE_URL . "/?page=coord&action=trackHospitalVisits&facility=" . $facility->pubid);
				}

*/

				else {
					$schedule->save();
					if ($isNew == true) {
						if ($isDirectAdmit == true) {
							CMS_Notify_Event::trigger("send_to_hospital_direct_admit", $schedule->getFacility(), $schedule, $atHospitalRecord);
						} else {
							CMS_Notify_Event::trigger("send_to_hospital_created", $schedule->getFacility(), $schedule, $atHospitalRecord);
						}
					} else {
						CMS_Notify_Event::trigger("send_to_hospital_updated", $schedule->getFacility(), $schedule, $obj_before, $atHospitalRecord);
					}
					feedback()->conf("Hospital visit has been saved to the system.{$extraFeedback}.");
					$this->redirect();
				}
			} catch (Exception $e) {
				feedback()->error("An error occurred while trying to save this hospital visit.");
				$this->redirect(SITE_URL . "/?page=facility&action=sendToHospital&schedule={$schedule->pubid}");
			}


		} else {
			feedback()->error("Invalid schedule record selected.");
			$this->redirect(auth()->getRecord()->homeURL());
		}
		
	}
	
	
	// public function sendToHospitalStatusUpdate() {
	// 	if (input()->schedule != '') {
	// 		$schedule = new CMS_Schedule(input()->schedule);
	// 		$atHospitalRecord = $schedule->atHospitalRecord();
	// 		$atHospitalRecord->datetime_updated = datetime();
			
	// 		if (input()->affirm == 'admitted-bed-hold') {
	// 			if (input()->datetime_bedhold_end == '') {
	// 				feedback()->error("You must provide a date/time for bed-hold expiration.");
	// 				$this->redirect();
	// 			} else {
	// 				$atHospitalRecord->was_admitted = 1;
	// 				$atHospitalRecord->is_complete = 0;
	// 				$atHospitalRecord->datetime_returned = NULL;
	// 				$atHospitalRecord->save();
					
	// 				$schedule->discharge_to = 'Discharge to Hospital (Bed Hold)';
	// 				$schedule->datetime_discharge = datetime(strtotime(input()->datetime_discharge));
	// 				$schedule->datetime_discharge_bedhold_end = datetime(strtotime(input()->datetime_bedhold_end));
	// 				$schedule->save();
	// 				feedback()->conf("Hospital visit has been updated and will continue to be tracked in the Holding Area. This patient has been scheduled for discharge at " .date("g:i a", strtotime(input()->datetime_discharge)) . "Because this patient accepted a bed-hold, he/she will continue to appear as an active patient until " .date("m/d/Y", strtotime(input()->datetime_bedhold_end)) . ".You can modify the discharge date by clicking this patient's Wrench Menu and choosing Manage Discharge.");
	// 			}
	// 		}
			
	// 		elseif (input()->affirm == 'admitted') {
	// 			if (input()->datetime_discharge == '') {
	// 				feedback()->error("You must provide a date/time for discharge.");
	// 				$this->redirect();
	// 			} else {
	// 				$atHospitalRecord->was_admitted = 1;
	// 				$atHospitalRecord->is_complete = 0;
	// 				$atHospitalRecord->datetime_returned = NULL;
	// 				$atHospitalRecord->save();
					
	// 				$schedule->datetime_discharge = datetime(strtotime(input()->datetime_discharge));
	// 				$schedule->datetime_discharge_bedhold_end = NULL;
	// 				$schedule->save();
	// 				feedback()->conf("Hospital visit has been updated and will continue to be tracked in the Holding Area. Patient has been scheduled for discharge at" . date("g:i a", strtotime(input()->datetime_discharge)) . ".");
	// 			}
	// 		}
			
	// 		elseif (input()->affirm == 'not-admitted') {
	// 			$atHospitalRecord->was_admitted = 0;
	// 			$atHospitalRecord->is_complete = 1;
	// 			$atHospitalRecord->datetime_returned = NULL;
	// 			$atHospitalRecord->stop_tracking_reason = "Not admitted to hospital.";
	// 			$atHospitalRecord->save();
	// 			feedback()->conf("Hospital visit has been updated and will no longer be tracked in the Holding Area.");				
	// 		}
			
	// 		elseif (input()->affirm == 'admitted-came-back') {
	// 			if (input()->datetime_returned == '') {
	// 				feedback()->error("You must provide a date/time that the patient returned.");
	// 				$this->redirect();
	// 			} else {
	// 				$atHospitalRecord->was_admitted = 1;
	// 				$atHospitalRecord->is_complete = 1;
	// 				$atHospitalRecord->datetime_returned = datetime(strtotime(input()->datetime_returned));
	// 				$atHospitalRecord->stop_tracking_reason = "Not admitted to hospital.";
	// 				$atHospitalRecord->save();
	// 				feedback()->conf("Hospital visit has been updated and will no longer be tracked in the Holding Area.");
	// 			}
	// 		}
			
	// 		elseif (input()->affirm = 'stop-tracking-other') {
	// 			$atHospitalRecord->was_admitted = NULL;
	// 			$atHospitalRecord->is_complete = 1;
	// 			$atHospitalRecord->datetime_returned = NULL;
	// 			$atHospitalRecord->stop_tracking_reason = input()->stop_tracking_reason;
	// 			$atHospitalRecord->save();
	// 			feedback()->conf("Hospital visit has been updated and will no longer be tracked in the Holding Area.");								
	// 		}
	
	// 		else {
	// 			feedback()->error("Invalid selection made.");
	// 			$this->redirect();
	// 		}
			
	// 		if (! feedback()->wasError() ) {
	// 			$confMsg = current(feedback()->getVals("conf"));
	// 			CMS_Notify_Event::trigger("send_to_hospital_status_updated", $schedule->getFacility(), $schedule, $atHospitalRecord, $confMsg);
	// 		}
	// 		$this->redirect();			
		
	// 	} else {
	// 		feedback()->error("Invalid schedule record selected.");
	// 		$this->redirect();			
	// 	}
	// }

	public function stopTracking() {
		if (input()->stop_tracking_reason != '') {
			feedback()->conf("It worked!");
			$this->redirect(SITE_URL . "/?page=coord");
		} else {
			feedback()->error("It didn't work.");
			$this->redirect(SITE_URL . "/?page=coord");
		}
		// if (input()->schedule != '') {
		// 	$schedule = new CMS_Schedule(input()->schedule);
		// 	$atHospitalRecord = $schedule->atHospitalRecord();
		// 	$atHospitalRecord->datetime_updated = datetime();
		// 	$atHospitalRecord->is_complete = 1;
		// 	if ($schedule->valid() == false) {
		// 		feedback()->error("Invalid record specified.");
		// 		$this->redirect(auth()->getRecord()->homeURL());
		// 	}
		// 	$obj = new CMS_Schedule_Hospital(input()->id);
		// 	if ($obj->id != $schedule->atHospitalRecord()->id) {
		// 		feedback()->error("A mismatch occured.  Please try again.");
		// 		$this->redirect(auth()->getRecord()->homeURL());
		// 	}

		// 	if (input()->stop_tracking_reason == '') {
		// 	feedback()->error("Please select a valid reason to stop tracking this patient and try again.");
		// 	$this->redirect();
		// 	} else {
		// 		$atHospitalRecord->stop_tracking_reason = input()->stop_tracking_reason;
		// 	}

		// 	try {
		// 		$atHospitalRecord->save();
		// 	} catch (Exception $e) {
		// 		feedback()->error("An error occurred while trying to stop tracking this hospital visit.");
		// 		$this->redirect(SITE_URL . "/?page=coord&action=trackHospitalVisits&facility={$schedule->facility}");
		// 	}
		// } else {
		// 	feedback()->error("Invalid record selected.");
		// 	$this->redirect(auth()->getRecord()->homeURL());
		// }

		$retval = array();
			
		if (feedback()->wasError()) {
			$retval["status"] = false;
			$retval["errors"] = feedback()->getVals("error");
			feedback()->clear();
		} else {
			$retval["status"] = true;
		}
		
		json_return($retval);
		

	}
	
		
	public function discharge() {
	
		// get list of facilities for drop-down
		$_facilities = auth()->getRecord()->getFacilities();
		smarty()->assign('facilities', $_facilities);
		
		// get list of all facilities for transfer
		$transfer = new CMS_Facility();
		$transferFacilities = $transfer->findAll();
		smarty()->assign('transferFacilities', $transferFacilities);
		
		if (input()->schedule != '') {
			$schedule = new CMS_Schedule(input()->schedule);
			if ($schedule->datetime_discharge == '') {
				$datetime = $GLOBALS["datetimeDischargeDefault"];
			} else {
				$datetime = $schedule->datetime_discharge;
			}
			if ($schedule->facility != '') {
				$facility = $schedule->related("facility");
			}
			
			if ($schedule->datetime_discharge_bedhold_end != '') {
				$datetime_bedhold_default = date("m/d/Y", strtotime($schedule->datetime_discharge_bedhold_end));
			} else {
				$datetime_bedhold_default = date("m/d/Y", strtotime("+1 day", strtotime($datetime))) . " 11:00 am";
			}
			$rooms = array();
			
			if ($schedule->discharge_to == 'Transfer to another AHC facility' && $schedule->discharge_transfer_schedule != '') {
				$transfer_schedule = $schedule->getTransferSchedule();
				$datetime_transfer_default = date("m/d/Y g:i a", strtotime($transfer_schedule->datetime_admit));		
			} else {
				$datetime_transfer_default = date("m/d/Y g:i a", strtotime("+1 hour", strtotime($datetime)));
			}

		} else {
		
			if (input()->facility != '') {
				$facility = new CMS_Facility(input()->facility);
			}
		
			if (input()->facility != '' && $facility->valid()) {
				if (input()->datetime == '') {
					$datetime = $GLOBALS["datetimeDischargeDefault"];
				} else {
					$datetime = input()->datetime;
				}
			}
			$rooms = CMS_Room::fetchScheduledByFacility($facility->id, datetime());
			
			$datetime_transfer_default = date("m/d/Y g:i a", strtotime("+1 hour", strtotime("now")));
		}
		
		// 2011-09-07 - introduction of new 'schedule_hospital' / holding area functionality deprecates
		// 'discharge to hospital' option, but we need to keep it in the enum() definition so that old records
		// remain intact.
		$dischargeToOptions = array_filter(db()->enumOptions("schedule", "discharge_to"),
										   function($v) {
												return ($v != 'Discharge to Hospital' && $v != 'Discharge to Hospital (Bed Hold)');
											}
										);

		$dischargeDispositionOptions = array_filter(db()->enumOptions("schedule", "discharge_disposition"));
		
		$serviceDisposition = array_filter(db()->enumOptions("schedule", "service_disposition"));

		
		smarty()->assignByRef("schedule", $schedule);
		smarty()->assignByRef("rooms", $rooms);
		smarty()->assignByRef("facility", $facility);
		smarty()->assign("datetime", $datetime);
		smarty()->assign("dischargeToOptions", $dischargeToOptions);
		smarty()->assign("dischargeDispositionOptions", $dischargeDispositionOptions);
		smarty()->assign("serviceDisposition", $serviceDisposition);
		smarty()->assign("datetime_bedhold_default", $datetime_bedhold_default);		
		smarty()->assign("datetime_transfer_default", $datetime_transfer_default);
		smarty()->assign("transfer_schedule", $transfer_schedule);
	}
	
	
	
	
	
	/*
	 * -------------------------------------------------------------
	 *  SUBMIT DISCHARGE REQUEST
	 * -------------------------------------------------------------
	 * 
	 * Note: This function was re-factored on 2013-07-16 by kwh due to a change in the way
	 * discharged are scheduled.  New drag and drop functionality has been implemented to create
	 * a "quick" discharge.  The additional discharge details are entered either just prior to a 
	 * patient actually discharging or after the actual discharge.  These details are used to get
	 * accurate reports for patients following their discharge.
	 *
	 */
	
	public function submitDischargeRequest() {
		$facility = new CMS_Facility(input()->facility);
		$datetime = datetime(strtotime(input()->datetime));
		$schedule = new CMS_Schedule(input()->schedule);
		$patient = new CMS_Patient_Admit($schedule->patient_admit);
				
		// validate facility
		if ($facility->valid() == false) {
			feedback()->error("Invalid facility selected.");
		}
		// validate schedule
		if ($schedule->valid() == false) {
			feedback()->error("Invalid scheduling selected.");
		}
		
		/*
		 * VALIDATE CONDITIONAL FIELDS BASED ON DISCHARGE TO SELECTION
		 *
		 * Note: Need to validate conditional inputs based on the discharge_to selection as different fields
		 * will appear for different selected values.
		 *
\		 */

		if (input()->datetime == '') {
			feedback()->error("You must select the date and time of discharge.");
			$this->redirect();
		}
		
		$dischargeToOptions = db()->enumOptions("schedule", "discharge_to");
		if (input()->discharge_to == '' || ! in_array(input()->discharge_to, $dischargeToOptions)) {
			feedback()->error("You must select a discharge type.");
			$this->redirect(SITE_URL . "/?page=facility&action=discharge_details&schedule={$schedule->pubid}");
		} else {
			$discharge_to = input()->discharge_to;
			
			if ($discharge_to == "General Discharge") {
				if (input()->discharge_disposition == '') {
					feedback()->error("You must select a discharge disposition.");
					$this->redirect(SITE_URL . "/?page=facility&action=discharge_details&schedule={$schedule->pubid}");
				}
				
				
				if (input()->service_disposition == '' && input()->discharge_disposition != 'Hospice') {
					feedback()->error("You must select a service disposition.");
					$this->redirect(SITE_URL . "/?page=facility&action=discharge_details&schedule={$schedule->pubid}");
				}
			}
			
			
			if ($discharge_to == "Transfer to another AHC facility") {
				$transfer_facility = new CMS_Facility(input()->transfer_facility);
				if ($schedule->transfer_request != 1) {
					$schedule->transfer_request = 1;
				}
				$schedule->discharge_location_id = input()->discharge_location_id;
				$schedule->transfer_to_facility = $transfer_facility->id;
				$schedule->transfer_from_facility = $facility->id;
				$schedule->transfer_comment = input()->discharge_comment;
				
				if ($transfer_facility->valid() == false) {
					feedback()->error("You must select a valid transfer facility.");
				}
				$datetime_discharge_transfer = date('Y-m-d 13:00:00', strtotime(input()->datetime));
			}
			
			
			if ($discharge_to == "Transfer to other facility") {
				if (input()->discharge_location_id == '') {
					feedback()->error("You must enter a discharge facility name.");
					$this->redirect(SITE_URL . "/?page=facility&action=discharge_details&schedule={$schedule->pubid}");
				} else {
					$schedule->discharge_location_id = input()->discharge_location_id;
				}
				
				if (input()->service_disposition == '') {
					feedback()->error("You must select a service disposition.");
					$this->redirect(SITE_URL . "/?page=facility&action=discharge_details&schedule={$schedule->pubid}");
				}
			} 
			
			if ($discharge_to == "In-Patient Hospice") {
				if (input()->discharge_location_id == '') {
					feedback()->error("You must enter a discharge facility name.");
					$this->redirect(SITE_URL . "/?page=facility&action=discharge_details&schedule={$schedule->pubid}");
				} else {
					$schedule->discharge_location_id = input()->discharge_location_id;
				}
			}
			
		}
		
		
		
		/*
		 * VALIDATE CONDITIONAL FIELDS BASED ON DISCHARGE DISPOSITION SELECTION
		 *
		 * Note: Fields will be validated depending on the selected value of the patients'
		 * selected discharge disposition.
		 *
		 */		
		
		if (input()->discharge_disposition == "Home") {
			if (input()->service_disposition == '') {
				feedback()->error("You must select a service disposition.");
				$this->redirect(SITE_URL . "/?page=facility&action=discharge_details&schedule={$schedule->pubid}");
			}
		}
		
		if (input()->discharge_disposition == "Group Home" || input()->discharge_disposition == "Assisted Living" || input()->discharge_disposition == "SNF") {
			if (input()->discharge_location_id == '') {
				feedback()->error("You must enter a discharge facility name.");
				$this->redirect(SITE_URL . "/?page=facility&action=discharge_details&schedule={$schedule->pubid}");
			} else {
				$schedule->discharge_location_id = input()->discharge_location_id;
			}
			
			if (input()->service_disposition == '') {
				feedback()->error("You must select a service disposition.");
				$this->redirect(SITE_URL . "/?page=facility&action=discharge_details&schedule={$schedule->pubid}");
			}
		}
		
		if (input()->discharge_disposition == "Hospice") {
			if (input()->discharge_location_id == '') {
				feedback()->error("You must enter a discharge facility name.");
				$this->redirect(SITE_URL . "/?page=facility&action=discharge_details&schedule={$schedule->pubid}");
			} else {
				$schedule->discharge_location_id = input()->discharge_location_id;
			}
		}
		
				
		
		/*
		 * VALIDATE CONDITIONAL FIELDS BASED ON SERVICE DISPOSITION SELECTION
		 *
		 * Note: 
		 *
		 */
		
		if (input()->service_disposition == "Other Home Health") {
			if (input()->home_health_org == '') {
				feedback()->error("You must enter the name of the home health agency.");
				$this->redirect(SITE_URL . "/?page=facility&action=discharge_details&schedule={$schedule->pubid}");
			} else {
				$schedule->home_health_id = input()->home_health_org;
			}
		}
						
			
				
		/*
		 * VALIDATE DISCHARGE ADDRESS
		 *
		 * Note: 
		 *
		 */
		 
		if (input()->discharge_address_checkbox == 1) {

			// Address
			if (input()->discharge_address == '') {
				feedback()->error("Please enter the discharge Street Address.");
				$this->redirect();
			} else {
				$schedule->discharge_address = input()->discharge_address;
			}

			// City
			if (input()->discharge_city == '') {
				feedback()->error("Please enter the discharge City.");
				$this->redirect();
			} else {
				$schedule->discharge_city = input()->discharge_city;
			}

			// State
			if (input()->discharge_state == '') {
				feedback()->error("Please enter the discharge State.");
				$this->redirect();
			} else {
				$validate = Validate::is_USAState(input()->discharge_state);
				if ($validate->success() == false) {
					feedback()->error("Please correct the State.") . $validate->message();
				} else {
					$schedule->discharge_state = input()->discharge_state;
				}
			}

			// Zip
			if (input()->discharge_zip == '') {
				feedback()->error("Please enter the discharge Zip.");
				$this->redirect();
			} else {
				$validate = Validate::is_zipcode(input()->discharge_zip);
				if ($validate->success() == false) {
					feedback()->error("Please correct the Zip.");
				} else {
					$schedule->discharge_zip = input()->discharge_zip;
				}
			}

			// Phone
			if (input()->discharge_phone != '') {
				$validate = Validate::is_phone(input()->discharge_phone);
				if ($validate->success() == false) {
					feedback()->error("Please correct the Phone number.");
				} else {
					$schedule->discharge_phone = input()->discharge_phone;
				}
			}
		}
				
		if (input()->flag_readmission) {
			$schedule->flag_readmission = input()->flag_readmission;
		}
		
		
		
		// breakpoint
		if (feedback()->wasError()) {
			$this->redirect(auth()->getRecord()->homeURL());
		}
		
		$transfer = $schedule->getTransferSchedule();
		
		$schedule->discharge_to = input()->discharge_to;
		$schedule->discharge_disposition = input()->discharge_disposition;
		$schedule->service_disposition = input()->service_disposition;
		$schedule->datetime_discharge = date('Y-m-d H:i:s', strtotime(input()->datetime));
		$schedule->discharge_comment = input()->discharge_comment;
		$schedule->discharge_datetime_modified = date('Y-m-d H:i:s', strtotime('now'));
		$schedule->discharge_site_user_modified = auth()->getRecord()->id;
		$schedule->status = "Discharged";
				
		
		// If this is a transfer need to discharge the patient and then schedule them for an admission at the new location
			
		try {
		
			$schedule->save();
			
			 if ($discharge_to == 'Transfer to another AHC facility') {
			        // create new transfer schedule record if we didn't already find one
			        if ($transfer == false) {
		                $transfer = new CMS_Schedule;
		                // default status if new transfer
		                $transfer->status = 'Under Consideration';
		                
		                // Get current patient_admit record
		                $patient = new CMS_Patient_Admit($schedule->patient_admit);
		                
		                $new_record = CMS_Patient_Admit::cloneTransferPatient($patient->id, $facility->id);
		                
		                $new_record->save();
			        } else {
			                if ($facility->id != $transfer->facility) {
			                        // reset status if you're changing the facility of an existing transfer
			                        $transfer->status = 'Under Consideration';                              
			                }
			        }
			        $transfer->facility = $transfer_facility->id;
			        $transfer->patient_admit = $new_record->id;
			        $transfer->datetime_admit = $datetime_discharge_transfer;
			        
					
			        
			        $transfer->save();
			        			        
			        // save transfer record association to this schedule
			        $schedule->discharge_transfer_schedule = $transfer->id;
			        $schedule->save();
			        
			        // send notification email
/*
			        CMS_Notify_Event::trigger("facility_transfer_inbound", $facility, $schedule, $transfer);
			        CMS_Notify_Event::trigger("facility_transfer_outbound", $transfer_facility, $schedule, $transfer);
*/
			        
			} else {
			        // delete any existing transfer schedule record; it's no longer needed
			        if ($transfer != false) {
			                CMS_Schedule::delete($transfer);
			        }
			        // clear from original schedule
			        $schedule->discharge_transfer_schedule = '';
			        $schedule->save();
			}
			feedback()->conf("Discharge has been scheduled.");
			$this->redirect(SITE_URL . "/?page=facility&id={$facility->pubid}&action=manage_discharges");

			
			// send notification
			CMS_Notify_Event::trigger("discharge_scheduled", $schedule);
			feedback()->conf("The discharge is complete for {$patient->first_name} {$patient->last_name}.");
			$this->redirect(SITE_URL . "/?page=facility&action=manage_discharges&facility={$facility->pubid}");
		} catch (ORMException $e) {
			feedback()->error("Unable to save discharge request.");
			$this->redirect(SITE_URL . "/?page=facility&action=discharge_details&schedule={$schedule->pubid}");
		}
		
	}
	
	
	
	public function schedule_discharges() {
		
		// get list of facilities for drop-down
		$_facilities = auth()->getRecord()->getFacilities();
		smarty()->assign('facilities', $_facilities);
		
		if (input()->facility == '') {
			$user = auth()->getRecord();
			$facility = new CMS_Facility($user->default_facility);
		} else {
			$facility = new CMS_Facility(input()->facility);
			
		}
		
		smarty()->assign('facility', $facility);
		
		
		// Optionally use a different week
		if (input()->weekSeed != '') {
			$weekSeed = input()->weekSeed;
			if (Validate::is_american_date($weekSeed)->success() || Validate::is_standard_date($weekSeed)->success()) {
				//$week = Calendar::getDateSequence($weekSeed, 7);
				$week = Calendar::getWeek($weekSeed);
			}
		} else {
		// Default to "this week"
			$weekSeed = date("Y-m-d");
			//$week = Calendar::getDateSequence($weekSeed, 7);
			$week = Calendar::getWeek($weekSeed);		
		}
		
		// Now grab the next week (use 6 days so that this weeks' last day appears as first)
		$nextWeekSeed = date("Y-m-d", strtotime("+7 days", strtotime($week[0])));
		$previousWeekSeed = date("Y-m-d", strtotime("-7 days", strtotime($week[0])));
		
		smarty()->assign(array(
			"weekSeed" => $weekSeed,
			"prevWeekSeed" => $previousWeekSeed,
			"nextWeekSeed" => $nextWeekSeed,
			"weekStart" => date('Y-m-d', strtotime($weekSeed))
		));

				
		$datetime = datetime();
		
			
		// Get all patients currently at the facility for the current week
		$currentPatients = CMS_Facility::fetchCurrentCensus($facility->id, $datetime);
		
		$current = array();
		$discharged = array();
		foreach ($currentPatients as $idx => $c) {
			if ($c->datetime_discharge == '') {
				$current[] = $c;
			} else {
				$discharged[] = $c;
			}
		}
		
		// split discharged patient up by date
		$dischargedByDate = array();
		foreach ($discharged as $d) {
			$date = date("Y-m-d", strtotime($d->datetime_discharge));
			if (! isset($dischargedByDate[$date])) {
				$dischargedByDate[$date] = array();
			}
			$dischargedByDate[$date][] = $d;
		}
		
		smarty()->assign('current', $current);
		smarty()->assign('discharged', $dischargedByDate);
		smarty()->assign('week', $week);

	}
	
	
	public function manage_discharges() {
		// get list of facilities for drop-down
		$_facilities = auth()->getRecord()->getFacilities();
		smarty()->assign('facilities', $_facilities);
		
		if (input()->facility == '') {
			$user = auth()->getRecord();
			$facility = new CMS_Facility($user->default_facility);
		} else {
			$facility = new CMS_Facility(input()->facility);
			
		}
		
		smarty()->assign('facility', $facility);
		
		/*
		 * GET ALL CURRENT DISCHARGES
		 *
		 * Note: Get all incomplete discharges.  Going forward this will be any schedule record with a datetime_discharge set
		 * and a status of Approved.  Discharges which are complete will get a status of Discharged
		 *
		 */
		 
		$discharges = CMS_Schedule::fetchCurrentDischarges($facility->id);
		
		smarty()->assign('discharges', $discharges);
		 
	}
	
	
	public function discharge_details() {
		if (input()->schedule == '') {
			feedback()->error("Invalid patient schedule was selected.");
			$this->redirect(SITE_URL . "/?page=facility&action=manage_discharges");
		} else {
			$schedule = new CMS_Schedule(input()->schedule);
			$patient = new CMS_Patient_Admit($schedule->patient_admit);
			$facility = new CMS_Facility($schedule->facility);
	
		}
				
	
		// if there is an existing hospital stay then redirect to the manage hospital visit page
		$hospitalStay = $schedule->atHospitalRecord();
		
		if (!empty ($hospitalStay)) {
			$this->redirect(SITE_URL . "/?page=facility&action=sendToHospital&schedule={$schedule->pubid}");
		}
		
				
		// get list of facilities for drop-down
		$_facilities = auth()->getRecord()->getFacilities();
		smarty()->assign('facilities', $_facilities);
		
		$obj = new CMS_Site_User;
		$user = auth()->getRecord();
		$userRoles = $obj->getRoles($user->id);		
		smarty()->assign('userRoles', $userRoles);
		
		// get list of all facilities for transfer
		$transfer = new CMS_Facility();
		$transferFacilities = $transfer->findAll();
		smarty()->assign('transferFacilities', $transferFacilities);

		
		$dischargeToOptions = array_filter(db()->enumOptions("schedule", "discharge_to"),
		   function($v) {
				return ($v != 'Discharge to Hospital' && $v != 'Discharge to Hospital (Bed Hold)');
			}
		);

		$dischargeDispositionOptions = array_filter(db()->enumOptions("schedule", "discharge_disposition"));
		
		$serviceDisposition = array_filter(db()->enumOptions("schedule", "service_disposition"));

		smarty()->assign('facility', $facility);
		smarty()->assign('schedule', $schedule);
		smarty()->assign('patient', $patient);
		smarty()->assign("dischargeToOptions", $dischargeToOptions);
		smarty()->assign("dischargeDispositionOptions", $dischargeDispositionOptions);
		smarty()->assign("serviceDisposition", $serviceDisposition);

	}
	
	
	public function save_discharge() {	
		if (input()->pubid != '') {
			$pubid = input()->pubid;
		} else {
			return false;
		}
			
		if (input()->date != '') {
			$date = input()->date;
		} else {
			return false;
		}	
							
		$schedule = new CMS_Schedule($pubid);
		$schedule->datetime_discharge = date('Y-m-d 11:00:00', strtotime($date));	
		
		
		$schedule->save();
		
		return true;
	}
	
	
	
	public function clear_discharge() {
		if (input()->pubid != '') {
			$pubid = input()->pubid;
		} else {
			return false;
		}
		
		$schedule = new CMS_Schedule($pubid);
		$schedule->datetime_discharge = null;
		$schedule->discharge_to = null;
		$schedule->discharge_disposition = null;
		$schedule->services_disposition = null;
		$schedule->discharge_location_id = null;
		$schedule->discharge_address = null;
		$schedule->discharge_city = null;
		$schedule->discharge_state = null;
		$schedule->discharge_zip = null;
		$schedule->discharge_phone = null;
		$schedule->save();
		
		return true;
	}
	
	
	
	public function cancelDischarge() {
		$schedule = new CMS_Schedule(input()->schedule);
		if (! $schedule->valid() ) {
			feedback()->error("Invalid discharge selected.");
			$this->redirect(auth()->getRecord()->homeURL());
		}
		
		$schedule->datetime_discharge = NULL;
		$schedule->discharge_to = NULL;
		$schedule->datetime_discharge_bedhold_end = NULL;
		$schedule->discharge_comment = NULL;
		$schedule->discharge_transfer_schedule = NULL;
		$schedule->status = 'Approved';
		$schedule->save();
		
		feedback()->conf("Discharge for {$schedule->getPatient()->fullName()} has been cancelled.");
		$this->redirect(SITE_URL . "/?page=facility&action=manage_discharges&facility={$schedule->getFacility()->pubid}");
		
	}
	
	public function census() {
		if (input()->facility != '') {
			$facility = new CMS_Facility(input()->facility);
		} else {
		$defaultFacility = auth()->getRecord()->getDefaultFacility();
		$facility = new CMS_Facility($defaultFacility->id);
		}

		if ($facility->valid() == false) {
			$facility = '';
			// feedback()->error("Invalid facility selected.");
			// $this->redirect(auth()->getRecord()->homeURL());
		}
		if (input()->datetime == '') {
			$datetime = datetime();
		} else {
			$datetime = datetime(strtotime(input()->datetime));
		}
		
		if (input()->type == '') {
			$type = 'all';
		} else {
			$type = input()->type;
		}
		if ($facility->short_term) {  // if a facility only offers short term show empty and full rooms
			if ($type == 'all') {	
				$empty = CMS_Room::fetchEmptyByFacility($facility->id, $datetime);
				$scheduled = CMS_Room::fetchScheduledByFacility($facility->id, $datetime);
				$rooms = CMS_Room::mergeFetchedRooms($empty, $scheduled);
				$emptyRooms = count($empty);
				$assignedRooms = count($scheduled);
				smarty()->assignByRef("empty", $empty);
			} elseif ($type == 'empty') {
				$rooms = CMS_Room::fetchEmptyByFacility($facility->id, $datetime);
				$emptyRooms = count($rooms);
			} elseif ($type == 'scheduled') {
				$rooms = CMS_Room::fetchScheduledByFacility($facility->id, $datetime);
				$assignedRooms = count($rooms);
			}
			

		} else {
			if ($type == 'all') { // otherwise show rooms by long-term or short-term patients
				$patients = CMS_Room::fetchRooms($facility->id, $datetime);
			} elseif ($type) {
				// fetch only rooms that have long term patients
				$patients = CMS_Room::fetchRooms($facility->id, $datetime, $type);
			}
			$empty = CMS_Room::fetchEmptyByFacility($facility->id, $datetime);
			$rooms = CMS_Room::mergeFetchedRooms($empty, $patients);
			$assignedRooms = count($patients);
			$emptyRooms = count($empty);
			smarty()->assignByRef("empty", $empty);
		}
					
		// get total number of rooms for the facility
		$totalRooms = CMS_Room::fetchRoomCount($facility->id);
										
		foreach ($totalRooms as $roomCount) {
			$numOfRooms = $roomCount->roomCount;
		}

		// get total number of patients on census per physician
		$physicians = array();
		foreach ($rooms as $room) {
			if ($room->physician_id != '') {
				$physicians[$room->physician_id] += count($room->physician_id);
				$physicianTotal += count($room->physician_id);
			}
		}		
				
		
		/*
		 * CALCULATE AVERAGE DAILY CENSUS FOR THE CURRENT MONTH
		 *
		 * Note: Get ADC for each day of the current month and then divide by the number 
		 * of days.
		 *
		 * UPDATE: The ADC will now be calculated nightly with a script and the value will
		 * be stored in the census_data_month table
		 *
		 */
		
		
		$census = CMS_Census_Data_Month::fetchCurrentCensus($facility->id);
		
		$adc = $census[0]->adc;
		$adcGoal = $census[0]->goal;

/*
		$date = date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y")));
				
		$dailyCensus = array();
		if (date('d', strtotime('now')) == 1) {
		 	 $query_date = 1;
		 } else {
		 	$query_date = date('d', strtotime('now - 1 day'));
		 }
				
		for ($i = 0; $i < $query_date; $i++) {
			if ($i == 0) {
				$date = $date;
			} else {
				$date = date('Y-m-d', strtotime($date . " + 1 days"));
			}
			$dailyCensus[] = CMS_Schedule::getADC($facility->id, $date);
		}	
																	
		foreach ($dailyCensus as $daily) {
			foreach ($daily as $d) {
				$censusTotal += $d->census;
				$adcGoal = $d->goal;
			}
		}
				
		$adc = round ($censusTotal / $i, 2);
*/
		
		
		
		
		/*
		 * CALCULATE LENGTH OF STAY FOR THE CURRENT MONTH
		 *
		 * Note: Get the number of discharges for each day of the month with the 
		 * datetime_admit and datetime_discharge for each patient.  Find the number
		 * of days between the two, total the days for all patients, and then divide
		 * by the number of patients discharged in the timeframe.
		 *
		 * UPDATE:  Need to get the admission date for each patient discharged after the first 
		 * day of the month, and then divide by the total number of discharges month-to-date
		 *
		 */
		 
		$date_start = date('Y-m-d 00:00:01', strtotime('first day of this month'));	 
	 	$date_end = date('Y-m-d 23:59:59', strtotime('now'));
				 
		 
		 $obj = new CMS_Schedule();
		 $discharges = $obj->fetchLosDischarges($date_start, $date_end, $facility->id, "Medicare");		 
		 
		$lengthByPatient = array();
		foreach ($discharges as $k => $d) {
			$lengthByPatient[$k] += $this->LoS($d->datetime_discharge, $d->datetime_admit);
		}
				
		$numOfDischarges = count ($lengthByPatient);
		
		$totalNumOfDays = array_sum($lengthByPatient);
		
		$avgLength = round ($totalNumOfDays/$numOfDischarges, 2);
						 
		 
		smarty()->assign('avgLength', $avgLength);
		smarty()->assign('adcGoal', $adcGoal);
		smarty()->assign("numOfRooms", $numOfRooms);
		smarty()->assign("adc", $adc);		
		smarty()->assign("physicians", $physicians);
		smarty()->assign("physicianTotal", $physicianTotal);
		smarty()->assignByRef("emptyDates", $emptyDates);
		smarty()->assignByRef("emptyRooms", $emptyRooms);
		smarty()->assign("assignedRooms", $assignedRooms);
		smarty()->assignByRef("rooms", $rooms);
		smarty()->assign("type", $type);
		smarty()->assign("datetime", $datetime);
		smarty()->assignByRef("facility", $facility);
		
		
		
		/*
		 * EXPORT THE CENSUS TO EXCEL / PDF
		 *
		 */
				
		if (input()->export != '') {
		
			require_once APP_PROTECTED_PATH . "/lib/contrib/Classes/PHPExcel.php";
			
			$styleArray = array(
				'font' => array(
					'bold' => true,
				)
			);	
			
			if (input()->export == "excel") {
				// Export to excel file
				$objPHPExcel = PHPExcel_IOFactory::load(APP_PATH . "/public/templates/census.xlsx");
				
			} else {
				// Export to a PDF file
				$objPHPExcel = PHPExcel_IOFactory::load(APP_PATH . "/public/templates/census.xlsx");
				$rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
				$rendererLibrary = 'mPDF5.3';
				$rendererLibraryPath = APP_PROTECTED_PATH . "/lib/contrib/Libraries/" . $rendererLibrary;
				$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
				
				if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
					die('NOTICE: Please set the $rendererName and $rendererLibraryPath values' . EOL . 'at the top of this script as appropriate for your directory structure');
				}
				
			}
			
			$objPHPExcel->getProperties()->setTitle("$facility->name Census");
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader("&24" . $facility->name . ' Census');
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter("&RPrinted: " . date("m/d/y g:i a", strtotime("now")));
			
			
			// Set header info
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "$facility->name Census");
			$objPHPExcel->getActiveSheet()->setCellValue("A2", "Room");
			$objPHPExcel->getActiveSheet()->setCellValue("B2", "Patient Name");
			$objPHPExcel->getActiveSheet()->setCellValue("C2", "Date of Birth");
			$objPHPExcel->getActiveSheet()->setCellValue("D2", "Admission Date");
			$objPHPExcel->getActiveSheet()->setCellValue("E2", "Scheduled Discharge Date");
			$objPHPExcel->getActiveSheet()->setCellValue("F2", "PCP");
			$objPHPExcel->getActiveSheet()->setCellValue("G2", "Attending Physician");
			$objPHPExcel->getActiveSheet()->setCellValue("H2", "Surgeon/Specialist");
			
			// Set census info
			$row = 3;
			foreach ($rooms as $room) {
				
				// Set patient info foreach room
				if ($room->patient_admit_pubid != "") {
					$occupant = CMS_Patient_Admit::generate();
					$occupant->load($room->patient_admit_pubid);
					$occupantSchedule = CMS_Schedule::generate();
					$occupantSchedule->load($room->schedule_pubid);	
					
				} else {
					$occupant = false;
				}
				
				$objPHPExcel->getActiveSheet()->setCellValue("A" . $row, $room->number);
				if ($occupant) {
					$objPHPExcel->getActiveSheet()->setCellValue("B" . $row, $occupant->fullName());
				}
				if ($occupant->birthday != '') {
					$objPHPExcel->getActiveSheet()->setCellValue("C" . $row, date("m/d/Y", strtotime($occupant->birthday)));
				}

				// Get the patient's date and time of admission
				if ($room->datetime_admit != '') {
					$objPHPExcel->getActiveSheet()->setCellValue("D" . $row, date("m/d/Y", strtotime($room->datetime_admit)));
				}

				// Get the patient's date and time of discharge (if scheduled)
				if ($room->datetime_discharge != '') {
					$objPHPExcel->getActiveSheet()->setCellValue("E" . $row, date("m/d/Y", strtotime($room->datetime_discharge)));
				}

				// Get the patient's PCP
				if ($occupant->doctor_id != "") {
					$pcp = CMS_Physician::generate();
					$pcp->load($occupant->doctor_id);
					$objPHPExcel->getActiveSheet()->setCellValue("F" . $row, $pcp->last_name . ', ' . $pcp->first_name);
				} 


				if ($occupant->physician_id != "") {
					$physician = CMS_Physician::generate();
					$physician->load($occupant->physician_id);
					$objPHPExcel->getActiveSheet()->setCellValue("G" . $row, $physician->last_name . ', ' . $physician->first_name);
				} 
				
				if ($occupant->ortho_id != "") {
					$ortho = CMS_Physician::generate();
					$ortho->load($occupant->ortho_id);
					$objPHPExcel->getActiveSheet()->setCellValue("H" . $row, $ortho->last_name . ', ' . $ortho->first_name);
				}
				$row++;
				
				
			}
			
			
			
			// Include required files
			require_once APP_PROTECTED_PATH . "/lib/contrib/Classes/PHPExcel/IOFactory.php";
			if (input()->export == "excel") {
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				// Output to Excel file
				header('Pragma: ');
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				// Name the file
				header("Content-Disposition: attachment; filename=" . $facility->name . "_" . $_dateStart . ".xlsx");
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');		
			} elseif (input()->export == "pdf") { // If you want to output e.g. a PDF file, simply do:			
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
				// Output to PDF file
				header('Pragma: ');
				header("Content-type: application/pdf");
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');		
				// Name the file
				//header("Content-Disposition: attachment; filename=" . $facility->name . "_" . $_dateStart . ".pdf");
			}
	
			// Write file to the browser
			$objWriter->save("php://output");
			exit;
		}
		
	}	
	
	
	
	public function cancelBedHold() {
		$schedule = new CMS_Schedule(input()->schedule);
		if (! $schedule->valid() ) {
			feedback()->error("Invalid discharge selected.");
			$this->redirect(auth()->getRecord()->homeURL());
		}
		
		$facility = new CMS_Facility($schedule->facility);
		$scheduleHospital = $schedule->atHospitalRecord();
		
		$schedule->discharge_to = "Discharge to Hospital";
		$schedule->datetime_discharge_bedhold_end = "";
		$scheduleHospital->bedhold_offered = 0;
		$scheduleHospital->datetime_bedhold_end = "";
		
		try {
			$schedule->save();
			$scheduleHospital->save();
			$this->redirect(SITE_URL . "/?page=facility&action=census&facility={$facility->id}");
		} catch (Exception $e) {
			feedback()->error("Could not cancel the discharge.");
			$this->redirect(SITE_URL . "/?page=facility&action=census&facility={$facility->id}");
		}
			
	}
	

	public function room_transfer() {
		$schedule = new CMS_Schedule(input()->schedule);
		if ($schedule->valid()) {
			smarty()->assignByRef("schedule", $schedule);

			if (input()->facility != '') {
				$facility = new CMS_Facility(input()->facility);
				if ($facility->valid() == false) {
					$facility = $schedule->related('facility');
				}
			} else {
				$facility = $schedule->related('facility');
			}
			$datetime = datetime(strtotime('now'));
			


			/*
			 * Note: Get rooms which are or will be empty on the admission date & time
			 *
			 */

			$empty = CMS_Room::fetchEmptyByFacility($facility->id, $datetime);
			$discharges = CMS_Room::fetchScheduledByFacility($facility->id, $datetime);
			$rooms = CMS_Room::mergeFetchedRooms($empty, $discharges);
					
			smarty()->assignByRef("rooms", $rooms);
			smarty()->assignByRef("facility", $facility);
			smarty()->assign("goToApprove", input()->goToApprove);
			smarty()->assign("datetime", $datetime);
		} else {
			feedback()->error("Invalid scheduling selected.");
			$this->redirect(SITE_URL . "/?page=coord");
		}
	}

	public function submitRoomTransfer() {
		// Get schedule info for the tranferring patient
		$schedule = new CMS_Schedule(input()->schedule);
		$facility = new CMS_Facility($schedule->facility);

		// Get the room info for the new room
		$new_room = new CMS_Room(input()->room);


		/* If there is a patient currently in the room to which the patient is being tranferred
		 * get the schedule info for that patient as well.  This patient will be transferred to
		 * the room from which the new patient is being transferred.  If this patient needs to 
		 * go to a different room this process will need to be repeated for that patient.
		 */

		if (input()->occupant != "") {
			$previousOccupant = new CMS_Schedule(input()->occupant);

			// Set the room to the from from which the new patient transferred
			$previousOccupant->room = $schedule->room;
			$room = new CMS_Room($schedule->room);
		} else {
			$previousOccupant = false;
		}
		
		// Set the new room # for the transferring patient
		$schedule->room = $new_room->id;

		try {
			$schedule->save();
			if ($previousOccupant) {
				$previousOccupant->save();
			}
			$this->redirect(SITE_URL . "/?page=facility&action=census&facility={$facility->pubid}");
		} catch (Exception $e) {
			$this->redirect(SITE_URL . "/?page=facility&action=room_transfer&schedule={$schedule->pubid}");
		}

	}
	

/*
	public function searchCodes() {
		$term = input()->term;
		if ($term != '') {
			$tokens = explode(" ", $term);
			$params = array();
			$sql = "select * from icd9_codes where ";
			foreach ($tokens as $idx => $token) {
				$token = trim($token);
				$sql .= " short_desc like :term{$idx} AND";
				$params[":term{$idx}"] = "%{$token}%";
			}
			$sql = rtrim($sql, " AND");
			$results = db()->getRowsCustom($sql, $params);
		} else {
			$results = array();
		}

		json_return($results);

	}
*/

/*
	public function searchHospital() {
		$term = input()->term;
		if ($term != '') {
			$tokens = explode(" ", $term);
			$params = array();
			$sql = "select * from hospital where ";
			foreach ($tokens as $idx => $token) {
				$token = trim($token);
				$sql .= " hospital_name like :term{$idx} AND";
				$params[":term{$idx}"] = "%{$token}%";
			}
			$sql = rtrim($sql, " AND");
			$results = db()->getRowsCustom($sql, $params);
		} else {
			$results = array();
		}

		json_return($results);

	}
*/
	
	
	
/*
	public function reports() {		// page created  2012-02 by kwh
		smarty()->assign("isPrint", input()->isPrint);
		
		$facility = new CMS_Facility(input()->facility);
		if (! $facility->valid()) {
			$facility = null;
		} 

		$_facility = $facility->id;
		smarty()->assign("facilityId", $_facility);
		smarty()->assign("facility", $facility);

		if (input()->start_date != '') {
			$_dateStart = date("Y-m-d G:i:s", strtotime(input()->start_date . "00:00:01"));
		} else {
			$_dateStart = false;
		}

		if (input()->end_date != '') {
			$_dateEnd = date("Y-m-d G:i:s", strtotime(input()->end_date . "23:59:59"));
		} else {
			$_dateEnd = false;
		}

		$_status = 'Approved';
		
		if (input()->summary != '') {
			$summary = input()->summary;
		}
		
		smarty()->assign("summary", $summary);
		smarty()->assignByRef("dateStart", $_dateStart);
		smarty()->assignByRef("dateEnd", $_dateEnd);
		smarty()->assign("type", input()->type);
		

		// Need to get info for reports
		if (input()->orderby == '') {
			$_orderby = false;
		} else {
			switch (input()->orderby) {
				case "room_ASC":
				$_orderby = "room ASC";
				break;

				case "patient_name_ASC":
				$_orderby = "last_name ASC";
				break;

				case "datetime_sent_DESC":
				$_orderby = "datetime_sent DESC";
				break;

				case "datetime_sent_ASC":
				$_orderby = "datetime_sent ASC";
				break;

				case "icd9_code_DESC";
				$_orderby = "icd9_codes.code";
				break;

				case "datetime_admit_DESC":
				$_orderby = "datetime_admit DESC";
				break;

				case "datetime_admit_ASC":
				$_orderby = "datetime_admit ASC";
				break;

				case "datetime_discharge_DESC":
				$_orderby = "datetime_discharge DESC";
				break;

				case "datetime_discharge_ASC":
				$_orderby = "datetime_discharge ASC";
				break;

				case "discharge_disposition_ASC":
				$_orderby = "discharge_disposition ASC";
				break;

				case "physician_ASC":
				$_orderby = "physician_name ASC";
				break;

				case "surgeon_ASC":
				$_orderby = "surgeon_name ASC";
				break;

				case "datetime_seen_DESC":
				$_orderby = "datetime_first_seen DESC";
				break;

				case "datetime_seen_ASC":
				$_orderby = "datetime_first_seen ASC";
				break;

				case "datetime_readmit_DESC":
				$_orderby = "schedule.datetime_readmit DESC";
				break;

				case "datetime_readmit_ASC":
				$_orderby = "schedule.datetime_readmit ASC";
				break;

				case "hospital_name_ASC":
				$_orderby = "hospital.name ASC";
				break;

				case "paymethod_ASC":
				$_orderby = "patient_admit.paymethod ASC";
				break;

				case "readmit_type":
				$_orderby = "readmit_type DESC";
				break;

			}
		}
				
		if (input()->filterby != '' && input()->filterby != 'undefined') {
			$_filterby = input()->filterby;
		} else {
			$_filterby = false;
		}
		
		if (input()->viewby != '') {
			$_viewby = input()->viewby;
		} else {
			$_viewby = false;
		}
	
		// get data for filterby drop-down
		if ($_filterby != false) {
			if (input()->type == 'discharge') {
				$_patientStatus = 'datetime_discharge';
			} else {
				$_patientStatus = 'datetime_admit';
			}
			$filterData = CMS_Schedule::fetchDataForFilter($_dateStart, $_dateEnd, $_facility, $_patientStatus, $_filterby, $_viewby);
		}		
						
		
		smarty()->assign("orderby", input()->orderby);
		smarty()->assign("filterby", input()->filterby);
		smarty()->assign("viewby", input()->viewby);
		smarty()->assignByRef("filterData", $filterData);

		// admission report info
		if (input()->type == 'admit') {
			
			$obj = CMS_Schedule::generate();
			
			// get admissions for selected time period
			$admits = $obj->fetchAdmitsByFacility($_dateStart, $_dateEnd, $_status, $_facility, $_orderby, $_filterby, $_viewby);
			
			$totalAdmitsByView = count($admits);		
			$totalAdmits = $obj->fetchAdmitsByFacility($_dateStart, $_dateEnd, $_status, $_facility);
			$countTotalAdmits = count($totalAdmits);
						
			if (input()->viewby != '') {
				$admitPercentage = number_format(($totalAdmitsByView/$countTotalAdmits) * 100, 0);
			} 
			
			
			$c = 0;				
			$summaryReport = array();
			while ($c < count($filterData)) {
				foreach ($filterData as $data) {
				
				if ($_filterby == 'hospital') {
					$dataId = $data->hospital_id;
				} elseif ($_filterby == 'physician') {
					$dataId = $data->physician_id;
				} elseif ($_filterby == 'ortho') {
					$dataId = $data->ortho_id;
				} elseif ($_filterby == 'case_manager') {
					$dataId = $date->case_manager;
				}
						
				$obj = CMS_Patient_Admit::generate();
				
					$numberOfAdmits = $obj->summaryReport($_filterby, $dataId, $_dateStart, $_dateEnd, $_facility);
					$summaryReport[$c]['numberOfAdmits'] = $numberOfAdmits;
					if ($_filterby == 'hospital') {
						$summaryReport[$c]['name'] = $data->name;
					} elseif ($_filterby == 'physician') {
						$p = CMS_Physician::generate();
						$p->load($data->physician_id);
						$summaryReport[$c]['name'] = $p->last_name . ", " . $p->first_name . " M.D.";
					} elseif ($_filterby == 'ortho') {
						$o = CMS_Physician::generate();
						$o->load($data->getPatient()->ortho_id);
						$summaryReport[$c]['name'] = $o->last_name . ", " . $o->first_name . " M.D.";
					}
					$summaryReport[$c]['percentageOfAdmits'] = ceil(($numberOfAdmits/$countTotalAdmits) * 100);
					$summaryReport[$c]['dataId'] = $dataId;
					$c++;
				}
				
			}	
			rsort($summaryReport);
						
			smarty()->assign("facility", $facility);
			smarty()->assignByRef("admits", $admits);
			smarty()->assign("summaryReport", $summaryReport);
			smarty()->assign("totalAdmitsByView", $totalAdmitsByView);
			smarty()->assign("countTotalAdmits", $countTotalAdmits);
			smarty()->assign("admitPercentage", $admitPercentage);
			
		}
		

		// discharge report info
		if (input()->type == 'discharge') {

			$obj = CMS_Schedule::generate();
			$discharges = $obj->fetchDischargesByFacility($_dateStart, $_dateEnd, $_status, $_facility, $_orderby, $_filterby, $_viewby);
			if (!empty ($discharges)) {
				$dischargeCount = count($discharges);
				foreach ($discharges as $d) {
					$days[] = $obj->LoS($d->datetime_discharge, $d->datetime_admit);
					$total = array_sum($days);
				}
	
				$totalDays = round($total/count($discharges));
			}
			


			smarty()->assignByRef("totalDays", $totalDays);
			smarty()->assign("dischargeCount", $dischargeCount);
			smarty()->assignByRef("discharges", $discharges);
		}


		// cancelled patients info
		if (input()->type == 'cancelled') {
			$obj = CMS_Schedule::generate();
			$cancelled = $obj->getCancelledInquiries($_dateStart, $_dateEnd, $_facility, $_orderby);

			smarty()->assignByRef("cancelled", $cancelled);
		}


		// returned to hospital report info
		if (input()->type == 'returned_to_hospital') {


			// $_facility = $facility->id;
			$obj = CMS_Schedule::generate();
			$returnedReport = $obj->getPatientsSentToHospital($_dateStart, $_dateEnd, $_facility, $_orderby);

			// Get datetiime_admit for those patients who have re-admitted

			// Calculate Re-Admission Rate
			$sentCount = count($obj->getPatientsSentToHospital($_dateStart, $_dateEnd, $_facility));
			$admittedCount = count($obj->fetchAdmitsByFacility($_dateStart, $_dateEnd, $_status, $_facility));
			$readmitRate = number_format(($sentCount/$admittedCount) * 100, 1);

			smarty()->assign("readmitRate", $readmitRate);
			smarty()->assign("orderby", input()->orderby);
			smarty()->assign("filterby", input()->filterby);
			smarty()->assignByRef("returnedReport", $returnedReport);
		}

		// re-admission report info
		if (input()->type == 're-admission') {
			$obj = CMS_Schedule::generate();
			$readmit = $obj->fetchReadmitsByFacility($_dateStart, $_dateEnd, $_facility, $_orderby);

			smarty()->assignByRef("readmit", $readmit);
		}

	}
*/
	
/*
	public function printReport() {

		$facility = new CMS_Facility(input()->facility);
		if (! $facility->valid()) {
			$facility = null;
		}

		smarty()->assignByRef("facility", $facility);

		if (input()->start_date != '') {
			$_dateStart = date("Y-m-d G:i:s", strtotime(input()->start_date . "00:00:01"));
		} else {
			$_dateStart = false;
		}

		if (input()->end_date != '') {
			$_dateEnd = date("Y-m-d G:i:s", strtotime(input()->end_date . "23:59:59"));
		} else {
			$_dateEnd = false;
		}

		$_status = 'Approved';
		$_orderby = input()->orderby;
		$_filterby = input()->filterby;
		$_viewby = input()->viewby;
		$_facility = $facility->id;

		smarty()->assignByRef("dateStart", $_dateStart);
		smarty()->assignByRef("dateEnd", $_dateEnd);
		smarty()->assign("type", input()->type);

		// print admission report
		if (input()->type == 'admit') {
			$obj = CMS_Schedule::generate();
			$admits = $obj->fetchAdmitsByFacility($_dateStart, $_dateEnd, $_status, $_facility, $_orderby, $_filterby, $_viewby);

			smarty()->assign("orderby", input()->orderby);
			smarty()->assign("filterby", input()->filterby);
			smarty()->assignByRef("admits", $admits);
		}

		// print discharge report
		if (input()->type == 'discharge') {
			$obj = CMS_Schedule::generate();
			$discharges = $obj->fetchDischargesByFacility($_dateStart, $_dateEnd, $_status, $_facility, $_orderby, $_filterby);

			smarty()->assignByRef("totalDays", $totalDays);
			smarty()->assign("dischargeCount", $dischargeCount);
			smarty()->assign("orderby", input()->orderby);
			smarty()->assign("filterby", input()->filterby);
			smarty()->assignByRef("discharges", $discharges);
		}

		// print rejected inquiries report
		$obj = CMS_Schedule::generate();
		$rejected = $obj->getCancelledInquiries($_dateStart, $_dateEnd, $_facility, $_orderby);

		smarty()->assignByRef("rejected", $rejected);

		// print returned to hospital report
		if (input()->type == 'returned_to_hospital') {

			if (input()->filterby != '') {
				$_filterby = input()->filterby;
			}

			$_facility = $facility->id;
			$obj = CMS_Schedule::generate();
			$returnedReport = $obj->getPatientsSentToHospital($_dateStart, $_dateEnd, $_facility);

			// Get datetiime_admit for those patients who have re-admitted

			// Calculate Re-Admission Rate
			$sentCount = count($obj->getPatientsSentToHospital($_dateStart, $_dateEnd, $_facility));
			$admittedCount = count($obj->fetchAdmitsByFacility($_dateStart, $_dateEnd, $_status, $_facility));
			$readmitRate = number_format(($sentCount/$admittedCount) * 100, 1);

			smarty()->assign("readmitRate", $readmitRate);
			smarty()->assign("orderby", input()->orderby);
			smarty()->assign("filterby", input()->filterby);
			smarty()->assignByRef("returnedReport", $returnedReport);
		}


		// Need to get info for re-admission report
		if (input()->type == 're-admission') {
			$obj = CMS_Schedule::generate();
			$readmitReport = $obj->fetchReadmitsByFacility($_dateStart, $_dateEnd, $_facility);

			// Calculate Re-Admission Rate
			$sentCount = count($obj->getPatientsSentToHospital($_dateStart, $_dateEnd, $_facility));
			$admittedCount = count($obj->fetchAdmitsByFacility($_dateStart, $_dateEnd, $_status, $_facility));
			$readmitRate = number_format(($sentCount/$admittedCount) * 100, 1);

			smarty()->assign("readmitRate", $readmitRate);
			smarty()->assign("orderby", input()->orderby);
			smarty()->assign("filterby", input()->filterby);
			smarty()->assignByRef("readmitReport", $readmitReport);
		}

	}
*/


	public function LoS($datetime_discharge, $datetime_admit) {

		$d = strtotime($datetime_discharge);
		$a = strtotime($datetime_admit);

		$dateDiff = abs($d - $a);

		return round($dateDiff/86400);
	}


	public function approveInquiry() {
		$schedule = new CMS_Schedule(input()->schedule);
		$schedule->status = input()->status;

		try {
			$schedule->save();
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
	
	
		
}
