<?php
/*
(c) 2008 - 2010 Intercarve Networks LLC
File: cls_calendar.php
Purpose:  This class exists primarily to facilitate the drawing of a calendar on a page
History:

2008-08-28 - bjc - Created
2010-03-16 - bjc - Updated for PHP5
*/


class Calendar {

	public $month;
	public $year;
	public $dom;
	public $reached_end = false;
	public $grid = false;
	
	public function __construct() {

	}
	
	public function gridDayOfYear($day) {
		return strftime("%j", strtotime($this->gridDay($day)));
	}
	
	// returns a vector of m/d/y strings representing the Sunday -> Saturday days of the week
	// to which $date belongs
	public static function getWeek($date) {
		$week = array();
		$todayDOWi = date("w", strtotime($date));			// eg, Tuesday is '2' (2 days since Sunday)
		$sunday = date("Y-m-d", strtotime("-{$todayDOWi} days", strtotime($date)));
		
		//
		//for ($i=0; $i<7; $i++) {
		//	$week[] = date("Y-m-d", strtotime("+$i days", strtotime($sunday)));
		//}		
		
		$week = self::getDateSequence($sunday, 7);
		return $week;
	}
	
	public static function getDateSequence($startDate, $count = 7) {
		$vector = array();
		for ($i=0; $i<$count; $i++) {
			$vector[] = date("Y-m-d", strtotime("+$i days", strtotime($startDate)));
		}
		return $vector;	
	}
	
	/* Provide $month as between 01 and 12 */
	public function set_month_year($month, $year) {
		if ($month == '')
			$month = date("m");
		if ($year == '')
			$year = date("Y");
		$this->month = intval($month);
		$this->year = intval($year);
		$this->reached_end = false;
	}

	public function next_month_year() {
		if ($this->last_day < 10)
			$ld = "0" . $this->last_day;
		else
			$ld = $this->last_day;
		$start = $this->year . "-" . $this->month . "-" . $ld;
		return array("m" => date("m", strtotime("+1 day", strtotime($start))), "y" => date("Y", strtotime("+1 day", strtotime($start))));
	}

	public function prev_month_year() {
		$start = $this->year . "-" . $this->month . "-01";
		return array("m" => date("m", strtotime("-1 day", strtotime($start))), "y" => date("Y", strtotime("-1 day", strtotime($start))));
	}

	public function month_name($pattern = NULL) {
		if (empty($pattern))
			$pattern = "F";
		$start = $this->year . "-" . $this->month . "-01";
		return date($pattern, strtotime($start));
	}

	public function set_last_day() {
		/* Figure out the last day of the month */
		$possible_lasts = array(28, 29, 30, 31, 32);
		foreach ($possible_lasts as $key => $d) {
			if (! checkdate($this->month, $d, $this->year)) {
				$this->last_day = $possible_lasts[$key - 1];
				break;
			}
		}
	}
	
	public function get_last_day() {
		return $this->last_day;
	}

	public function grid() {
		if ($this->grid == false) {
			$start = $this->year . "-" . $this->month . "-01";
			$start_dow = date("w", strtotime($start));
	
			$this->set_last_day();
	
			$grid = array();
	
			/* Make the first row - for now, lets leave out the previous month's trailing days */
			$row = array();
	
			for ($i=0; $i<$start_dow; $i++) {
				$row[] = NULL;			// emptyness - it's last month
			}
	
			$this->dom = 1;
	
			$row[] = $this->dom;					// the 1st of the month, in its correct spot in the row
	
			for ($i=$start_dow; $i<6; $i++) {
				$this->dom++;						// move to the next day
				$row[] = $this->dom; 			// put it in the row
			}
			$grid[] = $row;
	
			/* Maximum 5 rows after the first */
			for ($i=0; $i<6; $i++) {
				$grid[] = $this->get_row();
				if ($this->reached_end)
					break;
			}
			$this->grid = $grid;
		}

		return $this->grid;
	}
	
	public function gridDay($day) {
		if ($this->month < 10) {
			$month = "0{$this->month}";
		} else {
			$month = $this->month;
		}
		if ($day < 10) {
			$day = "0{$day}";
		}
		return $this->year . "-" . $month . "-" . $day;
	}

	public function get_row() {
		$row = array();
		for ($i=0; $i<7; $i++) {
			$this->dom++;
			$row[] = $this->dom;
			if ($this->dom == $this->last_day) {
				$this->reached_end = true;
				break;
			}
		}
		return $row;
	}


}
