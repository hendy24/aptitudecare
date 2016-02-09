<?php

class PatientSnack extends Dietary {

	protected $table = "patient_snack";


	public function fetchPatientSnacks($patient_id, $snack_time) {
		$snack = $this->loadTable("Snack");
		$sql = "SELECT s.* FROM {$this->tableName()} pfi INNER JOIN {$snack->tableName()} AS s ON s.id = pfi.snack_id WHERE patient_id = :patient_id AND time = :snack_time";
		$params[":patient_id"] = $patient_id;
		$params[":snack_time"] = $snack_time;
		$result =  $this->fetchAll($sql, $params);

		if (!empty ($result)) {
			return $result;
		}

		return false;
	}



	public function deleteSnack($patient_id, $snack_name, $snack_time) {
		$snack = $this->loadTable("Snack");
		$sql = "DELETE FROM {$this->tableName()} WHERE patient_id = :patient_id AND snack_id = (SELECT id FROM {$snack->tableName()} WHERE name = :snack_name) AND time = :snack_time";
		$params = array(
			":patient_id"	=> 	$patient_id,
			":snack_name"	=> 	$snack_name,
			":snack_time" 	=> 	$snack_time
		);

		if ($this->deleteQuery($sql, $params)) {
			return true;
		}

		return false;
	}

  public function fetchByLocation($location, $date) {
//    $adapt_equip = $this->loadTable("AdaptEquip");
//    $schedule = $this->loadTable("Schedule");
//    $room = $this->loadTable("Room");

    $sql =

<<<EOD
    SELECT g.number, p.id patient_id, s.id admit_schedule_id, p.last_name, p.first_name, s.location_id, Group_Concat(Distinct f.name SEPARATOR ', ') name, e.time
    FROM ac_patient AS p
    INNER JOIN admit_schedule s ON s.patient_id = p.id
    inner join dietary_patient_snack e ON e.patient_id = p.id
    left join dietary_snack f on e.snack_id = f.id
    left join admit_room g on s.room_id = g.id
    WHERE s.status='Approved'
    AND s.location_id = {$location->id}
    AND (s.datetime_discharge >= now() OR s.datetime_discharge IS NULL)
    group by g.number, p.id, s.id, p.last_name, p.first_name, s.location_id, /*f.id, f.name,*/ e.time
    ORDER BY s.room_id ASC
EOD;



    //$params[":location_id"] = 3;
    $snacks = $this->fetchAll($sql);



    return $snacks;
//    if (!empty ($compiled_patients)) {
//      return $compiled_patients;
//    } else {
//      return $this->fetchColumnNames();
//    }
  }

  public function fetchByLocationSnackReport($location) {
//    $adapt_equip = $this->loadTable("AdaptEquip");
//    $schedule = $this->loadTable("Schedule");
//    $room = $this->loadTable("Room");

    $sql =

<<<EOD
  SELECT f.name, Count(f.name) Count
  FROM ac_dev.ac_patient AS p
  INNER JOIN ac_dev.admit_schedule s ON s.patient_id = p.id
  inner join ac_dev.dietary_patient_snack e ON e.patient_id = p.id
  left join ac_dev.dietary_snack f on e.snack_id = f.id
  left join ac_dev.admit_room g on s.room_id = g.id
  WHERE s.status='Approved'
  AND s.location_id = {$location->id}
  AND (s.datetime_discharge >= now() OR s.datetime_discharge IS NULL)
  group by f.name
  Order BY e.time, COUNT(f.name) desc
EOD;

    //$params[":location_id"] = 3;
    $snacks = $this->fetchAll($sql);



    return $snacks;
  }



}