<?php

class PageControllerReport extends PageController {
		
/*
/*  These are now tracked in the database allowing different reports for different companies
    — Kemish W. Hendershot, 2014-05-13
	public $reportTypes = array(
		"admission" => "Admission",
		"discharge_type" => "Discharge Type",
		"discharge_service" => "Discharge Service",
		"discharge_history" => "Discharge History (4 Weeks)",
		"facility_transfer" => "Facility Transfers",	
		"length_of_stay" => "Length of Stay",
		"cancelled" => "Not Admitted",
		"readmission" => "Re-Admission",
		"returned_to_hospital" => "Returned to Hospital",
		"discharge_calls" => "30 Day Discharge Calls"
 	);
*/
	
	public $viewOpts = array(
			"month" => "Month",
			"quarter" => "Quarter",
			"year" => "Year"
		);		
	
	public function init() {
		Authentication::disallow();
	}
	
	public function __construct() {
		
		smarty()->assign("reportTypes", $this->reportTypes());
		smarty()->assign("type", input()->action);
		smarty()->assign("viewOpts", $this->viewOpts);
		smarty()->assign("yearOpts", $this->yearOpts());

	}
	
	
	public function yearOpts() {
		return array_combine (range (2011, date("Y", strtotime("now"))), range (2011, date("Y", strtotime("now"))));
	}
	
	public function index() {
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
		
		$report_types = $this->reportTypes();
				
		smarty()->assign("summary", $summary);
		smarty()->assignByRef("dateStart", $_dateStart);
		smarty()->assignByRef("dateEnd", $_dateEnd);
		smarty()->assign("type", input()->type);
		smarty()->assign("reportTypes", $report_types);
		smarty()->assign("viewOpts", $this->viewOpts);
		smarty()->assign("yearOpts", $this->yearOpts());
	}
	
	
	
	
	
	
	/*
	 * -------------------------------------------------------------
	 *  ADMISSION REPORT
	 * -------------------------------------------------------------
	 * 
	 */
	
	public function admission() {
		
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
		} else {
			$summary = false;
		}
		
		smarty()->assign("summary", $summary);
		smarty()->assignByRef("dateStart", $_dateStart);
		smarty()->assignByRef("dateEnd", $_dateEnd);
		
		smarty()->assign("start_date", input()->start_date);
		smarty()->assign("end_date", input()->end_date);		

		
		// Create and assign report types, orderby, and filterby options		
		$orderByOpts = array(
			'room_ASC' => 'Room # (lowest to highest)',
			'patient_name_ASC' => 'Patient Name (A &rarr; Z)',
			'datetime_admit_DESC' => 'Admission Date (most recent first)',
			'datetime_admit_ASC' => 'Admission Date (oldest first)',
		);
		
		$filterByOpts = array(
			'hospital' => 'Hospital',
			'pcp' => 'Primary Care Physician',
			'physician' => 'Attending Physician',
			'surgeon' => 'Orthopedic Surgeon/Specialist',
			'case_manager' => 'Case Manager',
			'zip_code' => 'Zip Code'
		);
		smarty()->assign("orderByOpts", $orderByOpts);
		smarty()->assign("filterByOpts", $filterByOpts);
		
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

				case "datetime_admit_DESC":
				$_orderby = "datetime_admit DESC";
				break;

