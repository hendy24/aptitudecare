<?php

/**
 * undocumented class
 **
 * @package default
 * @author
 **/
class Activity extends Info {

	protected $table = "activity";



	/*
	 * Get all the activities for the week at the selected location
	 *
	 */
	public function fetchActivities($location_id, $start_date, $num_days = 7) {
		
		$act_s = $this->loadTable('ActivitySchedule');

		$sql = "SELECT a.*, act_s.* 
					FROM {$this->tableName()} a 
					INNER JOIN {$act_s->tableName()} AS act_s ON act_s.activity_id = a.id 
					WHERE a.location_id = :location_id 
					AND 
					(
						act_s.date_start >= :start_date AND act_s.date_start <= :end_date
						OR 
						act_s.repeat_week = :repeat_week AND act_s.repeat_weekday = :repeat_weekday AND act_s.date_start <= :start_date
						OR 
						act_s.repeat_week IS NULL AND act_s.repeat_weekday = :repeat_weekday AND act_s.date_start <= :start_date 
						OR
						act_s.daily = 1
					)
					ORDER BY act_s.date_start";


		for ($i=0; $i < $num_days; $i++) {
			$params = array(
				":location_id"			=> 		$location_id,
				":start_date" 			=> 		date("Y-m-d", strtotime("{$start_date} + {$i} days")),
				":end_date" 			=> 		date("Y-m-d", strtotime("{$start_date} + {$i} days")),
				":repeat_week"			=>		ceil (date("j", strtotime("{$start_date} + {$i} days"))/7),
				":repeat_weekday"		=> 		date("w", strtotime("{$start_date} + {$i} days"))
			);
			$result = $this->fetchAll($sql, $params);

			if (empty ($result)) {
				$activities[$params[":start_date"]] = $this->fetchColumnNames();
			} else {
				$activities[$params[":start_date"]] = $result;
			}

			$activitiesArray[] = $activities;
		}
		return $activities;
	}

	// public function fetchActivities($location_id, $start_date) {
	// 	$activity_schedule = $this->loadTable('ActivitySchedule');

	// 	$sql = "SELECT
	// 		activity.*,
	// 		activity_schedule.*

	// 		FROM {$this->tableName()} activity
	// 		INNER JOIN {$activity_schedule->tableName()} AS activity_schedule ON activity_schedule.activity_id = activity.id

	// 		WHERE activity.location_id = :location_id
	// 		AND (
	// 			(activity_schedule.datetime_start >= :start_date AND activity_schedule.datetime_start <= :end_date)
	// 			OR (activity_schedule.repeat_week = :repeat_week AND activity_schedule.repeat_weekday = :repeat_weekday AND activity_schedule.datetime_start <= :start_date)
	// 			OR (activity_schedule.repeat_weekday = :repeat_weekday AND activity_schedule.datetime_start = :start_date)
	// 			)
	// 		ORDER BY activity_schedule.datetime_start ASC LIMIT 7";

	// 	$activities  = array();
	// 	$activitiesArray = array();
	// 	for ($i=0; $i<7; $i++) {
	// 		$params = array(
	// 			":location_id"			=> 		$location_id,
	// 			":start_date" 			=> 		date("Y-m-d", strtotime("{$start_date} + {$i} days")),
	// 			":end_date" 			=> 		date("Y-m-d 23:59:00", strtotime("{$start_date} + {$i} days")),
	// 			":repeat_week"			=>		ceil (date("j", strtotime("{$start_date} + {$i} days"))/7),
	// 			":repeat_weekday"		=> 		date("w", strtotime("{$start_date} + {$i} days"))
	// 		);
	// 		$result = $this->fetchAll($sql, $params);
	// 	/*	pr(ceil (date("j", strtotime("{$start_date} + {$i} days"))/7));
	// 		pr(date("w", strtotime("{$start_date} + {$i} days")));
	// 		pr($result);*/
	// 		if (empty ($result)) {
	// 			$activities[$params[":start_date"]] = $this->fetchColumnNames();
	// 		} else {
	// 			$activities[$params[":start_date"]] = $result;
	// 		}
	// 		$activitiesArray[] = $activities;
	// 	}
	// 	return $activities;

	// }



	public function fetchSchedule() {
		$activity_schedule = $this->loadTable('ActivitySchedule');

		$sql = "SELECT
			activity.*,
			activity_schedule.*
			FROM {$this->tableName()} activity
			INNER JOIN {$activity_schedule->tableName()} AS activity_schedule ON activity_schedule.activity_id = activity.id
			WHERE activity.id = :activity_id";

		$params[":activity_id"] = $this->id;

		return $this->fetchOne($sql, $params);
	}











} // END classActivity extends AppModel
