<?php

class PatientMenuHelper {
	
	public function menu($patient) {

		//	Get patient schedule
		$rand = rand(1, 10000) * $patient->id;
		$options = '';
		$options .= "<li><a href=\"/?module=HomeHealth&page=patients&action=inquiry&patient={$patient->public_id}\">Inquiry Record</a></li>";
		$options .= "<li><a href=\"/?module=HomeHealth&page=patients&action=face_to_face&patient={$patient->public_id}\">Face to Face Form</a></li>";
		$options .= "<li><a href=\"/?module=HomeHealth&page=patients&action=assign_clinicians&patient={$patient->public_id}\">Assign Clinicians</a></li>";
		
		//	If everything is ready show the approve link
		if ($patient->clinicians_assigned && $patient->f2f_received && $patient->status != "Approved") {
			$options .= "<li><a href=\"/?module=HomeHealth&page=patients&action=approve_inquiry&patient={$patient->public_id}\">Approve this Inquiry</a></li>";
		}


		//	Discharge options
		if ($patient->status == "Approved") {
			$options .= "<li><a href=\"/?module=HomeHealth&page=discharges&action=manage_discharge&patient={$patient->public_id}\">";
			if ($patient->datetime_discharge != '') {
				$options .= "Manage Discharge";
			} else {
				$options .= "Schedule Discharge";
			}

			$options .= "</a></li>";
		}

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

	}
}