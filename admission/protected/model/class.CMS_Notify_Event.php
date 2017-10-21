<?php

class CMS_Notify_Event extends CMS_Table {
    
    public static $table = "notify_event";
    protected static $metadata = array();
    public static $modelTitle = "Notification Events";
    public static $enableAdminDelete = "root";
    public static $enableAdminEdit = "root";
    public static $enableAdminNew = "root";
    
    public function getTitle() {
        return "({$this->name}) {$this->description}";
    }
    
    public static function trigger($event_name, $obj1 = false, $obj2 = false, $obj3 = false, $obj4 = false) {
        
        // make sure an event of this name exists:
        $objEvent = static::generate();
        $event = current($objEvent->fetch(array('name' => $event_name)));
        if ($event == false) {
            throw new Exception("Unable to trigger event '{$event_name}");
        }
        
        // factory for the data to send to the notification templates
        switch ($event_name) {
            
/*
            case "schedule_general":
                
                $schedule = $obj1;
                $pairs = array(
                    "schedule" => $schedule
                );
                $facility = $schedule->getFacility();
                if ($schedule->getRoom()->valid()) {
                    $subject = "[{$facility->name}] - Admit Notification for Patient in Room #{$schedule->getRoom()->number}";
                } else {
                    $subject = "[{$facility->name}] - Admit Notification";                    
                }
                break;
*/
            
            case "schedule_cancelled":
                $schedule = $obj1;
                $pairs = array(
                    "schedule" => $schedule
                );
                $facility = $schedule->getFacility();
                if ($schedule->getRoom()->valid()) {
                    $subject = "[{$facility->name}] - Admit Scheduling Cancellation Notice for Patient in Room # {$schedule->getRoom()->number}";
                } else {
                    $subject = "[{$facility->name}] - Admit Scheduling Cancellation Notice";                    
                }
                
                break;
            
            case "schedule_changed":
                $schedule = $obj1;
                $schedule_before = $obj2;
                $pairs = array(
                    "schedule" => $schedule,
                    "schedule_before" => $schedule_before
                );
                $facility = $schedule->getFacility();
                if ($schedule->getRoom()->valid()) {
                    $subject = "[{$facility->name}] - Admit Scheduling Change Notice for Patient in Room #{$schedule->getRoom()->number}";
                } else {
                    $subject = "[{$facility->name}] - Admit Scheduling Change Notice";                    
                }
                
                break;
            
            case "discharge_scheduled":
                $schedule = $obj1;
                $pairs = array(
                    "schedule" => $schedule
                );
                $facility = $schedule->getFacility();
                if ($schedule->getRoom()->valid()) {
                    $subject = "[{$facility->name}] - Discharge Schedule Notice for Patient in Room #{$schedule->getRoom()->number}";
                } else {
                    $subject = "[{$facility->name}] - Discharge Schedule Notice";                    
                }
                break;
            
            case "notes_uploaded":
                $schedule = $obj1;
                $pairs = array(
                    "schedule" => $schedule
                );
                $facility = $schedule->getFacility();
                if ($schedule->getRoom()->valid()) {
                    $subject = "[{$facility->name}] - Medical Records Notice for Patient in Room # {$schedule->getRoom()->number}";
                } else {
                    $subject = "[{$facility->name}] - Medical Records Notice";                    
                }
                break;
            
            case "facility_transfer_inbound":
                $facility = $obj1;
                $schedule_before = $obj2;
                $schedule_after = $obj3;
                $pairs = array(
                    "schedule_before" => $schedule_before,
                    "schedule_after" => $schedule_after
                );
                $subject = "Inbound Patient transfer notice";
                break;
            
            case "facility_transfer_outbound":
                $facility = $obj1;
                $schedule_before = $obj2;
                $schedule_after = $obj3;
                $pairs = array(
                    "schedule_before" => $schedule_before,
                    "schedule_after" => $schedule_after
                );
                $subject = "Outbound Patient transfer notice";
                break;
                            	
            case "send_to_hospital_created":
                $facility = $obj1;
                $schedule = $obj2;
                $atHospitalRecord = $obj3;
                $pairs = array(
                    "schedule" => $schedule,
                    "atHospitalRecord" => $atHospitalRecord
                );
                if ($schedule->getRoom()->valid()) {
                    $subject = "Patient hospital visit has been initiated for Patient in Room #{$schedule->getRoom()->number}";
                } else {
                    $subject = "Patient hospital visit has been initiated";                    
                }
                break;
            
            case "send_to_hospital_updated":
                $facility = $obj1;
                $schedule = $obj2;
                $atHospitalRecord_before = $obj3;
                $atHospitalRecord_after = $obj4;
                $pairs = array(
                    "schedule" => $schedule,
                    "atHospitalRecord_before" => $atHospitalRecord_before,
                    "atHospitalRecord_after" => $atHospitalRecord_after
                );
                if ($schedule->getRoom()->valid()) {
                    $subject = "Patient hospital visit has been updated for Patient in Room #{$schedule->getRoom()->number}";
                } else {
                    $subject = "Patient hospital visit has been updated";                    
                }
                break;

            case "send_to_hospital_status_updated":
                $facility = $obj1;
                $schedule = $obj2;
                $atHospitalRecord = $obj3;
                $confMsg = $obj4;
                $pairs = array(
                    "schedule" => $schedule,
                    "atHospitalRecord" => $atHospitalRecord,
                    "confMsg" => $confMsg
                );
                if ($schedule->getRoom()->valid()) {
                    $subject = "Patient hospital visit status has been updated for Patient in Room #{$schedule->getRoom()->number}";
                } else {
                    $subject = "Patient hospital visit status has been updated";                    
                }
                break;
                
           case "send_to_hospital_direct_admit":
            	$schedule = $obj1;
            	$atHospitalRecord = $obj2;
            	$facility = $obj3;
            	$pairs = array(
            		"schedule" => $schedule,
            		"atHospitalRecord" => $atHospitalRecord,
            		"facility" => $facility
            	);
            	if ($schedule->getRoom()->valid()) {
                    $subject = "Patient in Room #{$schedule->getRoom()->number} has been discharged and admitted to the hospital";
                } else {
	            $subject = "Patient has been discharged and admitted to the hospital";
                }                
            	break;

            
        }
        
        if (! isset($facility) ) {
            throw new Exception("Cannot determine the facility whose users to notify.");
        } else {
            if (! $facility->valid()) {
                throw new Exception("Cannot determine the facility whose users to notify.");                
            }
        }
        
        // which roles get notified of this event?
        $roles = $event->getRolesToNotify();
        
        // and, given the facility resolved above, which users do those roles represent?
        $users = array();
        foreach ($roles as $role) {
            $_users = $role->getUsers($facility);
            if ($_users !== false) {
                $users = array_merge($users, $_users);
            }
        }


        // add facility to the pairs
        $pairs["facility"] = $facility;
        
        // add the signed-in user to the pairs
        if (auth()->valid()) {
            $pairs["trigger_user"] = auth()->getRecord();
        }
        
        // construct string list of recipients
        $recip_list = array();
        foreach ($users as $user) {
            $recip_list[] = $user->email;
        }
        $pairs["recip_list"] = implode(", ", $recip_list);

        if (count($users) > 0) {
            $email = new Email("notify_event_{$event_name}", $pairs);
            $email->Subject = $subject;
            
            // add users to the email
            if (DEVELOPMENT == false) {
                foreach ($users as $user) {
                    // ignore "me"
                    if ($user->id != auth()->getRecord()->id) {
                        $email->AddAddress($user->email, $user->getFullName());
                    }                    
                }
            } else {
                $email->AddAddress(APP_EMAIL);
            }
            
            return $email->Send();
        }    
    }
    
    public function getRolesToNotify() {
        return $this->related("role");
    }
    
    
}