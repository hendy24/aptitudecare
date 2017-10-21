<?php

class AppModel {

	protected $prefix = false;

	public function generate($id = null, $class = null) {
		if ($id == null) {
			$class = get_called_class();
			return new $class;
		} else {
			if ($class != null) {
				$called_class = $class;
			} else {
				$called_class = get_called_class();
			}

			$class = new $called_class;
			return $this->fetchById($id, $class);

		}
	}
	
	
	public function fetchOne($sql, $params = array(), $class = null) {
		if ($class != null) {
			$called_class = $class;
		} else {
			$called_class = get_called_class();
		}
		$class = new $called_class;

		if ($sql == null) {
			$sql = "SELECT * FROM `{$this->tableName()}`";
			if (!empty($params)) {
				foreach ($params as $k => $p) {
					$sql .= " WHERE {$k} = {$p} AND";
				}

				$sql = trim($sql, "AND");
			}

		}

		try {
			return db()->fetchRow($sql, $params, $class);
		} catch (PDOException $e) {
			echo $e;
		}
	}
	
	public function fetchAll($sql = null, $params = array()) {
		
		$called_class = get_called_class();
		$class = new $called_class;

		$table = $this->tableName();

		if ($sql == null) {
			$sql = "SELECT * FROM `{$table}`";
			if (!empty($params)) {
				foreach ($params as $k => $p) {
					$sql .= " WHERE {$k} = {$p} AND";
				}

				$sql = trim($sql, "AND");
			}

		}
		
		try {
			return db()->fetchRows($sql, $params, $class);
		} catch (PDOException $e) {
			echo $e;
		}
	}



	public function fetchByColumn ($column, $value) {
		$sql = "SELECT t.* FROM {$this->tableName()} t WHERE :column = :value";
		
		$params = array(
			":column" => $column,
			":value" => $value
		);

		return db()->fetchRows($sql, $params, $this);
	}


	public function fetchCustom($sql, $params = array()) {
		$called_class = get_called_class();
		$class = new $called_class;
		$table = $class->fetchTable();
		return db()->fetchRows($sql, $params, $class);
	}

	public function fetchAllData() {
		$table = $this->fetchTable();
		$sql = "SELECT `{$table}`.* FROM `{$table}`";
		$params = array();

		try {
			return db()->fetchRows($sql, $params, $this);
		} catch (PDOException $e) {
			echo $e;
		}

	}

	public function update($sql, $params) {
		return db()->update($sql, $params);		
	}

	public function save($data = false, $database = false) {
		if (!$database) {
			$database = db()->dbname;
		} 

		try {
			if (isset ($this->id) && $this->id != '') {
				db()->updateRow($this, $database);
			} elseif (!empty ($this)) {
				$this->id =  db()->saveRow($this, $database);
				return $this;
			} elseif ($data != false) {
				if (!isset ($this->id) || $this->id != '') {
					$this->id = db()->saveRow($data, $database);
					return $this;
				} else {
					db()->updateRow($data);
				}	
			} else {
				return false;	
			}
		} catch (PDOException $e) {
			echo $e;
		}
		return true;
	}



	/*
	 * -------------------------------------------------------------------------
	 *  DELETE ITEM BY ID
	 * -------------------------------------------------------------------------
	 */
	
	public function delete($data = false) {
		try {
			if ($data) {
				db()->destroy($data);
			} else {
				db()->destroy($this);
			}

		} catch (PDOException $e) {
			echo $e;
		}

		return true;
	}


	public function deleteQuery($sql, $params = null) {
		try {
			db()->destroyQuery($sql, $params);
		} catch (PDOException $e) {
			echo $e;
		}

		return true;
	}
	
	public function fetchById($id, $className = null) {
		$params[':id'] = $id;

		if ($className != null) {
			$model = $className;
		} else {
			$model = get_class($this);
		}

		$class = new $model;
		$table = $class->tableName();

		$sql = "SELECT `{$table}`.*";
		$belongsTo = $class->fetchBelongsTo();

		if (!empty ($belongsTo)) {
			// foreach ($class->belongsTo as $k => $b) {
			// 	if (isset ($b['join_field'])) {
			// 		$sql .= ", `{$b['table']}`.`{$b['join_field']['column']}` AS {$b['join_field']['name']} ";
			// 	}
					
			// }

			$sql .= " FROM `{$table}`";

			// foreach ($belongsTo as $k => $b) {
			// 	$sql .= " {$b['join_type']} JOIN `{$b['table']}` ON `{$b['table']}`.`{$b['foreign_key']}` = `{$table}`.`{$b['inner_key']}`";
			// }
		} else {
			$sql .= " FROM `{$table}`";
		} 

		$sql .= " WHERE `{$table}`.";

		if (is_numeric($id)) {
			if ($model == 'HomeHealthSchedule') {
				$sql .= "patient_id=:id";
			} else {
				$sql .= "id=:id";
			}
			
		} else {
			$sql .= "public_id=:id";
		}
		return $this->fetchOne($sql, $params, $class);
	}


