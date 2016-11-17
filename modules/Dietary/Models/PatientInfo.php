<?php

class PatientInfo extends Dietary {

	protected $table = 'patient_info';


	public function fetchDietInfo($patientid) {
		$sql = "SELECT pi.* FROM {$this->tableName()} pi WHERE pi.patient_id = :patientid LIMIT 1";
		$params[":patientid"] = $patientid;
		$result = $this->fetchOne($sql, $params);

		if (!empty ($result)) {
			return $result;
		} else {
			return $this;
		}
	}


/*
 * -------------------------------------------------------------------------
 *  FETCH PATIENT INFO FOR THE TRAYCARD
 * -------------------------------------------------------------------------
 */

	public function fetchTrayCardInfo($patient_id) {
		// connect to all the tables needed to get info for the tray card
		$patient = $this->loadTable('Patient');
		$schedule = $this->loadTable('Schedule');
		$room = $this->loadTable('Room');
		$patient_diet_order = $this->loadTable('PatientDietOrder');
		$diet_order = $this->loadTable('DietOrder');
		$patient_texture = $this->loadTable('PatientTexture');
		$texture = $this->loadTable('Texture');
		$patient_food_info = $this->loadTable('PatientFoodInfo');
		$allergy = $this->loadTable('Allergy');
		$patient_other = $this->loadTable('PatientOther');
		$other = $this->loadTable('Other');
		$dislike = $this->loadTable('Dislike');
		$adapt_equip = $this->loadTable('AdaptEquip');
		$patient_adapt_equip = $this->loadTable('PatientAdaptEquip');

		// set params for the query
		$params[":patient_id"] = $patient_id;

		// fetch all the items from the disparate tables for the tray card 
		$sql = "SELECT 
					r.number,
					CONCAT (p.last_name, ', ', p.first_name) as patient_name,
					p.date_of_birth,
					(SELECT GROUP_CONCAT(di.name separator ', ') FROM {$diet_order->tableName()} AS di INNER JOIN {$patient_diet_order->tableName()} dpi ON dpi.diet_order_id = di.id WHERE dpi.patient_id = :patient_id AND di.name != 'Other') diet_orders,
					(SELECT GROUP_CONCAT(t.name separator ', ') FROM {$texture->tableName()} AS t INNER JOIN {$patient_texture->tableName()} pt ON pt.texture_id = t.id WHERE pt.patient_id = :patient_id) AS textures,
					pi.portion_size,
					pi.special_requests,
					(SELECT GROUP_CONCAT(a.name separator ', ') FROM {$allergy->tableName()} AS a INNER JOIN {$patient_food_info->tableName()} pfi ON pfi.food_id = a.id WHERE pfi.patient_id = :patient_id AND pfi.allergy = 1) AS allergies,
					(SELECT GROUP_CONCAT(o.name separator ', ') FROM {$other->tableName()} AS o INNER JOIN {$patient_other->tableName()} po ON po.other_id = o.id WHERE po.patient_id = :patient_id) AS orders,
					(SELECT GROUP_CONCAT(d.name separator ', ') FROM {$dislike->tableName()} AS d INNER JOIN {$patient_food_info->tableName()} pfi ON pfi.food_id = d.id AND pfi.allergy = 0 WHERE pfi.patient_id = :patient_id) AS dislikes,
					(SELECT GROUP_CONCAT(ae.name separator ', ') FROM {$adapt_equip->tableName()} AS ae INNER JOIN {$patient_adapt_equip->tableName()} pae ON pae.adapt_equip_id = ae.id WHERE pae.patient_id = :patient_id) AS adapt_equip
				FROM {$this->tableName()} AS pi
					INNER JOIN {$patient->tableName()} 				p ON p.id = pi.patient_id
					INNER JOIN {$schedule->tableName()} 			s ON s.patient_id = pi.patient_id
					INNER JOIN {$room->tableName()} 				r ON r.id = s.room_id

				WHERE p.id = :patient_id
				ORDER BY r.number ASC";

		$tray_card_info = array();
		$tray_card_info['main_data'] = $this->fetchOne($sql, $params);
		$tray_card_info['items_by_meal'] = $this->fetchItemsByMeal($patient_id);
		return $tray_card_info;
	}	


