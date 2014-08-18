<?php

class PhysiciansController extends MainController {

	public function searchPhysicians() {
		$this->template = 'blank';
		
		$term = input()->query;
		if ($term != '') {
			$tokens = explode(' ', $term);
			$params = array();

			$sql = "SELECT * FROM `physician` WHERE ";
			foreach ($tokens as $idx => $token) {
				$token = trim($token);
				$sql .= " first_name like :term{$idx} OR last_name like :term{$idx} AND";
				$params[":term{$idx}"] = "%{$token}%";
			}

			$sql = rtrim($sql, ' AND');

			if (isset (input()->location)) {
				$location = $this->loadModel('Location', input()->location)->fetchLocationStates();
			}

			die();
		}
	}

}