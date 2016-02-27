<?php

class DietaryMenuHelper {

	public function menu($patient, $selectedLocation) {

		//	Get patient schedule
		$rand = rand(1, 10000) * $patient->id;
		$options = '';
		$options .= "<li><a href=\"/?module=Dietary&amp;page=patient_info&amp;action=diet&amp;patient={$patient->public_id}\">Edit Diet</a></li>";
		$options .= "<li><a href=\"/?module=Dietary&amp;page=patient_info&amp;action=meal_traycard&amp;patient={$patient->public_id}&amp;location={$selectedLocation->public_id}&amp;pdf=true\" target=\"_blank\">Print Tray Card for Today's Meals</a></li>";
		$options .= "<li><a href=\"/?module=Dietary&amp;page=patient_info&amp;action=traycard_options&amp;patient={$patient->public_id}&amp;location={$selectedLocation->public_id}\" target=\"blank\">Print Specific Tray Card</a></li>";
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