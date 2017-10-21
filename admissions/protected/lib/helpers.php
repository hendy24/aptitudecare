<?php

function datetimepickerformat($in = "") {
	if ($in != "") {
		return strftime("%m/%d/%Y %I:%M %P", strtotime($in));
	} else {
		return strftime("%m/%d/%Y %I:%M %P", strtotime("now"));
	}
	
	return false;
}

$smarty->registerPlugin("function", "conflictAlert", "conflictAlert");
function conflictAlert($params, &$smarty) {
	$schedule = $params['schedule'];	
	$SITE_URL = SITE_URL;
	$ENGINE_URL = ENGINE_URL;
	
	$conflicts = $schedule->getConflicts();
	$msg = array();
	foreach ($conflicts as $conflict) {
		$str = "Conflict with {$conflict->getPatient()->fullName()}: admit to Room {$conflict->getRoomNumber()} on " . datetime_format($conflict->datetime_admit);
		if ($conflict->datetime_discharge != '') {
			$str .= ", discharge on " . datetime_format($conflict->datetime_discharge);
		}
		$msg[] = $str;
	}
	$msg = implode("\n", $msg);
	$msg .= "\nResolve conflicts by changing room assignment, by changing admit or discharge dates, by reverting one patient to Pending status, or by Cancelling an admit or discharge.";

	if (count($conflicts) > 0) {
		echo "<a class='admit-error-details' title='{$msg}'><img src='{$ENGINE_URL}/images/icons/error.png' /></a> <strong><i>Scheduling Conflict</i></strong><br />";
	}
}

