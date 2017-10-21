<?php

class NotifyForHospitalTracking extends CLIScript {
	
	protected static $firstRun = "2011-09-07 09:00:00";
	protected static $intervalDays = 0;
	protected static $intervalHours = 0;
	protected static $intervalMinutes = 60;

	protected static $enabled = true;
	
	public static function exec() {
		$atHospitalRecords = CMS_Schedule::getAtHospitalRecordsForReminder();
		ini_set('error_reporting', 'off');
		if ($atHospitalRecords !== false && count($atHospitalRecords) > 0) {
			foreach ($atHospitalRecords as $ahr) {
				$dischargeNurse = $ahr->dischargeNurse();
				if ($dischargeNurse != false) {
					if ($dischargeNurse->valid()) {
						$schedule = $ahr->related("schedule");
						$pairs = array(
							"schedule" => $schedule,
							"facility" => $schedule->getFacility(),
							"atHospitalRecord" => $ahr
						);
						$email = new Email("notify_event_send_to_hospital_status_update_needed", $pairs);
						$email->Subject = "Attention! Status updated needed for patient hospital visit: {$schedule->getPatient()->fullName()}";
						$email->AddAddress($dischargeNurse->email, $dischargeNurse->fullName());
						$email->Send();
						
						$ahr->datetime_last_email = datetime();
						$ahr->save();
					}
				}
			}
		}
		
	}
	
}