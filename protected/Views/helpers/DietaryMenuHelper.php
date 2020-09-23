<?php

class DietaryMenuHelper {

	public function menu($patient, $selectedLocation, $modEnabled = true, $k = "") {

		//	Get patient schedule
		$rand = rand(1, 10000) * $patient->id;
		$options = '';
		//$options .= "<li><a href=\"/?module=Dietary&amp;page=patient_info&amp;action=diet&amp;patient={$patient->public_id}\">Edit Diet</a></li>";
		$options .= "<li><a href=\"/?module=Dietary&amp;page=patient_info&amp;action=meal_tray_card&amp;patient={$patient->public_id}&amp;location={$selectedLocation->public_id}&amp;pdf2=true\" target=\"_blank\">Current Tray Card</a></li>";
		$options .= "<li><a href=\"/?module=Dietary&amp;page=patient_info&amp;action=traycard_options&amp;patient={$patient->public_id}&amp;location={$selectedLocation->public_id}\">Selected Tray Card</a></li>";
		$options .= "<li><a href=\"#\" class=\"move-patient\" style=\"padding-left: 0px;\">
				<img src=\"".FRAMEWORK_IMAGES."/door_in.png\" class=\"{$k}\" style=\"position: relative;width: 15px;height: 15px;\" alt=\"\">
				Change Room
				<input type=\"hidden\" name=\"public_id\" class=\"public-id\" value=\"{$patient->public_id}\">
				<input type=\"hidden\" name=\"room_number\" class=\"room-number\" value=\"{$patient->number}\">
				<input type=\"hidden\" name=\"patient_name\" class=\"patient-name\" value=\"{$patient->last_name}, {$patient->first_name}\">
		</a></li>";
		$options .= "<li><a href=\"#\" class=\"delete-patient\" style=\"padding-left: 0px;\">
				<img src=\"".FRAMEWORK_IMAGES."/delete.png\" class=\"{$k}\" style=\"position: relative;width: 15px;height: 15px;\" alt=\"\">
				Discharge Patient
				<input type=\"hidden\" name=\"public_id\" class=\"public-id\" value=\"{$patient->public_id}\">
				<input type=\"hidden\" name=\"room_number\" class=\"room-number\" value=\"{$patient->number}\">
			</a></li>
				<input type=\"hidden\" class=\"patient-id\" value=\"{$patient->public_id}\">";
		// $options .= "<li><a href=\"/?module=HomeHealth&amp;page=dietary&amp;action=delete&amp;patient={$patient->public_id}\">Delete</a></li>";

		//	If everything is ready show the approve link
		// if () {
		// 	$options .= "<li><a href=\"/?module=HomeHealth&amp;page=patients&amp;action=approve_inquiry&amp;patient={}\">Approve this Inquiry</a></li>";
		// }


		//	Discharge options
		// if () {
		// 	$options .= "<li><a href=\"/?module=HomeHealth&amp;page=discharges&amp;action=manage_discharge&amp;patient={}\">";
		// 	if ($patient->datetime_discharge != '') {
		// 		$options .= "Manage Discharge";
		// 	} else {
		// 		$options .= "Schedule Discharge";
		// 	}

		// 	$options .= "</a></li>";
		// }

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