<?php

class PatientInfo extends Dietary {

	protected $table = 'patient_info';

	/*
	//type:
			texture
			beverage 		***meal 1, 2, 3
			diet_order
			food_info		***allergy 1-yes, 0-disklike
			other
			snack			***time: am, pm, bedtime
			special_reqs	***meal 1, 2, 3
			supplement
			texture
			
	*/
	//Helper function to allow array where in queries.
	function bindParamArray($prefix, $values, &$bindArray, $delimiter=",")
	{
		$str = "";
		foreach($values as $index => $value){
			$str .= ":".$prefix.$index.$delimiter;
			$bindArray[$prefix.$index] = $value;
		}
		return rtrim($str,$delimiter);     
	}
	
	public function save_dietary_patient($patientid, $table, $optionsList, $flagName = "", $flag ="", $table2 =null, $special_id = null) {
		$lfeedback = "$table: $flagName $flag";
		$rowChangesNovel = null;
		$rowChangeRemoved = null;
		$rowChangeAdd = null;
		
		
		//table2 is if like allergy
		//Normal:
		//dietary_texture
		//dietary_patient_texture
		//texture_id as the column
		//
		//Allergies:
		//dietary_allergy
		//dietary_patient_food_info
		//food_id as the column
		
		if($table2 === null)
		{
			$table2 = $table;
		}
		if($special_id === null)
		{
			$special_id = "${table}_id";
		}
		
		//echo "Patient id is $patientid!";
		//echo "Working on $table!\n";
		//echo "$patientid, $table, optionsList, $flagName, $flag, $table2, $special_id\n";
		//update where to 
		$optionsList = (array) $optionsList;
		//array_walk($optionsList, function(&$x) {$x = "\"$x\"";});
		//$optionsList = implode(', ', $optionsList);
		
		$params = array();
		//echo "\n\n".$optionsList."\n\n";
		//print_r($params);
		$ids_from_helper = $this->bindParamArray("_id", $optionsList, $params, "),(");
		//print_r($params);
		
		//if we are deleteing everything, we don't have to add to the database.
		if(!empty($optionsList))
		{
			//add new items if any
			$sql = "INSERT IGNORE INTO `dietary_${table}` (name) VALUES (${ids_from_helper})";
			$conn = db()->getConnection();
			$stmt = $conn->prepare($sql);
			$stmt->execute($params);
			//echo "statment0: $sql\n";
			//echo "Row Count0: {$stmt->rowCount()}\n\n";
			//print_r($stmt->errorCode());
			//print_r($stmt->errorInfo());
			$rowChangesNovel = $stmt->rowCount();
			$lfeedback .= " Novel: {$stmt->rowCount()}";
		} else {
			//echo "statement0: skipped as options empty";
		}
		
		//DELETE ITEMS no longer linked We do this even if empty list, but need the value of null, but use the optionsList to not to #3
		$params = array();
		//echo "\n\n".$optionsList."\n\n";
		//print_r($params);
		if(!empty($optionsList))
		{
			$ids_from_helper = $this->bindParamArray("_id", $optionsList, $params);
		} else {
			$ids_from_helper = $this->bindParamArray("_id", array(NULL), $params);
		}
		//print_r($params);
		
		
		$sql ="DELETE FROM `dietary_patient_${table2}` WHERE `${special_id}` NOT IN (SELECT ID FROM `dietary_${table}` WHERE NAME IN (${ids_from_helper})) AND patient_id = :patient_id ";
		if($flag !== "" && $flagName !== "")
		{
			$sql.= "AND `${flagName}` = :flag";
			$params[":flag"] = $flag;
		}
		//echo "statment1: $sql\n\n";
		//$params[":optionsList"] = $optionsList;
		$params[":patient_id"] = $patientid;
		//if ($this->deleteQuery($sql, $params)) {
		//	echo "Query 1 True!\n";
		//	//return true;
		//}
		$conn = db()->getConnection();
		$stmt = $conn->prepare($sql);
		$stmt->execute($params);
		//echo "statment1: $sql\n";
		//echo "Row Count1: {$stmt->rowCount()}\n\n";
		$rowChangeRemoved = $stmt->rowCount();
		$lfeedback .= " Removed: {$stmt->rowCount()}";
		//print_r($stmt->errorCode());
		//print_r($stmt->errorInfo());

		
		//Add new items to change, but no point if the new-s are emptys.
		if(!empty($optionsList))
		{
			$params = array();
			$ids_from_helper = $this->bindParamArray("_id", $optionsList, $params);

			//update new & existing.
			//$optionsList = array_walk($arr, function(&$x) {$x = "\"$x\"";});
			//$optionsList = implode(', ', $optionsList);
			//INSERT NEW AND SAME
			// SELECT specifies the patient_id, and the meal
			//INSERT IGNORE INTO dietary_patient_beverage 		(beverage_id, patient_id, meal) SELECT id, 1, 1 FROM dietary_beverage WHERE NAME IN ("1/2 apple", "1/2 glass milk")
			$sql ="INSERT IGNORE INTO `dietary_patient_${table2}` (${special_id}, patient_id";
			
			if($flag !== "" && $flagName !== "")
			{
				$sql .= ", ${flagName})  SELECT id, :patient_id, :flag ";
				$params[":flag"] = $flag;
				
			} else {
				$sql .= ")  SELECT id, :patient_id ";
			}
			$sql .= " FROM dietary_${table} WHERE NAME IN (${ids_from_helper})";
			
			
			//$params[":optionsList"] = $optionsList;
			$params[":patient_id"] = $patientid;
			//if ($this->update($sql, $params)) {
			//	//return true;
			//	echo "Query 2 True!\n";
			//}
			$conn = db()->getConnection();
			$stmt = $conn->prepare($sql);
			$stmt->execute($params);
			//echo "statment2: $sql\n";
			//echo "Row Count2: {$stmt->rowCount()}\n\n";
			$rowChangesNew = $stmt->rowCount();
			$lfeedback .= " Added: {$stmt->rowCount()}";
			//print_r($stmt->errorCode());
			//print_r($stmt->errorInfo());
		}
		
		//$lfeedback .= "";
		if(($rowChangesNovel === null && $rowChangeRemoved === 0 && $rowChangesNew === null))
		{
			//$lfeedback = "$table: $flagName $flag no changes made!";
			$lfeedback = "";
		} elseif($rowChangesNovel === null && $rowChangesNew === null) {
			if($rowChangeRemoved > 0)
			{
				$lfeedback = "$table: $flagName $flag Deleted $rowChangeRemoved";
			} else {
				$lfeedback = "";
			}
		} elseif($rowChangesNovel === 0 && $rowChangeRemoved === 0 && $rowChangesNew === 0) {
				$lfeedback = "";
		}
		
		
		return $lfeedback;
	}

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
	