$smarty->registerPlugin("function", "scheduleMenu", "scheduleMenu");
function scheduleMenu($params, &$smarty) {
	$schedule = $params['schedule'];
	$weekSeed = $params['weekSeed'];
	$facility = CMS_Facility::generate();
	$facility->load($schedule->facility);
	$SITE_URL = SITE_URL;
	$ENGINE_URL = ENGINE_URL;
	$rand = rand(1, 10000) * $schedule->id;
	$options = '';

	if (auth()->getRecord()->canEditInquiry($schedule->getFacility())) {
		$options .= "<li><a href='{$SITE_URL}/?page=patient&amp;action=inquiry&amp;schedule={$schedule->pubid}&amp;weekSeed={$weekSeed}&amp;mode=edit'>Pre-Admission Inquiry Record</a></li>";		
	} else {
		$options .= "<li><a href='{$SITE_URL}/?page=patient&amp;action=inquiry&amp;schedule={$schedule->pubid}&amp;mode=view'>Pre-Admission Inquiry Record</a></li>";		
	}
	$options .= "\n<li id='onsite-assessment'><a href='{$SITE_URL}/?page=patient&amp;action=onsite_assessment&amp;schedule={$schedule->pubid}'>On-Site Assessment</a></li>";

	if (auth()->getRecord()->isAdmissionsCoordinator() == 1) {
		if ($schedule->getPatient()->readyForNotes()) {
			$options .= "\n<li><a href='{$SITE_URL}/?page=patient&amp;action=notes&amp;schedule={$schedule->pubid}'>Manage Medical Records Files</a></li>";
		}	
	}

	$options .= "\n<li><a href='{$SITE_URL}/?page=patient&amp;action=nursing&amp;patient={$schedule->getPatient()->pubid}&amp;mode=edit'>Nursing Report/Notes</a></li>";

	if (auth()->getRecord()->isAdmissionsCoordinator() == 1) {
		if ($schedule->status == 'Approved' && date('Y-m-d', strtotime($schedule->datetime_admit)) >= date('Y-m-d', strtotime('now'))) {
			$options .= "\n<li><a href='{$SITE_URL}/?page=coord&amp;action=setSchedulePending&amp;schedule={$schedule->pubid}&amp;path=" . urlencode(currentURL()) . "'>Revert to Pending Status</a></li>";
		}
		if ($schedule->datetime_discharge_bedhold_end != '') {
			$options .= "\n<li><a href='{$SITE_URL}/?page=coord&amp;action=readmit&amp;facility={$facility->pubid}&amp;schedule={$schedule->pubid}&amp;path=" . urlencode(currentURL()) . "'>Re-Admit this patient</a></li>";
		}

		if ($schedule->getPatient()->readyForApproval($msg, $schedule->getFacility()->id) && $schedule->status != 'Approved' && $schedule->room == "") {
			$options .= "\n<li><a class='approve-admit-link' href='{$SITE_URL}/?page=coord&action=room&amp;schedule={$schedule->pubid}&amp;goToApprove=1'>Approve this inquiry</a></li>";
		} elseif ($schedule->room == "") {
			$options .= "\n<li><a href='{$SITE_URL}/?page=coord&amp;action=room&amp;schedule={$schedule->pubid}&datetime={$schedule->datetime_admit}'>Assign Room</a></li>";

		} elseif ($schedule->room != "" && $schedule->status == "Under Consideration" && $schedule->getPatient()->final_orders == true) {
			$options .= "\n<li><a class='approve' name='{$schedule->pubid}'>Approve this inquiry</a></li>";
		}

	} else {
		if ($schedule->getPatient()->readyForNotes()) {
			$options .= "\n<li><a href='{$SITE_URL}/?page=patient&amp;action=notes&amp;schedule={$schedule->pubid}'>View Medical Records Files</a></li>";
		}	
		
	}

	if ($schedule->room != '' && ($schedule->status == 'Approved')  || ($schedule->status == 'Discharged' && strtotime($schedule->datetime_discharge) >= strtotime('now'))) {
		$options .= "\n<li><a href='{$SITE_URL}/?page=facility&amp;action=room_transfer&amp;schedule={$schedule->pubid}'>Room Transfer</a></li>";
	}



	/*
	 * Commented out on 5/28/2014 by kwh
	 * 
	 */

	// if ($schedule->room != "") {
	// 		if ($schedule->notify_sent == 0) {
	// 		$anchor = "Send Admit Notification";
	// 	} else {
	// 		$anchor = "Re-send Admit Notification";
	// 	}
	// }
	// $options .= "\n<li><a href='{$SITE_URL}/?page=patient&amp;action=sendScheduleNotification&amp;schedule={$schedule->pubid}&amp;_path={$backurl}'>{$anchor}</a></li>";


	/*
	 * Discharge Section
	 *
	 */

	if ($schedule->status != "Under Consideration") {
		$options .= "\n<li><a href='{$SITE_URL}/?page=facility&amp;action=discharge_details&amp;schedule={$schedule->pubid}'>Manage Discharge</a></li>";
		$atHospitalRecord = $schedule->atHospitalRecord();
		
	}
	
	if ($atHospitalRecord->id != '') {
		$options .= "\n<li><a href='{$SITE_URL}/?page=facility&amp;action=sendToHospital&amp;schedule={$schedule->pubid}&amp;path=" . urlencode(currentURL()) . "'>Manage Hospital Stay</a></li>";
	} elseif ($schedule->status == "Approved" || ($schedule->status == 'Discharged' && strtotime($schedule->datetime_discharge) >= strtotime('now'))) {
		$options .= "\n<li><a href='{$SITE_URL}/?page=facility&amp;action=sendToHospital&amp;schedule={$schedule->pubid}&amp;path=" . urlencode(currentURL()) . "'>Initiate Hospital Stay</a></li>";
	}
		
	//$options .= "\n<li><a href='{$SITE_URL}/?page=facility&amp;action=cancelHospitalVisit&amp;schedule={$schedule->pubid}&amp;path=" . urlencode(currentURL()) . "'>Cancel Hospital Stay</a></li>";
	
	if ($schedule->status == "Approved" || ($schedule->status == 'Discharged' && strtotime($schedule->datetime_discharge) >= strtotime('now'))) {
		if ($schedule->getPatient()->physician_id != "") {
			$options .= "\n<li id=\"record-visit\"><a href='{$SITE_URL}/?page=patient&amp;action=visit&amp;patient={$schedule->getPatient()->pubid}&schedule={$schedule->pubid}'>Record Physician Visit</a></li>";
		}
	}
	
	$backurl = urlencode(currentURL());
	
	if ($schedule->status == 'Approved') {
		$options .= "\n<li><a href='{$SITE_URL}/?page=patient&amp;action=transferRequest&amp;schedule={$schedule->pubid}'>Enter a Transfer Request</a></li>";
	}
	//$options .= "\n<li><a href='{$SITE_URL}/?page=patient&amp;action=patient_schedule&amp;schedule={$schedule->pubid}'>Edit Patient Schedule</a></li>";
	if ($schedule->discharge_to == "Discharge to Hospital (Bed Hold)") {
		$options .= "\n<li id=\"cancel-bedhold\"><a  href='{$SITE_URL}/?page=facility&amp;action=cancelBedHold&amp;schedule={$schedule->pubid}'>Cancel the Bed Hold</a></li>";
	}
	
	if ($schedule->status == 'Under Consideration' || ($schedule->status == 'Approved' && strtotime(date('Y-m-d', strtotime($schedule->datetime_admit))) >= strtotime(date('Y-m-d', strtotime('now'))))) {
		$options .= "\n<li><a class='cancel-admit-link' href='{$SITE_URL}/?page=coord&amp;action=cancelSchedule&amp;id={$schedule->pubid}'>Cancel this inquiry</a></li>";
	}

	if ($schedule->getPatient()->readyForApproval($msg, $schedule->getFacility()->id)) {

	}

	//pr ($schedule->getPatient())->readyForApproval();
		
	
	echo <<<END
		<dl style="" class="dropdown">
			<dt><a id="linkglobal{$rand}" style="cursor:pointer;"></a></dt>
			<dd>
				<ul id="ulglobal{$rand}">
				{$options}
				</ul>
			</dd>
		</dl>
END;
		if (count($msg) > 0) {
			$message = implode(";", $msg);
			$image = "bell_error.png";
		} else {
			$message = "This pending admit request<br /> is ready for approval.";	
			$image = "flag_green.png";
		}
		if ($schedule->status == "Under Consideration") {
			echo <<<END
				<a class="tooltip"><img src="{$ENGINE_URL}/images/icons/{$image}" /><span>{$message}</span></a>

END;
		} else {
			echo <<<END
				<div class="clear"></div>
END;
		}

}