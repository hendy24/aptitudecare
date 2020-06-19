<?php

class Contact extends AppData {

	protected $table = 'contact';

	public function fetchNames($term) {
		if ($term != null) {
			$tokens = explode(" ", $term);
			$params = array();

			$sql = "SELECT 
						id as data,
						CONCAT(first_name, \" \", last_name) AS value
					FROM {$this->tableName()} WHERE ";
			
			foreach ($tokens as $idx => $token) {
				$sql .= " last_name like :term{$idx} OR 
						first_name like :term{$idx} AND";
				$params[":term{$idx}"] = "%{$token}%";
			}
			$sql = rtrim($sql, " AND");

			return $this->fetchAll($sql, $params);
		} 
		
		return array();

	}
	
}