	public function fetchDietInfo2($patientid) {
		$schedule = $this->loadTable('Schedule');
		$room = $this->loadTable('Room');
		
		$sql = "SELECT pi.*, r.number, r.location_id FROM {$this->tableName()} pi
		LEFT JOIN {$schedule->tableName()} s ON s.patient_id = pi.patient_id
		LEFT JOIN {$room->tableName()} r ON r.id = s.room_id
		WHERE pi.patient_id = :patientid 
		ORDER BY datetime_admit DESC
		LIMIT 1";
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

	public function fetchTrayCardInfo($patient_id, $location_id = NULL, $snacks = false) {
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
		$patient_special_req = $this->loadTable('PatientSpecialReq');
		$special_req = $this->loadTable('SpecialReq');
		$patient_beverage = $this->loadTable('PatientBeverage');
		$beverage = $this->loadTable('Beverage');

		$params = array();
		
		// set params for the query
		if(strtolower($patient_id) == "all" || $patient_id == NULL) {
			//No extra, let's just keep the if statements the same
		} else {
			$params[":patient_id"] = $patient_id;
		}
		
		if($location_id !== NULL) {
			$params[":location_id"] = $location_id;
		};

		// fetch all the items from the disparate tables for the tray card
		$sql = "SELECT p.id, p.public_id, r.number, s.location_id, first_name, last_name, middle_name, pi.table_number, pi.diet_info_other, pi.texture_other, pi.fluid_other, ";
		$sql .= " CONCAT (p.last_name, ', ', p.first_name) as patient_name,
			p.date_of_birth,
			(SELECT GROUP_CONCAT(di.name ORDER BY is_other, sort_index separator ', ') FROM {$diet_order->tableName()} AS di INNER JOIN {$patient_diet_order->tableName()} dpi ON dpi.diet_order_id = di.id WHERE dpi.patient_id = p.id AND di.name != 'Other') diet_orders,
			(SELECT GROUP_CONCAT(t.name ORDER BY is_other, is_liquid, sort_index separator ', ') FROM {$texture->tableName()} AS t INNER JOIN {$patient_texture->tableName()} pt ON pt.texture_id = t.id WHERE pt.patient_id = p.id) AS textures,
			pi.portion_size,
			(SELECT GROUP_CONCAT(a.name separator ', ') FROM {$allergy->tableName()} AS a INNER JOIN {$patient_food_info->tableName()} pfi ON pfi.food_id = a.id WHERE pfi.patient_id = p.id AND pfi.allergy = 1) AS allergies,
			(SELECT GROUP_CONCAT(o.name ORDER BY is_other, sort_index separator ', ') FROM {$other->tableName()} AS o INNER JOIN {$patient_other->tableName()} po ON po.other_id = o.id WHERE po.patient_id = p.id) AS orders,
			(SELECT GROUP_CONCAT(d.name separator ', ') FROM {$dislike->tableName()} AS d INNER JOIN {$patient_food_info->tableName()} pfi ON pfi.food_id = d.id AND pfi.allergy = 0 WHERE pfi.patient_id = p.id) AS dislikes,
			(SELECT GROUP_CONCAT(ae.name separator ', ') FROM {$adapt_equip->tableName()} AS ae INNER JOIN {$patient_adapt_equip->tableName()} pae ON pae.adapt_equip_id = ae.id WHERE pae.patient_id = p.id) AS adapt_equip,
			(SELECT GROUP_CONCAT(b.name separator ', ') FROM {$beverage->tableName()} AS b INNER JOIN {$patient_beverage->tableName()} pb ON pb.beverage_id = b.id WHERE pb.patient_id = p.id AND meal = 1) AS beverages_0,
			(SELECT GROUP_CONCAT(b.name separator ', ') FROM {$beverage->tableName()} AS b INNER JOIN {$patient_beverage->tableName()} pb ON pb.beverage_id = b.id WHERE pb.patient_id = p.id AND meal = 2) AS beverages_1,
			(SELECT GROUP_CONCAT(b.name separator ', ') FROM {$beverage->tableName()} AS b INNER JOIN {$patient_beverage->tableName()} pb ON pb.beverage_id = b.id WHERE pb.patient_id = p.id AND meal = 3) AS beverages_2,
			(SELECT GROUP_CONCAT(sr.name separator ', ') FROM {$special_req->tableName()} AS sr INNER JOIN {$patient_special_req->tableName()} psr ON psr.special_req_id = sr.id WHERE psr.patient_id = p.id AND meal = 1) AS special_reqs_0,
			(SELECT GROUP_CONCAT(sr.name separator ', ') FROM {$special_req->tableName()} AS sr INNER JOIN {$patient_special_req->tableName()} psr ON psr.special_req_id = sr.id WHERE psr.patient_id = p.id AND meal = 2) AS special_reqs_1,
			(SELECT GROUP_CONCAT(sr.name separator ', ') FROM {$special_req->tableName()} AS sr INNER JOIN {$patient_special_req->tableName()} psr ON psr.special_req_id = sr.id WHERE psr.patient_id = p.id AND meal = 3) AS special_reqs_2
			
			"; //PHP logic is 0,1,2 for meals vs db 1,2,3
			
			if($snacks){
				$sql .= ",
					(SELECT GROUP_CONCAT(ds.name separator ', ') FROM dietary_patient_snack ps INNER JOIN dietary_snack ds ON ds.id = ps.snack_id WHERE ps.patient_id = p.id AND time = 'am') AS snacks_am,
					(SELECT GROUP_CONCAT(ds.name separator ', ') FROM dietary_patient_snack ps INNER JOIN dietary_snack ds ON ds.id = ps.snack_id WHERE ps.patient_id = p.id AND time = 'pm') AS snacks_pm,
					(SELECT GROUP_CONCAT(ds.name separator ', ') FROM dietary_patient_snack ps INNER JOIN dietary_snack ds ON ds.id = ps.snack_id WHERE ps.patient_id = p.id AND time = 'bedtime') AS snacks_bedtime
				";
			}

//patients won't show up unless they have an entry in the dietary_patient_info
		$sql .= " FROM {$this->tableName()} AS pi
			INNER JOIN {$patient->tableName()} p ON p.id = pi.patient_id
			INNER JOIN {$schedule->tableName()} s ON s.patient_id = pi.patient_id
			INNER JOIN {$room->tableName()} r ON r.id = s.room_id";
			
//use this if you don't want to pin to dietary_patient_info
/*		$sql .= " FROM {$patient->tableName()} AS p
			LEFT JOIN {$this->tableName()} pi ON p.id = pi.patient_id
			INNER JOIN {$schedule->tableName()} s ON s.patient_id = p.id
			INNER JOIN {$room->tableName()} r ON r.id = s.room_id";*/
			
			
		$sql .= " WHERE"; 
		
		if($location_id !== NULL) {
			$sql .= " s.location_id = :location_id AND";
		}
			
		if(strtolower($patient_id) == "all" || $patient_id == NULL) {
			$sql .= " (s.status = 'Approved' AND (s.datetime_discharge IS NULL OR s.datetime_discharge >= now())
												OR (s.status = 'Discharged' AND s.datetime_discharge >= now()))";
		} else {
			$sql .= "  p.public_id = :patient_id";
		}

		if ($location_id == 21) {
			$sql .= " ORDER BY pi.table_number ASC";
		} else {
			$sql .= " ORDER BY r.number ASC";
		}

		$tray_card_info = array();
		if(isset(input()->ForcePRODUCTION) && input()->ForcePRODUCTION == "rgbFBtfMwOm8n0pb9cQv")
		{
			//die("USE PRODUCTION!");
			try {
				@file_put_contents("/tmp/sql_query.txt", $sql, LOCK_EX);
				db()->getConnection()->exec("use ac_ahc");
				$tray_card_info = $this->fetchAll($sql, $params);
				db()->getConnection()->exec("use ac_dev");
			} catch (PDOException $e) {
				echo $e;
			}
		} else {
			$tray_card_info = $this->fetchAll($sql, $params);
		}
		
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

	//total rip off of fetchByLocation_allergy
	public function fetchByLocation_dislikes($location) {
		$dislike = $this->loadTable("Dislike");
		$schedule = $this->loadTable("Schedule");
		$room = $this->loadTable("Room");
		$patient = $this->loadTable('Patient');
		$pfi = $this->loadTable('PatientFoodInfo');

		$sql = "SELECT
				r.number,
				p.public_id,
				p.id AS patient_id,
				s.id AS schedule_id,
				p.last_name,
				p.first_name,
				s.location_id,
				GROUP_CONCAT(a.name separator ', ') as dislike_name
			FROM {$patient->tableName()} AS p
			INNER JOIN dietary_patient_info as dpi on dpi.patient_id = p.id
			INNER JOIN {$schedule->tableName()} s ON s.patient_id = p.id
			INNER JOIN {$room->tableName()} r ON r.id = s.room_id
			LEFT JOIN {$pfi->tableName()} pfi ON pfi.patient_id = p.id
			INNER JOIN {$dislike->tableName()} a ON a.id = pfi.food_id AND pfi.allergy = 0
			WHERE s.location_id = :location_id AND 
			 (s.status = 'Approved' AND (s.datetime_discharge IS NULL OR s.datetime_discharge >= now())
              OR (s.status = 'Discharged' AND s.datetime_discharge >= now()))  
			GROUP BY p.id
			ORDER BY r.number ASC";

		$params[":location_id"] = $location->id;
		//$params[":current_date"] = mysql_date();
		return $this->fetchAll($sql, $params);
	}

	public function fetchByLocation_allergy($location) {
		$allergy = $this->loadTable("Allergy");
		$schedule = $this->loadTable("Schedule");
		$room = $this->loadTable("Room");
		$patient = $this->loadTable('Patient');
		$pfi = $this->loadTable('PatientFoodInfo');

		$sql = "SELECT
				r.number,
				p.public_id,
				p.id AS patient_id,
				s.id AS schedule_id,
				p.last_name,
				p.first_name,
				s.location_id,
				GROUP_CONCAT(a.name separator ', ') as allergy_name
			FROM {$patient->tableName()} AS p
			INNER JOIN dietary_patient_info as dpi on dpi.patient_id = p.id
			INNER JOIN {$schedule->tableName()} s ON s.patient_id = p.id
			INNER JOIN {$room->tableName()} r ON r.id = s.room_id
			LEFT JOIN {$pfi->tableName()} pfi ON pfi.patient_id = p.id
			INNER JOIN {$allergy->tableName()} a ON a.id = pfi.food_id AND pfi.allergy = 1
			WHERE s.location_id = :location_id AND 
			 (s.status = 'Approved' AND (s.datetime_discharge IS NULL OR s.datetime_discharge >= now())
              OR (s.status = 'Discharged' AND s.datetime_discharge >= now()))  
			GROUP BY p.id
			ORDER BY r.number ASC";

		$params[":location_id"] = $location->id;
		//$params[":current_date"] = mysql_date();
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
	
	public function fetchTrayCardInfoByPatient($patient_id) {
		$sql = '(SELECT da.id, da.name, null as meal, null as is_other, "allergy" as type, NULL as sort_index, 0 as cat_sort FROM dietary_patient_food_info pf INNER JOIN dietary_allergy da ON da.id = pf.food_id WHERE allergy = 1 AND patient_id = :pt_id)
UNION
(SELECT dd.id, dd.name, null as meal, null as is_other, "dislike" as type, NULL as sort_index, 1 as cat_sort FROM dietary_patient_food_info pf INNER JOIN dietary_dislike dd ON dd.id = pf.food_id WHERE allergy = 0 AND patient_id = :pt_id)
UNION
(SELECT de.id, de.name, null as meal, null as is_other, "adapt_equip" as type, NULL as sort_index, 2 as cat_sort FROM dietary_patient_adapt_equip pe INNER JOIN dietary_adapt_equip de on pe.adapt_equip_id = de.id WHERE patient_id = :pt_id)
UNION
(SELECT ds.id, ds.name, null as meal, null as is_other, "supplement" as type, NULL as sort_index, 3 as cat_sort FROM dietary_patient_supplement ps INNER JOIN dietary_supplement ds ON ds.id = ps.supplement_id WHERE patient_id = :pt_id)
UNION
(SELECT dr.id, dr.name, meal, null as is_other, "special_req" as type, NULL as sort_index, 4 as cat_sort FROM dietary_patient_special_req pr INNER JOIN dietary_special_req dr ON pr.special_req_id = dr.id WHERE patient_id = :pt_id)
UNION
(SELECT db.id, db.name, meal, null as is_other, "beverage" as type, NULL as sort_index, 5 as cat_sort FROM dietary_patient_beverage pb INNER JOIN dietary_beverage db ON db.id = pb.beverage_id WHERE patient_id = :pt_id)
UNION
(SELECT ds.id, ds.name, time+0 as meal, time as is_other, "snack" as type, NULL as sort_index, 6 as cat_sort FROM dietary_patient_snack ps INNER JOIN dietary_snack ds ON ds.id = ps.snack_id WHERE patient_id = :pt_id)
UNION
(SELECT do.id, do.name, null as meal, is_other, "order" as type, sort_index, 7 as cat_sort FROM dietary_patient_diet_order po INNER JOIN dietary_diet_order do ON do.id = po.diet_order_id WHERE patient_id = :pt_id)
UNION
(SELECT dt.id, dt.name, null as meal, is_other, "texture" as type, sort_index, 8 as cat_sort FROM dietary_patient_texture pt INNER JOIN dietary_texture dt ON dt.id = pt.texture_id WHERE is_liquid != 1 AND patient_id = :pt_id)
UNION
(SELECT dt.id, dt.name, null as meal, is_other, "liquid" as type, sort_index, 8 as cat_sort FROM dietary_patient_texture pt INNER JOIN dietary_texture dt ON dt.id = pt.texture_id WHERE is_liquid = 1 AND patient_id = :pt_id)
UNION
(SELECT dh.id, dh.name, null as meal, is_other, "other" as type, sort_index, 9 as cat_sort FROM dietary_patient_other ph INNER JOIN dietary_other dh ON dh.id = ph.other_id WHERE patient_id = :pt_id)
ORDER BY cat_sort, type, meal, is_other, sort_index, id';
		$params = array(":pt_id" => $patient_id);
		return $this->fetchAll($sql, $params);
	}

}
