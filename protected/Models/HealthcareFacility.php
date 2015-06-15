<?php

class HealthcareFacility extends AppData {

	protected $table = 'healthcare_facility';

	protected $belongsTo = array(
		'LocationType' => array(
			'table' => 'ac_location_type',
			'join_type' => 'INNER',
			'inner_key' => 'location_type_id',
			'foreign_key' => 'id',
			'join_field' => array(
				'column' => 'description',
				'name' => 'ac_location_type'
			)
		)
	);

	protected $_manage_fields = array(
		'public_id',
		'name',
		'city',
		'state',
		'zip',
		'phone',
		'location_type'
	);

	protected $_add_fields = array(
		'name',
		'address',
		'city',
		'state',
		'zip',
		'phone',
		'fax'
	); 


	public function searchFacilities($term, $location = false) {
		$tokens = explode(' ', $term);
		$sql = "SELECT * FROM ac_healthcare_facility WHERE ";
		foreach ($tokens as $idx => $token) {
			$token = trim($token);
			$sql .= " name like :term{$idx} AND";
			$params[":term{$idx}"] = "%{$token}%";
		}
		$sql = rtrim($sql, ' AND');

		if ($location) {
			$location = $this->loadTable('Location', $location);
			$additionalStates = $this->loadTable('LocationLinkState')->getAdditionalStates($location->id);

			$params[':location_state'] = $location->state;
			$sql .= " AND ac_healthcare_facility.state = :location_state";

			foreach ($additionalStates as $k => $s) {
				$params[":add_states{$k}"] = $s->state;
				$sql .= " OR ac_healthcare_facility.state = :add_states{$k}";
			}

		} elseif (isset (input()->state) && input()->state != '') {
			$params[':state'] = input()->state;
			$sql .= " AND ac_healthcare_facility.state = :state";
		}

		$sql .= " ORDER BY `ac_healthcare_facility`.`name` ASC";

		return $this->fetchAll($sql, $params);
	}

}