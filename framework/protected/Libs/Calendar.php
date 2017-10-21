<?php

class Calendar {

	public function getWeek($date = null) {
		if ($date == null) {
			$date = date('Y-m-d');
		}

		if (date('w', strtotime($date)) == 0) {
			$startOfWeek = date('Y-m-d', strtotime($date));
		} else {
			$startOfWeek = date('Y-m-d', strtotime('last Sunday', strtotime($date)));
		}
		

		$week = array();
		$i = 0;
		while ($i < 7) {
			$week[$i] = date('Y-m-d', strtotime($startOfWeek . " + {$i} days"));
			$i++;
		}
		return $week;
		
	}
}