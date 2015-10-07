<?php

/**
 * undocumented class
 *
 * @package default
 * @author
 **/
class Activity extends Info {

	protected $table = "activity";


	/*
	 * Get all the activities for the week at the selected location
	 *
	 */
	public function fetchActivities($location_id, $start_date) {

		$sql = "SELECT
			activity.*

			FROM {$this->tableName()} activity

			WHERE activity.location_id = :location_id
			AND (
				(activity.datetime_start >= :start_date AND activity.datetime_start <= :end_date)
				OR (activity.repeat_week = :repeat_week AND activity.repeat_weekday = :repeat_weekday AND activity.datetime_start <= :start_date)
				OR (activity.repeat_weekday = :repeat_weekday AND activity.datetime_start = :start_date)
				)
			ORDER BY activity.datetime_start ASC LIMIT 7";

		$activities  = array();
		$activitiesArray = array();
		for ($i=0; $i<7; $i++) {
			$params = array(
				":location_id"			=> 		$location_id,
				":start_date" 			=> 		date("Y-m-d", strtotime("{$start_date} + {$i} days")),
				":end_date" 			=> 		date("Y-m-d 23:59:00", strtotime("{$start_date} + {$i} days")),
				":repeat_week"			=>		ceil (date("j", strtotime("{$start_date} + {$i} days"))/7),
				":repeat_weekday"		=> 		date("w", strtotime("{$start_date} + {$i} days"))
			);
			$result = $this->fetchAll($sql, $params);
		/*	pr(ceil (date("j", strtotime("{$start_date} + {$i} days"))/7));
			pr(date("w", strtotime("{$start_date} + {$i} days")));
			pr($result);*/
			if (empty ($result)) {
				$activities[$params[":start_date"]] = $this->fetchColumnNames();
			} else {
				$activities[$params[":start_date"]] = $result;
			}
			$activitiesArray[] = $activities;
		}
		//exit;
		return $activities;

	}



	public function fetchSchedule() {

		$sql = "SELECT
			activity.*
			FROM {$this->tableName()} activity
			WHERE activity.id = :activity_id";

		$params[":activity_id"] = $this->id;

		return $this->fetchOne($sql, $params);
	}











} // END classActivity extends AppModel