	public function fetchFields() {
		return $this->_manage_fields;
	}

	public function fetchTable() {
		return $this->table;
	}

	public function fetchBelongsTo() {
		if (isset ($this->belongsTo)) {
			return $this->belongsTo;
		}
		return false;
	}

	public function fetchHasMany() {
		if (isset ($this->hasMany)) {
			return $this->hasMany;
		}
		return false;
	}

	public function fetchColumnsToInclude() {
		return $this->_add_fields;
	}




	public function deleteCurrent($user_id) {
		$sql = "DELETE FROM {$this->tableName()} WHERE user_id = :user_id";
		$params[":user_id"] = $user_id;
		return $this->deleteQuery($sql, $params);
	}


	/*
	 * -------------------------------------------------------------------------
	 *  FETCH ALL DATA FOR MANAGE PAGE
	 * -------------------------------------------------------------------------
	 */

	public function fetchManageData($loc = false, $orderby = false) {
		if (isset (input()->page_num)) {
			$_pageNum = input()->page_num;
		} else {
			$_pageNum = false;
		}
		
		$pagination = new Paginator();
		$results = $pagination->fetchResults($this, $loc, $orderby, $_pageNum);

		if (!empty ($results)) {
			return $results; 
		} 

		return false;
	}


	// public function fetchColumnNames() {
	// 	$called_class = get_called_class();
	// 	$class = new $called_class;
	// 	$table = $class->fetchTable();
	// 	$sql = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`=:dbname AND `TABLE_NAME`=:table";
	// 	$params[':table'] = $table;
	// 	$params[':dbname'] = db()->dbname;
	// 	try {
	// 		return db()->fetchColumns($sql, $params, $class);
	// 	} catch (PDOException $e) {
	// 		echo $e;
	// 	}
	// }


	public function fetchColumnNames() {
		if ($this->prefix != false) {
			$table = $this->prefix . "_" . $this->fetchTable();
		} else {
			$table = $this->fetchTable();
		}
		if (isset ($this->dbname)) {
			$dbname = $this->dbname;
		} else {
			$dbname = false;
		}
		$columnNames =  db()->fetchColumnNames($table, $dbname);

		foreach ($columnNames as $n) {
			$this->$n = null;
		}

		return $this;
	}


	public function fetchRowCount($states) {
		$state = null;
		foreach ($states as $k => $s) {
			$state .= "'{$s->state}', ";
		}
		$state = trim($state, ", ");

		$sql = "SELECT count(id) AS items FROM {$this->tableName()} WHERE {$this->tableName()}.state IN ($state)";

		return $this->fetchOne($sql);
	}


	public function fetchByLocation($location_id) {
		$sql = "SELECT * FROM {$this->tableName()} WHERE location_id = :location_id";
		$params[":location_id"] = $location_id;
		return $this->fetchAll($sql, $params);
	}


	public function fetchOneByLocation($location_id) {
		$sql = "SELECT * FROM {$this->tableName()} WHERE location_id = :location_id LIMIT 1";
		$params[":location_id"] = $location_id;
		return $this->fetchOne($sql, $params);
	}

	public function fullName() {
		return $this->last_name . ", " . $this->first_name;
	}

	public function fullAddress() {
		$address =  $this->address . "<br>";
		$address .= $this->city . ", " . $this->state . " " . $this->zip . "<br>";
		$address .= $this->phone;

		return $address;
	}
	
	public function tableName() {
		if ($this->prefix) {
			return $this->prefix . "_" . $this->table;
		} else {
			return $this->table;
		}
		
	}

	public function joinName() {
		return $this->join;
	}


	public function loadTable($name = false, $id = false) {
		if ($name) {
			if (file_exists (APP_PROTECTED_DIR . DS . 'Models' . DS . $name . '.php')) {
				require_once (APP_PROTECTED_DIR . DS . 'Models' . DS . $name . '.php');
			} 

			if (class_exists($name)) {
				$class = new $name;
			} else {
				smarty()->assign('message', "Could not find the class {$name}");
				//$this->loadView('error', 'index');
				exit;
			}
		}

		if ($id) {
			return $class->fetchById($id);
		} else {
			//  This is an empty object, get the column names
			//	If the table is schedule then it is trying to access the admission dashboard
			//	we won't have access to this and don't need to get the column names from that
			//	table anyway.
			if ($class->fetchTable() != "schedule") {
				return $class->fetchColumnNames();
			} else {
				//	If the table variable isn't set in the model, then just return an empty object.
				return $class;
			}

		}


		return false;
	}	
	
	
}