				case "datetime_admit_ASC":
				$_orderby = "datetime_admit ASC";
				break;
			}
		}
						
		if (input()->filterby != '' && input()->filterby != 'undefined') {
			$_filterby = input()->filterby;
			if (!isset (input()->viewby) || input()->viewby == '') {
				$_viewby = '';
			}
		} else {
			$_filterby = false;
		}
				
		if (input()->viewby != '') {
			$_viewby = input()->viewby;
		} else {
			$_viewby = '';
		}

		$obj = CMS_Schedule::generate();
							
		// get admissions for selected time period
		$admits = $obj->fetchAdmitsByFacility($_dateStart, $_dateEnd, $_facility, $_filterby, $_viewby);
		
		$totalAdmitsByView = count($admits);	
			
		$totalAdmits = $obj->fetchAdmitsByFacility($_dateStart, $_dateEnd, $_facility);
		$countTotalAdmits = count($totalAdmits);
							
		if (input()->viewby != '') {
			$admitPercentage = number_format(($totalAdmitsByView/$countTotalAdmits) * 100, 0);
		} 
																														
				
		
		$startDate = date('Y-m-d', strtotime($_dateStart));
		$endDate = date('Y-m-d', strtotime($_dateEnd));
		
		$urlString = SITE_URL . "/?page=report&action=admission&facility={$facility->pubid}&start_date={$startDate}&end_date={$endDate}&orderby={$_orderby}&filterby={$_filterby}&viewby={$_viewby}&summary={$summary}";
		smarty()->assign('urlString', $urlString);
		
		
		// Set views for detailed data reports
		if ($_filterby) {
			if ($_filterby == 'zip_code') {
				$filterData = $obj->fetchInfoByZip($_dateStart, $_dateEnd, $_facility);
				$this->setView('report/admission', 'zip');
			} else {
				$filterData = $obj->fetchFilterData($_dateStart, $_dateEnd, $_facility, $_filterby);
				$summary = input()->summary;			
				$c = 0;				
				$summaryReport = array();
								
				while ($c < count($filterData)) {
					foreach ($filterData as $data) {
						$_data_id = $data->id;
																		
						$obj = CMS_Patient_Admit::generate();
						
						$numberOfAdmits = $obj->summaryReport($_dateStart, $_dateEnd, $_facility, $_data_id, $_filterby);

						foreach ($numberOfAdmits as $n) {
							$summaryReport[$c]['numberOfAdmits'] = $n->num_admits;
							if ($_filterby == "hospital") {
								$summaryReport[$c]['name'] = $n->name;
							} else {
								$summaryReport[$c]['name'] = $n->last_name . ', ' . $n->first_name;
							}
						}
						$summaryReport[$c]['id'] = $_data_id;	
						//$numberOfAdmits = $obj->summaryReport($_filterby, $dataId, $_dateStart, $_dateEnd, $_facility);
						foreach ($numberOfAdmits as $a) {
							$summaryReport[$c]['percentageOfAdmits'] = number_format(($a->num_admits/$countTotalAdmits) * 100, 1);
						}
						
						$c++;
					}
					
				}	

				rsort($summaryReport);

							
				if ($_filterby == 'hospital') {
					$this->setView('report/admission', 'hospital');
				} elseif ($_filterby == 'surgeon') {
					$this->setView('report/admission/', 'surgeon');
				} elseif ($_filterby == 'physician') {
					$this->setView('report/admission/', 'physician');
				} elseif ($_filterby == 'pcp') {
					$this->setView('report/admission/', 'pcp');
				} elseif ($_filterby == 'case_manager') {
					$this->setView('report/admission/', 'case_manager');
				}
			}

		}
						
		smarty()->assign("summaryReport", $summaryReport);
		smarty()->assign("orderby", $_orderby);
		smarty()->assign("filterby",$_filterby);
		smarty()->assign("viewby", $_viewby);
		smarty()->assignByRef("filterData", $filterData);
		smarty()->assign("facility", $facility);
		smarty()->assignByRef("admits", $admits);
		smarty()->assign("totalAdmitsByView", $totalAdmitsByView);
		smarty()->assign("countTotalAdmits", $countTotalAdmits);
		smarty()->assign("admitPercentage", $admitPercentage);




		
		
		
		/*
		 * EXPORT REPORT TO EXCEL
		 *
		 * Note: If export=excel, export to an Excel file, else if export=pdf, export to a PDF file
		 *
		 */
		
		if (input()->export != '') {
			
			// Create an array to display items in the excel file
			$dataSet = array();
						
			if (input()->summary) {
				
				foreach ($summaryReport as $s) {
					$dataSet[0] = array('Name', 'Number of Admissions', '% of Total Admissions');
					$dataSet[]  = array(
						$s['name'],
						$s['numberOfAdmits'],
						$s['percentageOfAdmits']
					);
				}
				
			} else {
				foreach ($admits as $a) {
					$dataSet[0] = array('Room #', 'Patient Name', 'Admit Date', 'Hospital', 'PCP', 'Attending Physician', 'Specialist/Surgeon');
					$dataSet[] = array(
						$a->number,
						$a->last_name . ', ' . $a->first_name,
						date ('m/d/Y', strtotime($a->datetime_admit)),
						$a->hospital_name,
						$a->pcp_first . ' ' . $a->pcp_last,
						$a->physician_first . ' ' . $a->physician_last,
						$a->surgeon_first . ' ' . $a->surgeon_last
					);
				}
			}
																		
			$this->export($facility, 'Admission Report', $dataSet);
		}
		
				
					
	}
	
	public function details() {
		$facility = new CMS_Facility(input()->facility);
		if (! $facility->valid()) {
			$facility = null;
		} 
		
		
		$_facility = $facility->id;
		smarty()->assign("facilityId", $_facility);
		smarty()->assign("facility", $facility);
		smarty()->assign("filterby", input()->filterby);
		smarty()->assign("viewby", input()->viewby);
		smarty()->assign("start_date", input()->start_date);
		smarty()->assign("end_date", input()->end_date);
				
		$returnUrl = $SITE_URL . "/?page=report&action=admission&facility=" . $facility->pubid . "&start_date=" . input()->start_date . "&end_date=" . input()->end_date . "&orderby=" . input()->orderby . "&filterby=" . input()->filterby . "&viewby=" . input()->viewby;
		smarty()->assign('returnUrl', $returnUrl);
		
		$date_start = date('Y-m-d 00:00:01', strtotime(input()->start_date));
		$date_end = date('Y-m-d 23:59:59', strtotime(input()->end_date));
		$filterby = input()->filterby;
		$viewby = input()->viewby;
		
		if (input()->orderby != '') {
			$orderby = input()->orderby;
		} else {
			$orderby = false;
		}
		
		// Get admission source name
		$obj = new CMS_Schedule();	
		if ($filterby != "zip_code") {
			$admitFrom = $obj->getAdmitFromName($filterby, $viewby);
			$admits = $obj->fetchAdmitsByFacility($date_start, $date_end, $_facility, $filterby, $viewby, $orderby);
			
			smarty()->assign('admits', $admits);
			smarty()->assign('admitFrom', $admitFrom[0]);
			
			$this->setView('report/admission', 'details');	
		} else {
			$admits = $obj->fetchAdmitsByZip($date_start, $date_end, $_facility, $filterby, $viewby, $orderby);
			
			smarty()->assign('zip_code', input()->viewby);
			smarty()->assign('admits', $admits);
			$this->setView('report/admission', 'admits_by_zip');
		}							
		
	}
	
	
	public function zip_map() {
		$facility = new CMS_Facility(input()->facility);
		if (! $facility->valid()) {
			$facility = null;
		} 
		
	
		$returnUrl = "/?page=report&action=admission&facility=" . $facility->pubid . "&start_date=" . input()->start_date . "&end_date=" . input()->end_date . "&orderby=" . input()->orderby . "&filterby=" . input()->filterby . "&viewby=" . input()->viewby;
		smarty()->assign("returnUrl", $returnUrl);
		smarty()->assign("zipCode", input()->viewby);
		smarty()->assign("facility", $facility);
		
		$this->setView('report/admission', 'zip_map');
	}
	
	
	
	
	
	
	/*
	 * -------------------------------------------------------------
	 *  DISCHARGE REPORT
	 * -------------------------------------------------------------
	 * 
	 */
	
	public function discharge_type() {
		smarty()->assign("isPrint", input()->isPrint);
		
		$facility = new CMS_Facility(input()->facility);
		if (! $facility->valid()) {
			$facility = null;
		} 

		$_facility = $facility->id;
		smarty()->assign("facilityId", $_facility);
		smarty()->assign("facility", $facility);
		smarty()->assign("view", input()->view);
		smarty()->assign("year", input()->year);
		
		/**
		 * Assign dates by month, quarter, or year using the dateRange array below.  This will then query 
		 * the db to count the discharges for the specified time period.
		 *
		 */
		 
		$dateRange = array();
		if (input()->year != "") {
			$year = input()->year;
			if (input()->view == "month") {
				for ($i=1;$i<=12;$i++) {
					$timestamp = mktime (0, 0, 0, $i, 1, $year);
					$dateRange[$i]["dateStart"] = "$year-$i-01 00:00:01";
					$dateRange[$i]["dateEnd"] = "$year-$i-" . date("t", $timestamp) . " 23:59:59";
					$dateRange[$i]["facility"] = $facility->id;
				}
			} elseif (input()->view == "quarter") {
				$i = 1;
				while ($i<=12) {
					$end = $i + 2;
					$dateRange[$i]["dateStart"] = "$year-$i-01 00:00:01";
					$dateRange[$i]["dateEnd"] = "$year-$end-" . date("t", $timestamp) . " 23:59:59";
					$dateRange[$i]["facility"] = $facility->id;
					$i = $end += 1;
				}
			} elseif (input()->view == "year") {
				$i = 1;
				while ($i <= 1) {
					$dateRange[$i]["dateStart"] = $year . "-01-01 00:00:01";
					$dateRange[$i]["dateEnd"] = $year . "-12-" . date("t", $timestamp) . " 23:59:59";
					$dateRange[$i]["facility"] = $facility->id;
					$i ++;
				}
			}
		}
								
		
		// Set options for filter by dropdown
		$filterByOpts = array(
			"All Results" => "All Results",
			"AHC Home Health" => "AHC Home Health",
			"Other Home Health" => "Other Home Health"
		);
		smarty()->assign("filterByOpts", $filterByOpts);
		
		// Create and assign report types, orderby, and filterby options		
		$orderByOpts = array(
			'patient_name_ASC' => 'Patient Name (A &rarr; Z)',
			'discharge_disposition' => 'Discharge Disposition',
			'service_disposition' => 'Service Disposition',
			'discharge_location' => 'Discharge Location Name (A &rarr; Z)',
		);
		smarty()->assign("orderByOpts", $orderByOpts);
		
		if (input()->filterby != "") {
			$_filterby = input()->filterby;
		} else {
			$_filterby = false;
		}
		smarty()->assign("filterby", $_filterby);		
						
		$obj = CMS_Schedule::generate();
		
		
		/**
		 * If input()->discharge_to is set then we are looking for a detailed view of which patients where
		 * discharged in that category.  We will use the date start and date end functionality below to 
		 * differentiate between month, quarter, and year.
		 */	
		 	
		if (input()->discharge_to != '') {
			if (input()->discharge_to == "All Results") {
				$_discharge_to = "";
			} else {
				$_discharge_to = input()->discharge_to;
			}
		} else {
			$_discharge_to = false;
			
		}
		smarty()->assign("discharge_to", $_discharge_to);
				
		if (input()->discharge_disposition != '') {
			$_dc_disp = input()->discharge_disposition;
		} else {
			$_dc_disp = false;
		}
		
		smarty()->assign("dc_disp", $_dc_disp);
		
		if (input()->orderby == '') {
			$_orderby = false;
		} else {
			switch (input()->orderby) {

				case "patient_name_ASC":
				$_orderby = "last_name";
				break;
				
				case "discharge_disposition";
				$_orderby = "discharge_disposition";
				break;
				
				case "service_disposition";
				$_orderby = "service_disposition";
				break;
				
				case "discharge_location";
				$_orderby = "discharge_location_id";
				break;


			}
		}
		
		smarty()->assign("orderby", input()->orderby);
		
		
		if (input()->dateStart != '') {
			$_dateStart = date("Y-m-d 00:00:01", strtotime(input()->dateStart));
			if (input()->view == "month") {
				$_dateEnd = date("Y-m-d 23:59:59", (strtotime(" + 1 month", strtotime(input()->dateStart)) - 1));
			} elseif (input()->view == "quarter") {
				$_dateEnd = date("Y-m-d 23:59:59", (strtotime(" + 3 months", strtotime(input()->dateStart)) - 1));
			} elseif (input()->view == "year") {
				$_dateEnd = date("Y-m-d 23:59:59", strtotime(input()->dateStart  . " + 365 days"));
			}
			
		}
						
		
		/**
		 * If discharge_to is set get specific patient info using the set date start and date end values.  Otherwise,
		 * use the dateRange array to get discharges per time period.
		 *
		 * NOTE:  Using the dateRange array means that the app will query the db for each time period, so for the month
		 * view it will post 12 queries.  If I were smarter (maybe sometime in the future), this may be able to be done 
		 * in only one query with the data formatted correctly in the array instead of having to re-arrange the data
		 * following the query.  This also occurs on the Length Of Stay report.
		 */
		 
/*
		if ($_discharge_to != false && $_filterby == false) {
			$data[] = $obj->fetchDischargesByFacility($_dateStart, $_dateEnd, $_facility, $_discharge_to, $_filterby);
		} 
*/

		/**
		 * If looking at discharge to data need to get the start and end date
		 * for the time period in which the selected item falls.
		 */
		
		$data = array();
		if ($_discharge_to != false) {
			$data[] = $obj->fetchDischargesByFacility($_dateStart, $_dateEnd, $_facility, $_discharge_to, $_dc_disp, $_filterby, $_orderby);
		} 
				
		if ($_filterby != false && $_discharge_to == false) {
			foreach ($dateRange as $date) {
				$data[date("Y-m", strtotime($date["dateStart"]))] = $obj->fetchDischargesByFacility($date["dateStart"], $date["dateEnd"], $_facility, $_discharge_to, $_dc_disp, $_filterby, $_orderby);
			}
		} 
		
						
		foreach ($dateRange as $date) {
			$countData[date("Y-m", strtotime($date["dateStart"]))] = $obj->countDischargesByFacility($date["dateStart"], $date["dateEnd"], $_facility, $_filterby);
		}
		
							
		$dischargeData = array();
		foreach ($countData as $key => $data) {
			foreach ($data as $k => $d) {
							
				if ($d->dc_to == '') {
					$dischargeData[$key]['No Discharge Location']['dc_count'] = $d->dc_count;
					$dischargeData[$key]['No Discharge Location']['dc_to_count'] = $d->empty_dc_count;
				} else {
					$dischargeData[$key][$d->dc_to]['dc_count'] = $d->dc_count;
					$dischargeData[$key][$d->dc_to]['dc_to_count'] = $d->dc_to_count;
					if ($d->dc_disp != '' && ($d->dc_to == "General Discharge" || $d->dc_to == "Transfer to other facility")) {
						$dischargeData[$key][$d->dc_to][$k]['dc_disp'] = $d->dc_disp;
						$dischargeData[$key][$d->dc_to][$k]['dc_disp_count'] = $d->dc_disp_count;
					}		
				}
							
				
			}
		}
				
				
		smarty()->assign("dischargeData", $dischargeData);
		smarty()->assign("data", $data);
		
		$view = input()->view;
		$urlString = SITE_URL . "/?page=report&action=discharge_type&facility={$facility->pubid}&view={$view}&year={$year}&orderby={$orderby}";
		smarty()->assign('urlString', $urlString);
		
		/*
		 * EXPORT REPORT TO EXCEL
		 *
		 * Note: If export=excel, export to an Excel file, else if export=pdf, export to a PDF file
		 *
		 */
		 
		if (input()->export != '') {
			
			require_once APP_PROTECTED_PATH . "/lib/contrib/Classes/PHPExcel.php";
		 	require_once APP_PROTECTED_PATH . "/lib/contrib/Classes/PHPExcel/IOFactory.php";
			
			$styleArray = array(
				'font' => array(
					'bold' => true,
					'size' => '14'
				)
			);
			
			if (input()->export == 'excel') {
				$objPHPExcel = PHPExcel_IOFactory::load(APP_PATH . "/public/templates/blank.xlsx");
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				// Output to Excel file
				header('Pragma: ');
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				// Name the file
				header("Content-Disposition: attachment; filename=" . $facility->name . " " . $reportType . ".xlsx");
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			} else {
				// Export to a PDF file
				$objPHPExcel = PHPExcel_IOFactory::load(APP_PATH . "/public/templates/blank.xlsx");
				$rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
				$rendererLibrary = 'mPDF5.3';
				$rendererLibraryPath = APP_PROTECTED_PATH . "/lib/contrib/Libraries/" . $rendererLibrary;
				$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
				
				if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
					die('NOTICE: Please set the $rendererName and $rendererLibraryPath values' . EOL . 'at the top of this script as appropriate for your directory structure');	
				}
				
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
				// Output to PDF file
				header('Pragma: ');
				header("Content-type: application/pdf");
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');		
			}
			
			PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
			$objPHPExcel->getProperties()->setTitle("$facility->name");
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader("&24" . $facility->name . ' ' . $reportType);
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter("&RPrinted: " . date("m/d/y g:i a", strtotime("now")));
			
			$row = 3;
			$col = "A";
			
			
			foreach ($dischargeData as $key => $data) {
				$col = "A";
				$objPHPExcel->getActiveSheet()->mergeCells("A1:D1");
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow();
				$objPHPExcel->getActiveSheet()->setCellValue("A1", $facility->name . " Discharge Report");
				$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A".$row, date("F Y", strtotime($key)));
				$objPHPExcel->getActiveSheet()->getStyle("A".$row)->applyFromArray($styleArray);
				
				$objPHPExcel->getActiveSheet()->setCellValue("D".$row, "# of Discharges");
				$objPHPExcel->getActiveSheet()->getStyle("D".$row)->applyFromArray($styleArray);
				
				$objPHPExcel->getActiveSheet()->setCellValue("E".$row, "% of Discharges");
				$objPHPExcel->getActiveSheet()->getStyle("E".$row)->applyFromArray($styleArray);
				
				foreach(range('C','E') as $columnID) {
				    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				        ->setAutoSize(true);
				}
				
				$row++;
				$col++;
				foreach ($data as $k => $d) {	
					$objPHPExcel->getActiveSheet()->mergeCells("B".$row.":C".$row);			
					$objPHPExcel->getActiveSheet()->setCellValue("B".$row, $k);
					$objPHPExcel->getActiveSheet()->setCellValue("D".$row, $d['dc_to_count']);
					$objPHPExcel->getActiveSheet()->setCellValue("D".$row, number_format(($d['dc_to_count']/$d['dc_count'])*100, 1)."%");
					foreach ($d as $n => $i) {
						if (is_numeric($n)) {
							$row++;
							$objPHPExcel->getActiveSheet()->getColumnDimension("C".$row)->setAutoSize(true);
							$objPHPExcel->getActiveSheet()->setCellValue("C".$row, $i['dc_disp']);
							$objPHPExcel->getActiveSheet()->setCellValue("D".$row, $i['dc_disp_count']);
							$objPHPExcel->getActiveSheet()->setCellValue("E".$row, number_format(($i['dc_disp_count']/$d['dc_count'])*100, 1)."%");
						}
						
					}
					$row++;
				}
								
			}
			
			
			// Write file to the browser
			$objWriter->save("php://output");
			exit;

						
			
		}
		
		
						
	}
		
	
	
	
	
	
	
	/*
	 * -------------------------------------------------------------
	 *  DISCHARGE HISTORY REPORT
	 * -------------------------------------------------------------
	 * 
	 */
	
	public function discharge_history() {
		if (auth()->getRecord()->isAdmissionsCoordinator() != 1) {
			redirect(auth()->getRecord()->homeURL());
		}
		// Get the users' facilities
		$facilities = auth()->getRecord()->getFacilities();	
		// Get selected facility info
		$facility = new CMS_Facility(input()->facility);
		
		// Start from 
		$start_date = date('Y-m-d 00:00:01', strtotime(input()->week_start));
		$end_date = date('Y-m-d 23:59:59', strtotime($start_date . ' + 6 days'));
				
		// Get discharges for the last 4 weeks
		$obj = new CMS_Schedule();
		$dischargeHistory = $obj->fetchDischargeHistory($facility->id, $start_date, $end_date);
				
		smarty()->assign('dischargeHistory', $dischargeHistory);
		smarty()->assign('facility', $facility);
		smarty()->assign('facilities', $facilities);
		smarty()->assign('start_date', input()->week_start);
	}
	
	
	
	
	
	
	
	
	
	
	/*
	 * -------------------------------------------------------------
	 *  LENGTH OF STAY REPORT
	 * -------------------------------------------------------------
	 * 
	 */
	
	public function length_of_stay() {
		
		$facility = new CMS_Facility(input()->facility);
		if (! $facility->valid()) {
			$facility = null;
		} 

		$_facility = $facility->id;
		
		if (input()->isMicro == 1) {
			smarty()->assign("isMicro", true);
		} else {
			smarty()->assign("isMicro", false);
		}
		
		smarty()->assign("facilityId", $_facility);
		smarty()->assign("facility", $facility);	
										
		if (input()->view != '') {
			$_view = input()->view;
		} else {
			$_view = false;
		}
		
		if (input()->year != '') {
			$_year = input()->year;
		} else {
			$_year = false;
		}	
		
		$filterByOpts = array(
			'' => 'All',
			"Medicare" => "Medicare",
			"HMO" => "HMO",
			"Rugs" => "Rugs",
			"Private Pay" => "Private Pay"
		);	
		
		smarty()->assign("facilityPubId", $facility->pubid);
		smarty()->assign("filterByOpts", $filterByOpts);
		smarty()->assign("orderby", input()->orderby);
		smarty()->assign("view", input()->view);
		smarty()->assign("year", input()->year);
				
		$dateRange = array();
		if (input()->year != "") {
			$year = input()->year;
			if (input()->view == "month") {
				for ($i=1;$i<=12;$i++) {
					$timestamp = mktime (0, 0, 0, $i, 1, $year);
					$dateRange[$i]["dateStart"] = "$year-$i-01 00:00:01";
					$dateRange[$i]["dateEnd"] = "$year-$i-" . date("t", $timestamp) . " 23:59:59";
					$dateRange[$i]["facility"] = $facility->id;
				}
			} elseif (input()->view == "quarter") {
				$i = 1;
				while ($i<=12) {
					$end = $i + 2;
					$dateRange[$i]["dateStart"] = "$year-$i-01 00:00:01";
					$dateRange[$i]["dateEnd"] = "$year-$end-" . date("t", $timestamp) . " 23:59:59";
					$dateRange[$i]["facility"] = $facility->id;
					$i = $end += 1;
				}
			} elseif (input()->view == "year") {
				$i = 1;
				while ($i <= 1) {
					$dateRange[$i]["dateStart"] = $year . "-01-01 00:00:01";
					$dateRange[$i]["dateEnd"] = $year . "-12-" . date("t", $timestamp) . " 23:59:59";
					$dateRange[$i]["facility"] = $facility->id;
					$i ++;
				}
			}
		}
				
		if (input()->filterby != '') {
			$_filterby = input()->filterby;
		} else {
			$_filterby = false;
		}
		
		smarty()->assign("filterby", $_filterby);
		
		
		
		/*
		 *
		 * -------------------------------------------------------------
		 *  Calculate the Length of Stay
		 * -------------------------------------------------------------
		 * 
		 * Need to get all the discharges from the facility for the selected time period.  The query will always pull
		 * data for the entire year, but it can be grouped by month, quarter, or the entire year (on one line).  Need
		 * to get a count of the discharges by type (General Discharge, Transfer to another AHC facility, Transfer to 
		 * other facility, Discharge to Hospital, or Discharge to Hospital (Bed Hold) -- for older db entries --. Also
		 * need to get a list of all discharges with datetime_admit and datetime_discharge during the time period to
		 * calculate the length of stay.  The length of stay will then be averaged for the time period.
		 *
		 */
		
		
		$obj = CMS_Schedule::generate();
		// Get all discharges for the time period
		foreach ($dateRange as $date) {
			$discharges[date ("M Y", strtotime($date["dateStart"]))] = $obj->fetchAllDischarges($date["dateStart"], $date["dateEnd"], $date["facility"], $_filterby);
		}
			
		
									
		// Loop through array, separate out by discharge_to, get length of stay for each patient
		$lengthByPatient = array();
		
		foreach ($discharges as $key => $discharge) {
			foreach ($discharge as $k => $d) {
				$lengthByPatient[$key]["totalOfLoS"] += $this->LoS($d->datetime_discharge, $d->datetime_admit);
				if ($d->discharge_to == '') {
					$lengthByPatient[$key]['No Discharge Location'][$k]["lengthOfStay"] = $this->LoS($d->datetime_discharge, $d->datetime_admit); 
					$lengthByPatient[$key]['No Discharge Location'][$k]["datetime_discharge"] = $d->datetime_discharge;
				} else {
					$lengthByPatient[$key][$d->discharge_to][$k]["lengthOfStay"] = $this->LoS($d->datetime_discharge, $d->datetime_admit); 
					$lengthByPatient[$key][$d->discharge_to][$k]["datetime_discharge"] = $d->datetime_discharge;
				}
			}
		}
		
				
		// Remove lengthOfStay from array to find the highest and lowest dates 
		foreach ($lengthByPatient as $key => $length) {
			unset ($length["totalOfLoS"]);
			foreach ($length as $k => $l) {
				foreach ($l as $v) {
					unset ($v["lengthOfStay"]);
					$dateSearch[$key][$k][] = strtotime($v["datetime_discharge"]);
				}
			}
		}
		
					
		// Find highest and lowest dates
		foreach ($dateSearch as $key => $search) {
			foreach ($search as $k => $s) {
				$dates[$key][$k]["minDate"] = date("Y-m-d", min ($s));
				$dates[$key][$k]["maxDate"] = date("Y-m-d", max ($s));
			}
		}
		
						
		// Count the number of discharges and then combine LoS per patient into a total for the period
		foreach ($lengthByPatient as $key => $length) {
			unset ($length["totalOfLoS"]);
			foreach ($length as $k => $l) {
				$patientData[$key][$k]["totalDischarges"] = count($l);
				foreach ($l as $v) {
					$patientData[$key][$k]["lengthOfStay"] += $v["lengthOfStay"];
				}
			}
		}
				
		// Calculate the average length of stay for each discharge type						
		foreach ($patientData as $key => $data) {
			foreach ($data as $k => $d) {
				$returnData[$key][$k]["totalDischarges"] = $d["totalDischarges"];
				$returnData[$key][$k]["lengthOfStay"] = round($d["lengthOfStay"]/$d["totalDischarges"], 2);
			}
		}
		
					
		// Get total LoS for the selected time period (month, quarter, or year)
		foreach ($returnData as $key => $data) {
			foreach ($data as $d) {
				$sectionLoS[$key]["numOfDischarges"] += $d["totalDischarges"];
				$sectionLoS[$key]["totalNumOfDays"] = $lengthByPatient[$key]["totalOfLoS"];
			}
		}
		
		
		$result = array_merge_recursive($returnData, $dates);
				
		
		// Put total dischages and average LoS into each date section
		foreach ($result as $key => $r) {
			$result[$key]["totalDischarges"] = $sectionLoS[$key]["numOfDischarges"];
			$result[$key]["avgLoS"] = $sectionLoS[$key]["totalNumOfDays"]/$sectionLoS[$key]["numOfDischarges"];
		}
		
				
		// Total Year info
		foreach ($result as $key => $r) {
			$yearInfo["totalDischarges"] += $r["totalDischarges"];
			foreach ($sectionLoS as $length) {
				$totalDays += $length["totalNumOfDays"];
				$totalDischarges += $length["numOfDischarges"];
				
			}
			$yearInfo["totalAvgLoS"] = $totalDays/$totalDischarges;
		}
																	
		smarty()->assign("lengthOfStay", $result);
		smarty()->assign("sectionLoS", $sectionLoS);
		smarty()->assign("yearInfo", $yearInfo);
		
		$view = input()->view;
		$urlString = SITE_URL . "/?page=report&action=length_of_stay&facility={$facility->pubid}&view={$view}&year={$year}&orderby={$orderby}";
		smarty()->assign('urlString', $urlString);
		
		
		/*
		 * EXPORT REPORT TO EXCEL
		 *
		 * Note: If export=excel, export to an Excel file, else if export=pdf, export to a PDF file
		 *
		 */
		
		if (input()->export != '') {
													
			require_once APP_PROTECTED_PATH . "/lib/contrib/Classes/PHPExcel.php";
		 	require_once APP_PROTECTED_PATH . "/lib/contrib/Classes/PHPExcel/IOFactory.php";
			
			$styleArray = array(
				'font' => array(
					'bold' => true,
					'size' => '14'
				)
			);
			
			if (input()->export == 'excel') {
				$objPHPExcel = PHPExcel_IOFactory::load(APP_PATH . "/public/templates/blank.xlsx");
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				// Output to Excel file
				header('Pragma: ');
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				// Name the file
				header("Content-Disposition: attachment; filename=" . $facility->name . " " . $reportType . ".xlsx");
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			} else {
				// Export to a PDF file
				$objPHPExcel = PHPExcel_IOFactory::load(APP_PATH . "/public/templates/blank.xlsx");
				$rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
				$rendererLibrary = 'mPDF5.3';
				$rendererLibraryPath = APP_PROTECTED_PATH . "/lib/contrib/Libraries/" . $rendererLibrary;
				$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
				
				if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
					die('NOTICE: Please set the $rendererName and $rendererLibraryPath values' . EOL . 'at the top of this script as appropriate for your directory structure');	
				}
				
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
				// Output to PDF file
				header('Pragma: ');
				header("Content-type: application/pdf");
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');		
			}
			
			PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
			$objPHPExcel->getProperties()->setTitle("$facility->name");
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader("&24" . $facility->name . ' ' . $reportType);
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter("&RPrinted: " . date("m/d/y g:i a", strtotime("now")));
			
			$row = 3;
			$col = "A";
			
			
			foreach ($result as $key => $data) {
				$col = "A";
				$objPHPExcel->getActiveSheet()->mergeCells("A1:D1");
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow();
				$objPHPExcel->getActiveSheet()->setCellValue("A1", $facility->name . " Discharge Service Disposition Report");
				$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A".$row, date("F Y", strtotime($key)));
				$objPHPExcel->getActiveSheet()->getStyle("A".$row)->applyFromArray($styleArray);
				
				$objPHPExcel->getActiveSheet()->setCellValue("C".$row, "Total Discharges");
				$objPHPExcel->getActiveSheet()->getStyle("C".$row)->applyFromArray($styleArray);
				
				$objPHPExcel->getActiveSheet()->setCellValue("D".$row, "Average Length of Stay");
				$objPHPExcel->getActiveSheet()->getStyle("D".$row)->applyFromArray($styleArray);
				
				
				foreach(range('A','D') as $columnID) {
				    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				        ->setAutoSize(true);
				}
				
				$row++;
								
				foreach ($data as $k => $d) {
					if ($k != "totalDischarges" && $k != "avgLoS") {
						$objPHPExcel->getActiveSheet()->setCellValue("B".$row, $k);
						$objPHPExcel->getActiveSheet()->setCellValue("C".$row, $d['totalDischarges']);
						$objPHPExcel->getActiveSheet()->setCellValue("D".$row, $d['lengthOfStay']);
						$row++;
					}
				}
								
			}
			
			
			// Write file to the browser
			$objWriter->save("php://output");
			exit;

						
			
		}
		
	}
	
	
	
	/*
	 * -------------------------------------------------------------
	 *  LENGTH OF STAY DETAILS
	 * -------------------------------------------------------------
	 * 
	 */
	
	public function los_details() {
		$facility = new CMS_Facility(input()->facility);
		if (! $facility->valid()) {
			$facility = null;
		} 
		
		$_facility = $facility->id;
		smarty()->assign("facilityId", $_facility);
		smarty()->assign("facility", $facility);
		
		// If discharge_to is empty redirect
		if (input()->discharge_to == '') {
			feedback()->error("No discharge type was selected.  Please try again.");
			$this->redirect(SITE_URL . "/?page=report&action=length_of_stay&facility=$facility->pubid");
		} elseif(input()->discharge_to == "No Discharge Location") {
			$dischargeTo = "";
		} else {
			$dischargeTo = input()->discharge_to;
		}
		
		if (input()->date_start == '') {
			feedback()->error("Could not find the start date.  Please try again.");
			$this->redirect(SITE_URL . "/?page=report&action=length_of_stay&facility=$facility->pubid");
		} else {
			$dateStart = input()->date_start;
		}
		
		if (input()->date_end == '') {
			feedback()->error("Could not find the end date.  Please try again.");
			$this->redirect(SITE_URL . "/?page=report&action=length_of_stay&facility=$facility->pubid");
		} else {
			$dateEnd = input()->date_end;
		}		
				
		$getter = new CMS_Schedule;
		$getter->paginationOn();
		$getter->paginationSetSliceSize(50);
		$slice = trim(strip_tags(input()->slice));
		if ($slice == '' || ! Validate::is_natural($slice)->success()) {
		  $slice = 1;
		}
		$getter->paginationSetSlice($slice);
		
		$details = $getter->fetchLosDetails($dischargeTo, $dateStart, $dateEnd, $_facility);
		
		$totalDischarges = count($details);
			
		$patients = array();
		foreach ($details as $detail) {
			$detail->length_of_stay = $this->LoS($detail->datetime_discharge, $detail->datetime_admit);
			$detail->name = $detail->last_name . ", " . $detail->first_name;			
			$totalLoS += $detail->length_of_stay;
			$avgLoS = round ($totalLoS/$totalDischarges, 2);
		}	
										
		smarty()->assignByRef("getter", $getter);	
		smarty()->assign("avgLoS", $avgLoS);
		smarty()->assign("patients", $details);
	}
	
	
	
	
	
	
	/*
	 * -------------------------------------------------------------
	 *  NOT ADMITTED REPORT
	 * -------------------------------------------------------------
	 * 
	 */
	
	public function cancelled() {
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

		
		// Create and assign report types, orderby, and filterby options		
		$orderByOpts = array(
			'datetime_admit_ASC' => 'Desired Admission Date (oldest first)',
			'datetime_admit_DESC' => 'Desired Admission Date (most recent first)',
			'hospital_name_ASC' => 'Referall Source (Hospital name)',
			'paymethod_ASC' => 'Payment Method'
		);
		
		smarty()->assign("reportTypes", $this->reportTypes);
		smarty()->assign("orderByOpts", $orderByOpts);
		
		
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
		
		$obj = CMS_Schedule::generate();
		$cancelled = $obj->getCancelledInquiries($_dateStart, $_dateEnd, $_facility, $_orderby);

		smarty()->assignByRef("cancelled", $cancelled);
	}
	
	
	
	
	
	
	/*
	 * -------------------------------------------------------------
	 *  RE-ADMISSION REPORT
	 * -------------------------------------------------------------
	 * 
	 */
	
	public function readmission() {
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
		
		if (input()->summary != "") {
			$summary = input()->summary;
		}
		
		if (input()->readmit_type != "") {
			$readmitType = input()->readmit_type;
		} else {
			$readmitType = "";
		}
		
		smarty()->assign("summary", $summary);
		smarty()->assignByRef("dateStart", $_dateStart);
		smarty()->assignByRef("dateEnd", $_dateEnd);
		smarty()->assign("readmitType", $readmitType);

		
		// Create and assign report types, orderby, and filterby options		
		$orderByOpts = array(
			'datetime_admit_DESC' => 'Admission Date (most recent first)',
			'datetime_admit_ASC' => 'Admission Date (oldest first)',
			'patient_name_ASC' => 'Patient Name (A &rarr; Z)',
			'readmit_type' => 'Type of Re-Admission (A &rarr; Z)',
			'physician_ASC' => 'Attending Physician (A &rarr; Z)'
		);
		
		smarty()->assign("reportTypes", $this->reportTypes);
		smarty()->assign("orderByOpts", $orderByOpts);
		
		
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
		
		$obj = CMS_Schedule::generate();
		$readmit = $obj->fetchReadmitsByFacility($_dateStart, $_dateEnd, $_facility, $_orderby, $readmitType);

		smarty()->assignByRef("readmit", $readmit);
		
		
		
		$view = input()->view;
		$dateStart = date('m/d/Y', strtotime($_dateStart));
		$dateEnd = date('m/d/Y', strtotime($_dateEnd));
		
		$urlString = SITE_URL . "/?page=report&action=readmission&facility={$facility->pubid}&start_date={$dateStart}&end_date={$dateEnd}&orderby={$orderby}&filterby={$filterby}&viewby={$viewby}&summary={$summary}&readmit_type={$readmitType}";
		smarty()->assign('urlString', $urlString);
		
		
		/*
		 * EXPORT REPORT TO EXCEL
		 *
		 * Note: If export=excel, export to an Excel file, else if export=pdf, export to a PDF file
		 *
		 */
		
		if (input()->export != '') {
			
			$dataSet = array();
			
			foreach ($readmit as $r) {
				$dataSet[0] = array('Patient Name', 'Re-Admit Date', 'Type of Re-Admission', 'Hospital', 'Attending Physician');
				$dataSet[] = array(
					$r->getPatient()->fullName(),
					date('m/d/Y', strtotime($r->datetime_admit)),
					$r->readmit_type,
					$r->getDischargeLocation(),
					$r->getPhysicianName()
				);
			}
									
			$this->export($facility, 'Re-Admission Report', $dataSet);
					
		}


	}
	
	
	
	
	
	/*
	 * -------------------------------------------------------------
	 *  RETURNED TO HOSPITAL REPORT
	 * -------------------------------------------------------------
	 * 
	 */
	
	public function returned_to_hospital() {
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
		
		
		// Create and assign report types, orderby, and filterby options		
		$orderByOpts = array(
			'datetime_sent_DESC' => 'Sent to Hospital Date (most recent first)',
			'datetime_sent_ASC' => 'Sent to Hospital Date (oldest first)',
			'patient_name_ASC' => 'Patient Name (A &rarr; Z)',
			'physician_ASC' => 'Attending Physician (A &rarr; Z)'
		);
		
		$filterByOpts = array(
			'discharge_disposition' => 'Discharge Disposition',
			'service_disposition' => 'Home Health',
			'physician' => 'Attending Physician'
		);
		
		smarty()->assign("orderByOpts", $orderByOpts);
		smarty()->assign("filterByOpts", $filterByOpts);
		
		
		// Need to get info for reports
		if (input()->orderby == '') {
			$_orderby = false;
		} else {
			switch (input()->orderby) {
				case "patient_name_ASC":
				$_orderby = "last_name ASC";
				break;

				case "datetime_sent_DESC":
				$_orderby = "datetime_sent DESC";
				break;

				case "datetime_sent_ASC":
				$_orderby = "datetime_sent ASC";
				break;

				case "physician_ASC":
				$_orderby = "physician_name ASC";
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
		


		// $_facility = $facility->id;
		$obj = CMS_Schedule::generate();
		$returnedReport = $obj->getPatientsSentToHospital($_dateStart, $_dateEnd, $_facility, $_orderby);
				
		// Get datetiime_admit for those patients who have re-admitted

		// Calculate Re-Admission Rate
		$sentCount = count($obj->getPatientsSentToHospital($_dateStart, $_dateEnd, $_facility));
		$admittedCount = count($obj->fetchAdmitsByFacility($_dateStart, $_dateEnd, $_facility));
		
		$readmitRate = number_format(($sentCount/$admittedCount) * 100, 1);

		smarty()->assign("readmitRate", $readmitRate);
		smarty()->assign("orderby", input()->orderby);
		smarty()->assign("filterby", input()->filterby);
		smarty()->assignByRef("returnedReport", $returnedReport);
		
		
		$view = input()->view;
		$dateStart = date('m/d/Y', strtotime($_dateStart));
		$dateEnd = date('m/d/Y', strtotime($_dateEnd));
		
		$urlString = SITE_URL . "/?page=report&action=returned_to_hospital&facility={$facility->pubid}&start_date={$dateStart}&end_date={$dateEnd}&orderby={$orderby}&filterby={$filterby}&viewby={$viewby}&summary={$summary}";
		
		smarty()->assign('urlString', $urlString);
		
		
		/*
		 * EXPORT REPORT TO EXCEL
		 *
		 * Note: If export=excel, export to an Excel file, else if export=pdf, export to a PDF file
		 *
		 */
		
		if (input()->export != '') {
			
			$dataSet = array();
			foreach ($returnedReport as $r) {
				if ($r->datetime_returned != '') {
					$returnDate = date('m/d/Y', strtotime($r->datetime_returned));
				} else {
					$returnDate = '';
				}
				$dataSet[0] = array('Patient Name', 'Hospital', 'Sent', 'Comment', 'Attending Physician', 'Re-Admitted to AHC');
				$dataSet[] = array(
					$r->getPatient()->fullName(),
					$r->getDischargeHospital(),
					date('m/d/Y', strtotime($r->datetime_sent)),
					$r->comment,
					$r->getPhysicianName(),
					$returnDate,
				);
			}
			
			
			
			$this->export($facility, 'Returned to Hospital Report', $dataSet);
					
		}


		
		

	}
	
	
	
	
	
	/*
	 * -------------------------------------------------------------
	 *  FACILITY TRANSFER REPORT
	 * -------------------------------------------------------------
	 * 
	 */
	
	public function facility_transfer() {
		$facility = new CMS_Facility(input()->facility);
		if (! $facility->valid()) {
			$facility = null;
		} 

		$_facility = $facility->id;
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
		
		// Get all transfers between facilities
		$obj = new CMS_Schedule();
		$transfers = $obj->fetchTransferReport($_facility, $_dateStart, $_dateEnd);
				
		smarty()->assignByRef("dateStart", $_dateStart);
		smarty()->assignByRef("dateEnd", $_dateEnd);
		smarty()->assign("facilityId", $_facility);
		smarty()->assign("facility", $facility);
		smarty()->assign("transfers", $transfers);	
		
		$startDate = date('m/d/Y', strtotime($_dateStart));
		$endDate = date('m/d/Y', strtotime($_dateEnd));
		
		$urlString = SITE_URL . "/?page=report&action=facility_transfer&facility={$facility->pubid}&start_date={$startDate}&end_date={$endDate}&orderby={$orderby}$filterby={$filterby}&viewby={$viewby}&summary={$summary}";
		smarty()->assign('urlString', $urlString);
		
		if (input()->export != '') {
			$dataSet = array();
			
			foreach ($transfers as $t) {
				$dataSet[0] = array('Patient Name', 'Admission Date', 'Transfer Facility');
				$dataSet[] = array(
					$t->last_name . ', ' . $t->first_name,
					date('m/d/Y', strtotime($t->datetime_admit)),
					$t->transfer_from
				);
			}
			
			$this->export($facility, 'Facility Transfer Report', $dataSet);
			
		}
		
	}
	
	
	
	
	
	
	/*
	 * -------------------------------------------------------------
	 *  CALCULATE LENGTH OF STAY
	 * -------------------------------------------------------------
	 * 
	 */
			
	public function LoS($datetime_discharge, $datetime_admit) {

		$d = strtotime($datetime_discharge);
		$a = strtotime($datetime_admit);

		$dateDiff = abs($d - $a);

		return round($dateDiff/86400);
	}
	
	
	
	
	
	
	/*
	 * -------------------------------------------------------------
	 *  GET THE CURRENT CALENDAR QUARTER
	 * -------------------------------------------------------------
	 * 
	 */
	
	public static function getQuarter($month = '') {
		$month = date("F", strtotime($month));
		if ($month == "January") {
			return "1st Quarter";
		} elseif ($month == "April") {
			return "2nd Quarter";
		} elseif ($month == "July") {
			return "3rd Quarter";
		} elseif ($month == "October") {
			return "4th Quarter";
		}
		return false;
	}
	
	
	
	
	/*
	 * -------------------------------------------------------------
	 *  EXPORT TO AND EXCEL OR PDF FILE
	 * -------------------------------------------------------------
	 * 
	 */
	 
	 public function export($facility, $reportType, $dataSet) {
	 	
	 	require_once APP_PROTECTED_PATH . "/lib/contrib/Classes/PHPExcel.php";
			
		$styleArray = array(
			'font' => array(
				'bold' => true,
			)
		);	
			
		 if (input()->export == 'excel') {
			// Export to excel
			$objPHPExcel = PHPExcel_IOFactory::load(APP_PATH . "/public/templates/blank.xlsx");
		} else {
			// Export to a PDF file
			$objPHPExcel = PHPExcel_IOFactory::load(APP_PATH . "/public/templates/blank.xlsx");
			$rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
			$rendererLibrary = 'mPDF5.3';
			$rendererLibraryPath = APP_PROTECTED_PATH . "/lib/contrib/Libraries/" . $rendererLibrary;
			$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
			
			if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
				die('NOTICE: Please set the $rendererName and $rendererLibraryPath values' . EOL . 'at the top of this script as appropriate for your directory structure');
			}
		}
		PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
		
		$objPHPExcel->getProperties()->setTitle("$facility->name");
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader("&24" . $facility->name . ' ' . $reportType);
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter("&RPrinted: " . date("m/d/y g:i a", strtotime("now")));
		
		
		// Set header info
		foreach ($dataSet as $data) {
			$numOfCols = count ($data);
		}
		
		$col = "A";
		
		for ($i = 0; $i < ($numOfCols - 1); $i++) {
			$col++;
		}
		
		$maxCol = $col;
				
		$objPHPExcel->getActiveSheet()->mergeCells("A1:" . $col . "1");
		$objPHPExcel->getActiveSheet()->setCellValue("A1", "$facility->name $reportType");
		
		
		$col = "A";
		$i = 2;
		$testArray = array();
		
		foreach ($dataSet as $data) {
			foreach ($data as $d) {
				$objPHPExcel->getActiveSheet()->setCellValue($col . $i, $d);
				$col++;
			}
			$i++;
			$col = "A";
		}
		
		
		foreach(range('A', $maxCol) as $columnID) {
		    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
		        ->setAutoSize(true);
		}
				
							
		// Include required files
		require_once APP_PROTECTED_PATH . "/lib/contrib/Classes/PHPExcel/IOFactory.php";
		if (input()->export == "excel") {
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			// Output to Excel file
			header('Pragma: ');
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			// Name the file
			header("Content-Disposition: attachment; filename=" . $facility->name . " " . $reportType . ".xlsx");
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
	
	
	
	
	
	/*
	 * -------------------------------------------------------------
	 *  EXPORT A DATE RANGE (MONTH, QUARTER, YEAR) TO AND EXCEL OR PDF FILE
	 * -------------------------------------------------------------
	 * 
	 */
	 
	 public function exportDateRange($facility, $reportType, $dataSet) {
	 					
	}
	
	
	
	public function discharge_service() {
		smarty()->assign("isPrint", input()->isPrint);
		
		$facility = new CMS_Facility(input()->facility);
		if (! $facility->valid()) {
			$facility = null;
		} 

		$_facility = $facility->id;
		smarty()->assign("facilityId", $_facility);
		smarty()->assign("facility", $facility);
		smarty()->assign("view", input()->view);
		smarty()->assign("year", input()->year);
		
		
		/**
		 * Assign dates by month, quarter, or year using the dateRange array below.  This will then query 
		 * the db to count the discharges for the specified time period.
		 *
		 */
		 
		$dateRange = array();
		if (input()->year != "") {
			$year = input()->year;
			if (input()->view == "month") {
				for ($i=1;$i<=12;$i++) {
					$timestamp = mktime (0, 0, 0, $i, 1, $year);
					$dateRange[$i]["dateStart"] = "$year-$i-01 00:00:01";
					$dateRange[$i]["dateEnd"] = "$year-$i-" . date("t", $timestamp) . " 23:59:59";
					$dateRange[$i]["facility"] = $facility->id;
				}
			} elseif (input()->view == "quarter") {
				$i = 1;
				while ($i<=12) {
					$end = $i + 2;
					$dateRange[$i]["dateStart"] = "$year-$i-01 00:00:01";
					$dateRange[$i]["dateEnd"] = "$year-$end-" . date("t", $timestamp) . " 23:59:59";
					$dateRange[$i]["facility"] = $facility->id;
					$i = $end += 1;
				}
			} elseif (input()->view == "year") {
				$i = 1;
				while ($i <= 1) {
					$dateRange[$i]["dateStart"] = $year . "-01-01 00:00:01";
					$dateRange[$i]["dateEnd"] = $year . "-12-" . date("t", $timestamp) . " 23:59:59";
					$dateRange[$i]["facility"] = $facility->id;
					$i ++;
				}
			}
		}
		
		$urlString = SITE_URL . "/?page=report&action=discharge_service&facility=" . $facility->pubid . "&view=" . input()->view . "&year=" . $year;
		
		smarty()->assign('urlString', $urlString);
		
		
				
		$obj = CMS_Schedule::generate();	
		$data = array();
		
		foreach ($dateRange as $date) {
			$data[date("Y-m", strtotime($date["dateStart"]))] = $obj->fetchHHDischargesByFacility($date["dateStart"], $date["dateEnd"], $_facility);
		}
		foreach ($data as $k => $d) {
			foreach ($d as $i) {
				if ($i->discharges == 0) {
					unset ($data[$k]);
				}
			}
		}
				
		smarty()->assign('data', $data);
		
		if (input()->export != '') {
									
			require_once APP_PROTECTED_PATH . "/lib/contrib/Classes/PHPExcel.php";
		 	require_once APP_PROTECTED_PATH . "/lib/contrib/Classes/PHPExcel/IOFactory.php";
			
			$styleArray = array(
				'font' => array(
					'bold' => true,
					'size' => '14'
				)
			);
			
			if (input()->export == 'excel') {
				$objPHPExcel = PHPExcel_IOFactory::load(APP_PATH . "/public/templates/blank.xlsx");
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				// Output to Excel file
				header('Pragma: ');
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				// Name the file
				header("Content-Disposition: attachment; filename=" . $facility->name . " " . $reportType . ".xlsx");
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			} else {
				// Export to a PDF file
				$objPHPExcel = PHPExcel_IOFactory::load(APP_PATH . "/public/templates/blank.xlsx");
				$rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
				$rendererLibrary = 'mPDF5.3';
				$rendererLibraryPath = APP_PROTECTED_PATH . "/lib/contrib/Libraries/" . $rendererLibrary;
				$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
				
				if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
					die('NOTICE: Please set the $rendererName and $rendererLibraryPath values' . EOL . 'at the top of this script as appropriate for your directory structure');	
				}
				
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
				// Output to PDF file
				header('Pragma: ');
				header("Content-type: application/pdf");
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');		
			}
			
			PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
			$objPHPExcel->getProperties()->setTitle("$facility->name");
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader("&24" . $facility->name . ' ' . $reportType);
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter("&RPrinted: " . date("m/d/y g:i a", strtotime("now")));
			
			$row = 3;
			$col = "A";
			
			
			foreach ($data as $k => $d) {
				$col = "A";
				$objPHPExcel->getActiveSheet()->mergeCells("A1:E1");
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow();
				$objPHPExcel->getActiveSheet()->setCellValue("A1", $facility->name . " Discharge Service Disposition Report");
				$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray);
				
				$objPHPExcel->getActiveSheet()->setCellValue("A".$row, date("F Y", strtotime($k)));
				$objPHPExcel->getActiveSheet()->getStyle("A".$row)->applyFromArray($styleArray);
				
				
				foreach(range('A','E') as $columnID) {
				    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
				        ->setAutoSize(true);
				}
				
								
				foreach ($d as $n) {
					$discharges = $n->discharges;
					$totalHHDcs = $n->AhcHomeHealth + $n->OtherHomeHealth + $n->OutpatientTherapy;
					foreach ($n as $d => $i) {			
						if ($d == 'AhcHomeHealth' && $i != 0) {
							$objPHPExcel->getActiveSheet()->setCellValue("B".$row, 'AHC Home Health');
							$objPHPExcel->getActiveSheet()->setCellValue("C".$row, $i);
							$objPHPExcel->getActiveSheet()->setCellValue("D".$row, number_format(($i/$discharges)*100, 2). "%");
							$objPHPExcel->getActiveSheet()->setCellValue("E".$row, number_format(($i/$totalHHDcs)*100, 2). "%");
							$row++;
						} elseif ($d == 'OtherHomeHealth' && $i != 0) {
							$objPHPExcel->getActiveSheet()->setCellValue("B".$row, 'Other Home Health');
							$objPHPExcel->getActiveSheet()->setCellValue("C".$row, $i);
							$objPHPExcel->getActiveSheet()->setCellValue("D".$row, number_format(($i/$discharges)*100, 2). "%");
							$objPHPExcel->getActiveSheet()->setCellValue("E".$row, number_format(($i/$totalHHDcs)*100, 2). "%");
							$row++;
						} elseif ($d == 'OutpatientTherapy' && $i != 0) {
							$objPHPExcel->getActiveSheet()->setCellValue("B".$row, 'Outpatient Therapy');
							$objPHPExcel->getActiveSheet()->setCellValue("C".$row, $i);
							$objPHPExcel->getActiveSheet()->setCellValue("D".$row, number_format(($i/$discharges)*100, 2). "%");
							$objPHPExcel->getActiveSheet()->setCellValue("E".$row, number_format(($i/$totalHHDcs)*100, 2). "%");
							$row++;
						} elseif ($d == 'NoServices' && $i != 0) {
							$objPHPExcel->getActiveSheet()->setCellValue("B".$row, 'Declined Services');
							$objPHPExcel->getActiveSheet()->setCellValue("C".$row, $i);
							$objPHPExcel->getActiveSheet()->setCellValue("D".$row, number_format(($i/$discharges)*100, 2). "%");
							$objPHPExcel->getActiveSheet()->setCellValue("E".$row, number_format(($i/$totalHHDcs)*100, 2). "%");
							$row++;
						} 
						
					}
				}
								
			}
			
			
			// Write file to the browser
			$objWriter->save("php://output");
			exit;

						
			
		}
		
	}
	
	
	
	public function discharge_service_details() {
	
		smarty()->assign("isPrint", input()->isPrint);
		
		$facility = new CMS_Facility(input()->facility);
		if (! $facility->valid()) {
			$facility = null;
		} 

		$_facility = $facility->id;
		smarty()->assign("facilityId", $_facility);
		smarty()->assign("facility", $facility);
		smarty()->assign("view", input()->view);
		smarty()->assign("year", input()->year);
			
		if (input()->date_start != '') {
			$_date_start = date("Y-m-d 00:00:01", strtotime(input()->date_start));
			$month = date("F Y", strtotime(input()->date_start));
			if (input()->view == "month") {
				$_date_end = date('Y-m-d 23:59:59', strtotime('last day of ' . $month));
			}
		}
		
		$obj = CMS_Schedule::generate();	
		$data = array();

	
		if (input()->type != '') {
		
			$_type = input()->type;
			
			if ($_type == "ahc_home_health") {
				$_type = "AHC Home Health";
			} elseif ($_type == "other_home_health") {
				$_type = "Other Home Health";
			} elseif ($_type == "outpatient_therapy") {
				$_type = "Outpatient Therapy";
			} elseif ($_type == "declined_services") {
				$_type = "No Services";
			} 
		
			
			$data = $obj->fetchDischargesByServiceDisposition($_date_start, $_date_end, $_facility, $_type);
			smarty()->assign('type', $_type);
		} 
		
		
		if (input()->location_id != '') {
			$location_id = input()->location_id;
			$data = $obj->fetchDischargesByHomeHealthName($_date_start, $_date_end, $_facility, $location_id);
			smarty()->assign('type', 'Other Home Health');
		}
		
		$urlString = SITE_URL . "/?page=report&action=discharge_service_details&facility=" . $facility->pubid . "&view=" . input()->view . "&year=" . input()->year . "&date_start=" . date('Y-m-d', strtotime($_date_start)) . "&type=" . input()->type . "&location_id=" . $location_id;
		
		smarty()->assign('urlString', $urlString);
		smarty()->assign('data', $data);
		
		
		if (input()->export != '') {
															
			require_once APP_PROTECTED_PATH . "/lib/contrib/Classes/PHPExcel.php";
		 	require_once APP_PROTECTED_PATH . "/lib/contrib/Classes/PHPExcel/IOFactory.php";
			
			$styleArray = array(
				'font' => array(
					'bold' => true,
					'size' => '14'
				)
			);
			
			if (input()->export == 'excel') {
				$objPHPExcel = PHPExcel_IOFactory::load(APP_PATH . "/public/templates/blank.xlsx");
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				// Output to Excel file
				header('Pragma: ');
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				// Name the file
				header("Content-Disposition: attachment; filename=" . $facility->name . " " . $reportType . ".xlsx");
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			} else {
				// Export to a PDF file
				$objPHPExcel = PHPExcel_IOFactory::load(APP_PATH . "/public/templates/blank.xlsx");
				$rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
				$rendererLibrary = 'mPDF5.3';
				$rendererLibraryPath = APP_PROTECTED_PATH . "/lib/contrib/Libraries/" . $rendererLibrary;
				$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
				
				if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
					die('NOTICE: Please set the $rendererName and $rendererLibraryPath values' . EOL . 'at the top of this script as appropriate for your directory structure');	
				}
				
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
				// Output to PDF file
				header('Pragma: ');
				header("Content-type: application/pdf");
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');		
			}
			
			PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
			$objPHPExcel->getProperties()->setTitle("$facility->name");
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader("&24" . $facility->name . ' ' . $reportType);
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter("&RPrinted: " . date("m/d/y g:i a", strtotime("now")));
			
			$row = 4;
			$col = "A";
			
			$col = "A";
			$objPHPExcel->getActiveSheet()->mergeCells("A1:E1");
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow();
			$objPHPExcel->getActiveSheet()->setCellValue("A1", $facility->name . " Discharge Service Disposition Details Report");
			$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray);
			
			$objPHPExcel->getActiveSheet()->setCellValue("A3", "Patient Name");
			$objPHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($styleArray);
			
			$objPHPExcel->getActiveSheet()->setCellValue("B3", "Discharge To");
			$objPHPExcel->getActiveSheet()->getStyle("B3")->applyFromArray($styleArray);
			
			$objPHPExcel->getActiveSheet()->setCellValue("C3", "Discharge Disposition");
			$objPHPExcel->getActiveSheet()->getStyle("C3")->applyFromArray($styleArray);
			
			$objPHPExcel->getActiveSheet()->setCellValue("D3", "Discharge Location Name");
			$objPHPExcel->getActiveSheet()->getStyle("D3")->applyFromArray($styleArray);
			
			$objPHPExcel->getActiveSheet()->setCellValue("E3", "Discharge Date");
			$objPHPExcel->getActiveSheet()->getStyle("E3")->applyFromArray($styleArray);
							
			foreach(range('A','E') as $columnID) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
			        ->setAutoSize(true);
			}
			
			foreach ($data as $k => $d) {
				$objPHPExcel->getActiveSheet()->setCellValue("A".$row, $d->last_name . ', ' . $d->first_name);
				$objPHPExcel->getActiveSheet()->setCellValue("B".$row, $d->discharge_to);
				$objPHPExcel->getActiveSheet()->setCellValue("C".$row, $d->discharge_disposition);
				$objPHPExcel->getActiveSheet()->setCellValue("D".$row, $d->name);		
				$objPHPExcel->getActiveSheet()->setCellValue("E".$row, date("m/d/Y", strtotime($d->datetime_discharge)));
				$row++;		
			}
			
			
			// Write file to the browser
			$objWriter->save("php://output");
			exit;

						
			
		}

				
	}
	
	
	public function other_home_health_details() {
		smarty()->assign("isPrint", input()->isPrint);
		
		$facility = new CMS_Facility(input()->facility);
		if (! $facility->valid()) {
			$facility = null;
		} 

		$_facility = $facility->id;
		smarty()->assign("facilityId", $_facility);
		smarty()->assign("facility", $facility);
		smarty()->assign("view", input()->view);
		smarty()->assign("year", input()->year);
	
		if (input()->date_start != '') {
			$_date_start = date("Y-m-d 00:00:01", strtotime(input()->date_start));
			$month = date("F Y", strtotime(input()->date_start));
			if (input()->view == "month") {
				$_date_end = date('Y-m-d 23:59:59', strtotime('last day of ' . $month));
			}
		}
		
		$_type = "Other Home Health";
		
		$obj = CMS_Schedule::generate();	
		$data = array();
		
		$data = $obj->fetchDischargesByServiceDisposition($_date_start, $_date_end, $_facility, $_type);
		smarty()->assign('date_start', $_date_start);
		smarty()->assign('type', $_type);
		smarty()->assign('data', $data);
		
		$urlString = SITE_URL . "/?page=report&action=other_home_health_details&facility=" . $facility->pubid . "&view=" . input()->view . "&year=" . input()->year . "&date_start=" . date('Y-m-d', strtotime($_date_start)) . "&type=" . input()->type;
		
		smarty()->assign('urlString', $urlString);


		
		if (input()->export != '') {
															
			require_once APP_PROTECTED_PATH . "/lib/contrib/Classes/PHPExcel.php";
		 	require_once APP_PROTECTED_PATH . "/lib/contrib/Classes/PHPExcel/IOFactory.php";
			
			$styleArray = array(
				'font' => array(
					'bold' => true,
					'size' => '14'
				)
			);
			
			if (input()->export == 'excel') {
				$objPHPExcel = PHPExcel_IOFactory::load(APP_PATH . "/public/templates/blank.xlsx");
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				// Output to Excel file
				header('Pragma: ');
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				// Name the file
				header("Content-Disposition: attachment; filename=" . $facility->name . " " . $reportType . ".xlsx");
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			} else {
				// Export to a PDF file
				$objPHPExcel = PHPExcel_IOFactory::load(APP_PATH . "/public/templates/blank.xlsx");
				$rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
				$rendererLibrary = 'mPDF5.3';
				$rendererLibraryPath = APP_PROTECTED_PATH . "/lib/contrib/Libraries/" . $rendererLibrary;
				$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
				
				if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
					die('NOTICE: Please set the $rendererName and $rendererLibraryPath values' . EOL . 'at the top of this script as appropriate for your directory structure');	
				}
				
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
				// Output to PDF file
				header('Pragma: ');
				header("Content-type: application/pdf");
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');		
			}
			
			PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
			$objPHPExcel->getProperties()->setTitle("$facility->name");
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader("&24" . $facility->name . ' ' . $reportType);
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter("&RPrinted: " . date("m/d/y g:i a", strtotime("now")));
			
			$row = 4;
			$col = "A";
			
			$col = "A";
			$objPHPExcel->getActiveSheet()->mergeCells("A1:G1");
			$objPHPExcel->getActiveSheet()->mergeCells("A2:G2");

			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow();
			$objPHPExcel->getActiveSheet()->setCellValue("A1", $facility->name . " Discharge Service Disposition Details Report");
			$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray);
			
			$objPHPExcel->getActiveSheet()->mergeCells("A3:D3");
			$objPHPExcel->getActiveSheet()->setCellValue("A3", "Home Health Name");
			$objPHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($styleArray);
			
			$objPHPExcel->getActiveSheet()->mergeCells("E3:G3");
			$objPHPExcel->getActiveSheet()->setCellValue("E3", "Number of Discharges");
			$objPHPExcel->getActiveSheet()->getStyle("E3")->applyFromArray($styleArray);
			
							
			foreach(range('A','E') as $columnID) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
			        ->setAutoSize(true);
			}
			
			foreach ($data as $k => $d) {
				$objPHPExcel->getActiveSheet()->mergeCells("A".$row .":D".$row);
				$objPHPExcel->getActiveSheet()->mergeCells("E".$row .":G".$row);
				$objPHPExcel->getActiveSheet()->setCellValue("A".$row, $d->name);
				$objPHPExcel->getActiveSheet()->setCellValue("E".$row, $d->count);
				$row++;		
			}
			
			
			// Write file to the browser
			$objWriter->save("php://output");
			exit;

						
			
		}

		
	}
	
	
	public function discharge_calls() {
		$facility = new CMS_Facility(input()->facility);
		if (! $facility->valid()) {
			$facility = null;
		} 
		
		$_facility = $facility->id;
		smarty()->assign("facilityId", $_facility);
		smarty()->assign("facility", $facility);
		
		$obj = CMS_Schedule::generate();	
		$data = array();
		
		$_date_end = date('Y-m-d 23:59:59', strtotime('now - 1 day'));
		$_date_start = date('Y-m-d 00:00:01', strtotime('- 4 weeks', time()));
				
		$data = $obj->fetchPhoneCallReport($_date_start, $_date_end, $_facility);
		smarty()->assign('data', $data);
		
		$urlString = SITE_URL . "/?page=report&action=discharge_calls&facility=" . $facility->pubid;
		
		smarty()->assign('urlString', $urlString);
		
		if (input()->export != '') {
															
			require_once APP_PROTECTED_PATH . "/lib/contrib/Classes/PHPExcel.php";
		 	require_once APP_PROTECTED_PATH . "/lib/contrib/Classes/PHPExcel/IOFactory.php";
			
			$styleArray = array(
				'font' => array(
					'bold' => true,
					'size' => '20'
				),
				'borders' => array(
					'top' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					),
			        'left' => array(
			          'style' => PHPExcel_Style_Border::BORDER_THIN,
			        ),
			        'right' => array(
			          'style' => PHPExcel_Style_Border::BORDER_THIN,
			        ),
			        'bottom' => array(
			          'style' => PHPExcel_Style_Border::BORDER_THIN,
			        )
			      )
			);
			
			$bodyArray = array(
				'font' => array(
					'size' => '18'
				),
				'borders' => array(
					'top' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					),
			        'left' => array(
			          'style' => PHPExcel_Style_Border::BORDER_THIN,
			        ),
			        'right' => array(
			          'style' => PHPExcel_Style_Border::BORDER_THIN,
			        ),
			        'bottom' => array(
			          'style' => PHPExcel_Style_Border::BORDER_THIN,
			        )
			      )
			);
			
			if (input()->export == 'excel') {
				$objPHPExcel = PHPExcel_IOFactory::load(APP_PATH . "/public/templates/blank.xlsx");
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				// Output to Excel file
				header('Pragma: ');
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				// Name the file
				header("Content-Disposition: attachment; filename=" . $facility->name . " " . $reportType . ".xlsx");
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			} else {
				// Export to a PDF file
				$objPHPExcel = PHPExcel_IOFactory::load(APP_PATH . "/public/templates/blank.xlsx");
				$rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
				$rendererLibrary = 'mPDF5.3';
				$rendererLibraryPath = APP_PROTECTED_PATH . "/lib/contrib/Libraries/" . $rendererLibrary;
				
				
				if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
					die('NOTICE: Please set the $rendererName and $rendererLibraryPath values' . EOL . 'at the top of this script as appropriate for your directory structure');	
				}
				
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
				// Output to PDF file
				header('Pragma: ');
				header("Content-type: application/pdf");
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');		
			}
			
						
			$row = 3;
			$col = "A";
			
			$col = "A";
			$objPHPExcel->getActiveSheet()->mergeCells("A1:I1");

			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow();
			$objPHPExcel->getActiveSheet()->setCellValue("A1", $facility->name . " 30 Day Call Back Report");
			$objPHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray);
			
			$objPHPExcel->getActiveSheet()->setCellValue("A2", "Room");
			$objPHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray($styleArray);
			
			$objPHPExcel->getActiveSheet()->setCellValue("B2", "Patient Name");
			$objPHPExcel->getActiveSheet()->getStyle("B2")->applyFromArray($styleArray);
						
			$objPHPExcel->getActiveSheet()->setCellValue("C2", "Phone Number");
			$objPHPExcel->getActiveSheet()->getStyle("C2")->applyFromArray($styleArray);
			
			$objPHPExcel->getActiveSheet()->setCellValue("D2", "Discharge Date");
			$objPHPExcel->getActiveSheet()->getStyle("D2")->applyFromArray($styleArray);
			
			$objPHPExcel->getActiveSheet()->setCellValue("E2", "Diagnosis");
			$objPHPExcel->getActiveSheet()->getStyle("E2")->applyFromArray($styleArray);
			
			$objPHPExcel->getActiveSheet()->setCellValue("F2", "Admitted From");
			$objPHPExcel->getActiveSheet()->getStyle("F2")->applyFromArray($styleArray);
			
			$objPHPExcel->getActiveSheet()->setCellValue("G2", "D/C Location");
			$objPHPExcel->getActiveSheet()->getStyle("G2")->applyFromArray($styleArray);
			
			$objPHPExcel->getActiveSheet()->setCellValue("H2", "Home Health");
			$objPHPExcel->getActiveSheet()->getStyle("H2")->applyFromArray($styleArray);
			
			$objPHPExcel->getActiveSheet()->setCellValue("I2", "Comments");
			$objPHPExcel->getActiveSheet()->getStyle("I2")->applyFromArray($styleArray);
			
							
			foreach(range('A','D') as $columnID) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
			        ->setAutoSize(true);
			}
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(40);  //11.71
			
			foreach(range('F','H') as $columnID) {
			    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
			}
			
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(50);  //11.71
			$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(-1);
			$objPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(-1);		

						
			foreach ($data as $k => $d) {
				$dl = new CMS_Hospital($d->discharge_location_id);
				if ($d->home_health_id != '') {
					$hh = new CMS_Hospital($d->home_health_id);
				}
				
				$objPHPExcel->getActiveSheet()->setCellValue("A".$row, $d->number);
				$objPHPExcel->getActiveSheet()->getStyle("A".$row)->applyFromArray($bodyArray);
				
				$objPHPExcel->getActiveSheet()->setCellValue("B".$row, $d->last_name . ', ' . $d->first_name);
				$objPHPExcel->getActiveSheet()->getStyle("B".$row)->applyFromArray($bodyArray);
				
				$objPHPExcel->getActiveSheet()->setCellValue("C".$row, $d->phone);
				$objPHPExcel->getActiveSheet()->getStyle("C".$row)->applyFromArray($bodyArray);
				
				$objPHPExcel->getActiveSheet()->setCellValue("D".$row, date('m/d/Y', strtotime($d->datetime_discharge)));
				$objPHPExcel->getActiveSheet()->getStyle("D".$row)->applyFromArray($bodyArray);

				$objPHPExcel->getActiveSheet()->setCellValue("E".$row, $d->other_diagnosis);
				$objPHPExcel->getActiveSheet()->getStyle("E".$row)->applyFromArray($bodyArray);
				
				$objPHPExcel->getActiveSheet()->setCellValue("F".$row, $d->name);
				$objPHPExcel->getActiveSheet()->getStyle("F".$row)->applyFromArray($bodyArray);
				
				$objPHPExcel->getActiveSheet()->setCellValue("G".$row, $d->discharge_disposition);
				$objPHPExcel->getActiveSheet()->getStyle("G".$row)->applyFromArray($bodyArray);
				
				if ($d->service_disposition == 'Other Home Health') {
					$objPHPExcel->getActiveSheet()->setCellValue("H".$row, $hh->name);
				} else {
					$objPHPExcel->getActiveSheet()->setCellValue("H".$row, $d->service_disposition);
				}
				$objPHPExcel->getActiveSheet()->getStyle("H".$row)->applyFromArray($bodyArray);
				$objPHPExcel->getActiveSheet()->getStyle("I".$row)->applyFromArray($bodyArray);
				
				
				// This line is needed to add a larger row height to accomadate the comments box, however it is causing empty 
				// columns to be added after the last column and will not print correctly.
				
				//$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(100);				
				
				
				
				$row++;		
			}
						
			
			$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$row)->getAlignment()->setWrapText(true); 
			$objPHPExcel->getActiveSheet()->getStyle('A1:I'.$row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			
			
			PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
			$objPHPExcel->getProperties()->setTitle("$facility->name");
			$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader("&24" . $facility->name . ' ' . $reportType);
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter("&RPrinted: " . date("m/d/y g:i a", strtotime("now")));
			$objPHPExcel->getActiveSheet()->getSheetView()->setZoomScale(75);
			
			
			// Write file to the browser
			$objWriter->save("php://output");
			exit;

		}

		
	}

	public function adc() {
		smarty()->assign("isPrint", input()->isPrint);
		
		$facility = new CMS_Facility(input()->facility);
		if (! $facility->valid()) {
			$facility = null;
		} 

		$_facility = $facility->id;
		smarty()->assign("facilityId", $_facility);
		smarty()->assign("facility", $facility);
		smarty()->assign("view", input()->view);
		smarty()->assign("year", input()->year);
		 
		// Get ADC report for the time period
		$obj = new CMS_Schedule();
		$adc = $obj->fetchAdcReport(input()->view , input()->year, $facility->id);
												
		smarty()->assign("adc_info", $adc);
		smarty()->assign("graphData", json_encode($graphData));
	}
	
	public function getAdcData() {
		$facility = new CMS_Facility(input()->facility);
		if (! $facility->valid()) {
			$facility = null;
		} 


		$obj = new CMS_Schedule();
		$adc = $obj->fetchAdcReport(input()->view , input()->year, $facility->id);
			
		header("Content-type: text/javascript"); 
		$json_data = $this->graphData($adc);
		echo json_encode($json_data); 
		session_write_close(); 
		exit;		
	}
	
	private function graphData($data = false) {
		$returnData = array();
		foreach ($data as $val) {
			$returnData["categories"][] = date('F', strtotime($val->time_period));
			$returnData["data"][] = $val->census;
		}
		
		return $returnData;
		
	}
		
	
	private function reportTypes() {
		$report_types = CMS_Reports::fetchNames();
				
		$names = array();
		foreach ($report_types as $type) {
			$names[$type->name] = $type->description;
		}
		
		return $names;
	}
	
	
}