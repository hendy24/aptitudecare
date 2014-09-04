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
				$location = $this->loadModel('Location', input()->location);
				$additionalStates = $this->loadModel('LocationLinkState')->getAdditionalStates($location->id);

				$params[':location_state'] = $location->state;
				$sql .= " AND (`physician`.`state` = :location_state";

				foreach ($additionalStates as $k => $s) {
					$params[":add_states{$k}"] = $s->state;
					$sql .= " OR `physician`.`state` = :add_states{$k}";
				}
				$sql .= ")";
			}

			$sql .= " ORDER BY `physician`.`last_name` ASC";

			$class = $this->loadModel('HealthcareFacility');

			$results = db()->fetchRows($sql, $params, $class);

		} else {
			$results = array();
		}

		$resultArray = array();
		foreach ($results as $k => $r) {
			$resultArray['suggestions'][$k]['value'] = $r->last_name . ', ' . $r->first_name ;
			$resultArray['suggestions'][$k]['data'] = $r->id;
		}

		json_return($resultArray);
	}


	public function submitAdd() {
		if (!auth()->has_permission('add', 'physicians')) {
			$error_messages[] = "You do not have permission to add new physicians";
			session()->setFlash($error_messages, 'error');
			$this->redirect();
		}

		if (isset (input()->id)) {
			$id = input()->id;
		} else {
			$id = null;
		}

		$physician = $this->loadModel('Physician', $id);

		if (input()->first_name != '') {
			$physician->first_name = input()->first_name;
		} else {
			$error_messages[] = "Enter the physician's first name";
		}

		if (input()->last_name != '') {
			$physician->last_name = input()->last_name;
		} else {
			$error_messages[] = "Enter the physician's last name";
		}

		if (input()->address != '') {
			$physician->address = input()->address;
		} 

		if (input()->city != '') {
			$physician->city = input()->city;
		} else {
			$error_messages[] = "Enter the city";
		} 

		if (input()->state != '') {
			$physician->state = input()->state;
		} else {
			$error_messages[] = "Enter the state";
		} 

		if (input()->zip != '') {
			$physician->zip = input()->zip;
		} else {
			$error_messages[] = "Enter the zip code";
		} 

		if (input()->phone != '') {
			$physician->phone = input()->phone;
		}

		if (input()->fax != '') {
			$physician->fax = input()->fax;
		}

		if (input()->email != '') {
			$physician->email = input()->email;
		}


		//	Breakpoint
		if (!empty($error_messages)) {
			session()->setFlash($error_messages, 'error');
			$this->redirect(input()->path);
		}
		
		if ($physician->save()) {
			session()->setFlash("Successfully added/edited {$physician->first_name} {$physician->last_name}", 'success');
			if (!isset (input()->isMicro) || input()->isMicro == 0) {
				$this->redirect(array('page' => 'data', 'action' => 'manage', 'type' => 'physicians'));
			} else {
				$this->redirect(array('page' => 'data', 'action' => 'close'));
			}
			
		}

	}

	public function getAdditionalData($data = false) {

	}

}