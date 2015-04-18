<?php

class MenuMod extends Dietary {

	protected $table = "menu_mod";

	public function countMenuMods($num_days = false, $start_date = false, $end_date = false) {
		
		$location = $this->loadTable('Location');

		// get the count
		$sql = "SELECT loc.id, loc.public_id, loc.name, (SELECT count(dmm.id) FROM {$this->tableName()} dmm WHERE dmm.location_id = loc.id AND ";

		if ($num_days) {
			$date = date("Y-m-d", strtotime("now - $num_days days"));
			$sql .= " dmm.date >= :date)";
			$params = array(
				":date" => $date
			);
		} elseif ($start_date && $end_date) {
			$sql = " dmm.date BETWEEN :start_date AND :end_date)";
			$params = array(":start_date" => $start_date, ":end_date" => $end_date);
		} else {
			return false;
		}

		// only get the skilled nursing facilities and then group by location
		$sql .= " count FROM {$location->tableName()} loc";

		return $this->fetchAll($sql, $params);

	}


	public function paginateMenuMods($location_id, $num_days, $page = false) {
		$menuItem = $this->loadTable("MenuItem");
		$user = $this->loadTable("User");
		$date = date("Y-m-d", strtotime("now - $num_days days"));
		$params[":location_id"] = $location_id;
		$params[":date"] = $date;

		$sql = "SELECT count(id) as menu_mods FROM {$this->tableName()} mm WHERE mm.location_id = :location_id and mm.date >= :date";
		$count = $this->fetchOne($sql, $params);




		$sql = "SELECT mm.id, mm.public_id, mm.content AS mod_content, mm.reason as mod_reason, mm.date, mi.meal_id, mi.day, mi.content as content, CONCAT(user.first_name, \" \", user.last_name) as user_name FROM {$this->tableName()} mm INNER JOIN {$menuItem->tableName()} mi ON mi.id = mm.menu_item_id INNER JOIN {$user->tableName()} user ON user.id = mm.user_id WHERE mm.location_id = :location_id AND mm.date >= :date";

		$pagination = new Paginator();
		$pagination->default_ipp = 8;
		$pagination->items_total = $count->menu_mods;
		return $pagination->paginate($sql, $params, $this, $page);
	}
	
}