	private function fetchItemsByMeal($patient_id) {
		$patient_special_req = $this->loadTable('PatientSpecialReq');
		$special_req = $this->loadTable('SpecialReq');
		$patient_beverage = $this->loadTable('PatientBeverage');
		$beverage = $this->loadTable('Beverage');

		$bev_sql = "SELECT b.name, pb.meal FROM {$beverage->tableName()} AS b INNER JOIN {$patient_beverage->tableName()} pb ON pb.beverage_id = b.id WHERE pb.patient_id = :patient_id;";
		$spec_req_sql = "SELECT sr.name, psr.meal FROM {$special_req->tableName()} AS sr INNER JOIN {$patient_special_req->tableName()} psr ON psr.special_req_id = sr.id WHERE psr.patient_id = :patient_id";

		$params[":patient_id"] = $patient_id;

		$info_array = array();
		$info_array['beverages'] = $this->fetchAll($bev_sql, $params);
		$info_array['special_reqs'] = $this->fetchAll($spec_req_sql, $params);
		return $info_array;

	}



	public function fetchByLocation_allergy($location) {
		$allergy = $this->loadTable("Allergy");
		$schedule = $this->loadTable("Schedule");
		$room = $this->loadTable("Room");
		$patient = $this->loadTable('Patient');
		$pfi = $this->loadTable('PatientFoodInfo');

		$sql = "SELECT 
				r.number, 
				p.id AS patient_id, 
				s.id AS schedule_id, 
				p.last_name, 
				p.first_name, 
				s.location_id, 
				GROUP_CONCAT(a.name separator ', ') as allergy_name
			FROM {$patient->tableName()} AS p 
			INNER JOIN {$schedule->tableName()} s ON s.patient_id = p.id
			INNER JOIN {$room->tableName()} r ON r.id = s.room_id
			LEFT JOIN {$pfi->tableName()} pfi ON pfi.patient_id = p.id
			LEFT JOIN {$allergy->tableName()} a ON a.id = pfi.food_id AND pfi.allergy = 1
			WHERE s.status = 'Approved'
			AND s.location_id = :location_id
			AND (s.datetime_discharge >= :current_date OR s.datetime_discharge IS NULL)
			GROUP BY p.id
			ORDER BY r.number ASC";

		$params[":location_id"] = $location->id;
		$params[":current_date"] = mysql_date();
		return $this->fetchAll($sql, $params);


		//     $sql =

		// <<<EOD
		//   SELECT g.number, p.id patient_id, s.id admit_schedule_id, p.last_name, p.first_name, s.location_id, f.id allergy_id, f.name, p.id
		// 	FROM ac_patient AS p
		// 	INNER JOIN admit_schedule s ON s.patient_id = p.id
		// 	LEFT JOIN dietary_patient_food_info e ON e.patient_id = p.id
		// 	left join dietary_allergy f on e.food_id = f.id
		// 	left join admit_room g on s.room_id = g.id
		// 	WHERE s.status='Approved'
		// 	AND s.location_id = {$location->id}
		// 	AND (s.datetime_discharge >= now() OR s.datetime_discharge IS NULL)
		// 	group by g.number, p.id, s.id, p.last_name, p.first_name, s.location_id, f.id, f.name, p.id
		// 	ORDER BY s.room_id ASC
		// EOD;

		//     $compiled_patients  = array();
		//     $duped_patients = array();


		//     //$params[":location_id"] = 3;
		//     $patients = $this->fetchAll($sql);


		//     foreach ($patients as $key => $value) {
		//       $compiled_patients[$value->patient_id][] = $value;
		//     }


		//     if (!empty ($compiled_patients)) {
		//       return $compiled_patients;
		//     } else {
		//       return $this->fetchColumnNames();
		//     }
	}


	public function fetchPatientInfoByPatient($patient_id, $location_id) {
		$sql = "SELECT * FROM {$this->tableName()} WHERE patient_id = :patient_id AND location_id = :location_id";
		$params = array(":patient_id" => $patient_id, ":location_id" => $location_id);
		return $this->fetchOne($sql, $params);
	}

}
