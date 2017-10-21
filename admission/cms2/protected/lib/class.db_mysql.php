<?php

class db_mysql {

    public $db;
	public $username;
	public $password;
	public $dbname;
	public $host;
	public $dsn;
	private static $timerIdx = 0;
	private static $timerTotal = 0;
	private static $timers = array();

	private $_debugSQL;
	

	// adds a new timer to the stack and returns the array index of that timer
	private static function startTimer() {
		array_push(self::$timers, array(microtime(true), false));
		return count(self::$timers) - 1;
	}
	
	// marks end time for given timer and returns the runtime
	private static function stopTimer($idx) {
		self::$timers[$idx][1] = microtime(true);
		return self::$timers[$idx][1] - self::$timers[$idx][0];
	}
	
	// gets total of all timers (total SQL runtime of the page load)
	public static function getRuntime() {
		$total = 0;
		foreach (self::$timers as $timer) {
			$total += $timer[1] - $timer[0];
		}
		return $total;
	}

    /**
     *
     * Set variables
     *
     */
    public function __set($name, $value)
    {
        switch($name)
        {
            case 'username':
            $this->username = $value;
            break;

            case 'password':
            $this->password = $value;
            break;

			case 'dbname':
			$this->dbname = $value;
			break;

			case 'host':
			$this->host = $value;
			break;
		
            case 'dsn':
            $this->dsn = $value;
            break;

            default:
            throw new Exception("$name is invalid");
        }
    }

    /**
     *
     * @check variables have default value
     *
     */
    public function __isset($name)
    {
        switch($name)
        {
            case 'username':
            $this->username = null;
            break;

            case 'password':
            $this->password = null;
            break;
        }
    }

	/**
	 *
	 * @Connect to the database and set the error mode to Exception
	 *
	 * @Throws PDOException on failure
	 *
	 */
	public function conn()
	{
		isset($this->username);
		isset($this->password);
		if (!$this->db instanceof PDO)
		{
			try {
				$this->dsn = "mysql:dbname={$this->dbname};host={$this->host}";
				$this->db = new PDO($this->dsn, $this->username, $this->password, array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true, PDO::ATTR_EMULATE_PREPARES => true));
				$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (PDOException $e) {
				echo "Connection error.";
				die();
			}

		}
	}

	public function insert_id() {
		$this->conn();
		return $this->db->lastInsertId();
	}

	public function beginTransaction() {
		$this->conn();
		return $this->db->beginTransaction();
	}

	public function rollBack() {
		$this->conn();
		return $this->db->rollBack();
	}

	public function commit() {
		$this->conn();
		return $this->db->commit();
	}
	
	public function debugSQL() {
		return $this->_debugSQL;
	}


	public function getRows($table, $params = array(), $cls = "std", $only_fields = false, $orderby = false, $count = false, $offset = false, $countRows = false, &$foundRows = false)
	{
		$timer = Logging::startTimer(false, "mysql");
		$this->conn();
		
		
		if ($only_fields !== false) {
			$fields = implode(",", $only_fields);
		} else {
			$fields = "*";
		}
		if ($countRows == true) {
			$fields = "SQL_CALC_FOUND_ROWS " . $fields;
		}

		$sql = "SELECT {$fields} FROM `$table`";
		if (is_array($params)) {
			if (count($params) > 0) {
				$sql .= " WHERE";
			}
			$_iter = 0;
			$_params = array();
			foreach ($params as $k => $v) {
				$_iter++;
				if (is_null($v)) {
					$sql .= " `{$k}` IS NULL";
				} else {
					$sql .= " `{$k}`=:{$k}";
					$_params[":{$k}"] = $v;
				}
				if ($_iter < count($params)) {
					$sql .= " AND";
				}
			}
		} elseif (strtoupper(substr(trim($params), 0, 5) == "WHERE")) {
			$sql .= $params;
		}

		if ($orderby !== false) {
			$sql .= " order by {$orderby}";
		}

		if ($count !== false && $offset !== false) {
			$sql .= " limit {$offset}, {$count}";
		} elseif ($count !== false && $offset == false) {
			$sql .= " limit {$count}";
		}
		$this->_debugSQL = $sql;
		
		$stmt = $this->db->prepare($sql);
		if ($cls == 'std') {
			$stmt->execute($_params);
			if ($countRows == true) {
				$foundRows = $this->db->query("select FOUND_ROWS()")->fetchColumn();
			}
			$records = $stmt->fetchAll(PDO::FETCH_OBJ);

		} else {
			$stmt->execute($_params);
			if ($countRows == true) {
				$foundRows = $this->db->query("select FOUND_ROWS()")->fetchColumn();
			}
			$records = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $cls, array("___NO_LOAD_EMPTY___"));
		}
		Logging::stopTimer($timer, "mysql");
		return $records;
	}

	/***
	 *
	 * @select values from table
	 *
	 * @access public
	 *
	 * @param string $table The name of the table
	 *
	 * @param string $fieldname
	 *
	 * @param string $id
	 *
	 * @return array on success or throw PDOException on failure
	 *
	 */
	public function getRow($table, $params = array(), $cls = 'std', $only_fields = false) {
		$records = $this->getRows($table, $params, $cls, $only_fields);
		return current($records);
	}

	/**
	 *
	 * @execute a raw query
	 *
	 * @access public
	 *
	 * @param string $sql
	 *
	 * @return array
	 *
	 */
	public function getRowsCustom($sql, $params = array(), $cls = 'std', $count = false, $offset = false, $countRows = false, &$foundRows = false)
	{
		$timer = Logging::startTimer(false, "mysql");
		$this->conn();
		
		// mangle the SQL
		if ($countRows == true) {
			// remove newlines from SQL so the preg works
			$sql = str_replace("\n", "", $sql);
			$sql = preg_replace_callback("/^select\s+(.*?)\s+?from/i", function($matches) {
				return str_replace($matches[1], "SQL_CALC_FOUND_ROWS " . $matches[1], $matches[0]);
			}, $sql);
		}
		if ($count !== false && $offset !== false) {
			$sql .= " limit {$offset}, {$count}";
		} elseif ($count !== false && $offset == false) {
			$sql .= " limit {$count}";
		}
		$this->_debugSQL = $sql;
		$stmt = $this->db->prepare($sql);
		if ($cls == 'std') {
			$stmt->execute($params);
			if ($countRows == true) {
				$foundRows = $this->db->query("select FOUND_ROWS()")->fetchColumn();
			}
			$records = $stmt->fetchAll(PDO::FETCH_OBJ);
		} else {
			$stmt->execute($params);
			if ($countRows == true) {
				$foundRows = $this->db->query("select FOUND_ROWS()")->fetchColumn();
			}
			$records = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $cls, array("___NO_LOAD_EMPTY___"));
		}
		
		Logging::stopTimer($timer, "mysql");
		
		return $records;

	}


	public function getRowCustom($sql, $params = array(), $cls = 'std') {
		$records = $this->getRowsCustom($sql, $params, $cls);
		return current($records);
	}


	public function query($sql, $params) {
		$timer = Logging::startTimer(false, "mysql");
		$this->conn();
		$stmt = $this->db->prepare($sql);
		Logging::stopTimer($timer, "mysql");
		return $stmt->execute($params);
	}

	/**
	 *
	 * @run a raw query
	 *
	 * @param string The query to run
	 *
	 */
	public function rawQuery($sql)
	{
		$timer = Logging::startTimer(false, "mysql");
		$this->conn();
		$this->db->query($sql);
		Logging::stopTimer($timer, "mysql");
	}


	public function rawQueryMulti($sql) {
		$timer = Logging::startTimer(false, "mysql");
		$mysqli = new mysqli($this->host, $this->username, $this->password, $this->dbname);
		$mysqli->multi_query($sql);
		Logging::stopTimer($timer, "mysql");
		return $mysqli->error;
	}

	/**
	 *
	 * @Insert a value into a table
	 *
	 * @acces public
	 *
	 * @param string $table
	 *
	 * @param array $pairs
	 *
	 * @return int The last Insert Id on success or throw PDOexeption on failure
	 *
	 */
		
	public function simple_insert($table, $pairs)
	{
		if (count($pairs) == 0) {
			return true;
		}
		$timer = Logging::startTimer(false, "mysql");
		$this->conn();

		/* By convention, if a column is nullable, we want to store NULL instead of an empty string. */
		$nullables = $this->get_nullable_columns($table);
		foreach ($pairs as $k => $v) {
			if ($v === '' && in_array($k, $nullables)) {
				$pairs[$k] = NULL;
			} elseif (is_null($v) && ! in_array($k, $nullables)) {
				$pairs[$k] = '';
			}
		}

		$bound = array();

		$sql = "INSERT INTO `$table` SET";
		$_count = 0;
		foreach ($pairs as $key => $value) {
			$_count++;
			$sql .= " `{$key}`=:{$key}";
			$bound[":$key"] = $value;
			if ($_count != count($pairs)) {
				$sql .= ",";
			}
		}
		
		/*
		* ** prepare and execute ***/
		$stmt = $this->db->prepare($sql);
		$result = $stmt->execute($bound);
		Logging::stopTimer($timer, "mysql");
		return $result;
	}

	/**
	 *
	 * @Update values into a table
	 *
	 * @acces public
	 *
	 * @param string $table
	 *
	 * @param array $pairs col=>val pairs
	 *
	 * @return int The last Insert Id on success or throw PDOexeption on failure
	 *
	 */
	public function simple_update($table, $pairs, $id, $pk = "id")
	{
		if (count($pairs) == 0) {
			return true;
		}

		$timer = Logging::startTimer(false, "mysql");
		$this->conn();
		/* By convention, if a column is nullable, we want to store NULL instead of an empty string. */
		$nullables = $this->get_nullable_columns($table);
		$all_columns = $this->get_table_columns($table);

		foreach ($pairs as $k => $v) {
			if ($v === '' && in_array($k, $nullables)) {
				$pairs[$k] = NULL;
			} elseif (is_null($v) && ! in_array($k, $nullables)) {
				$pairs[$k] = '';
			}
		}

		
		$fieldnames = array_keys($pairs);
		
		/*** now build the query ***/
		$size = sizeof($fieldnames);
		$i = 1;
		$sql = "UPDATE `$table` SET";
		$_count = 0;
		$_total = count($pairs);
		foreach ($pairs as $key => $val) {
			$_count++;
			$sql .= " `{$key}` = :{$key}";
			if ($_count < $_total) {
				$sql .= ",";
			}

		}
		$sql .= " where `$pk`=:id";
		$pairs[":id"] = $id;
		/*** prepare and execute ***/
		$stmt = $this->db->prepare($sql);
		$results = $stmt->execute($pairs);
		Logging::stopTimer($timer, "mysql");
		return $results;
	}

	public function truncate($table) {
		$timer = Logging::startTimer(false, "mysql");
		if ( $this->table_exists($table)) {
			$this->rawQuery("truncate table `$table`");
		}
		Logging::stopTimer($timer, "mysql");
	}

	/**
	 *
	 * @Update value in a table
	 *
	 * @access public
	 *
	 * @param string $table
	 *
	 * @param array $value The new value
	 *
	 * @param string $pk The primary key
	 *
	 * @param string $id The id
	 *
	 * @throws PDOException on failure
	 *
	 */
	public function update($table, $fieldname, $value, $id, $pk = "id")
	{
		$timer = Logging::startTimer(false, "mysql");
		$this->conn();
		$sql = "UPDATE `$table` SET `$fieldname`=:value WHERE `$pk` = :id";
		$stmt = $this->db->prepare($sql);
		$pairs = array(
			 ":value" => $value,
			 ":id" => $id
		 );
		$results = $stmt->execute($pairs);
		Logging::stopTimer($timer, "mysql");
		return $results;
	}


	/**
	 *
	 * @Delete a record from a table
	 *
	 * @access public
	 *
	 * @param string $table
	 *
	 * @param string $fieldname
	 *
	 * @param string $id
	 *
	 * @throws PDOexception on failure
	 *
	 */
	public function delete($table, $fieldname, $id)
	{
		$timer = Logging::startTimer(false, "mysql");
		$this->conn();
		$sql = "DELETE FROM `$table` WHERE `$fieldname` = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_STR);
		$result = $stmt->execute();
		Logging::stopTimer($timer, "mysql");
		return $result;
	}

	public function get_column($table, $column) {
		$timer = Logging::startTimer(false, "mysql");
		$this->conn();
		$sql = "select * from `information_schema`.`COLUMNS` where `TABLE_NAME`=:table and `COLUMN_NAME`=:column";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(":table", $table);
		$stmt->bindParam(":column", $column);
		$stmt->execute();

		$results = $stmt->fetchObject();
		Logging::stopTimer($timer, "mysql");
		return $results;
	}

	public function get_table_columns($table) {
		$timer = Logging::startTimer(false, "mysql");
		$this->conn();
		$sql = "select `COLUMN_NAME` from `information_schema`.`COLUMNS` where `TABLE_SCHEMA`=:schema and `TABLE_NAME`=:table";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(":schema", $this->dbname);
		$stmt->bindParam(":table", $table);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
		Logging::stopTimer($timer, "mysql");
		return array_values($results);
	}

	public function get_table_columns_with_meta($table) {
		$timer = Logging::startTimer(false, "mysql");
		$this->conn();
		$sql = "select * from `information_schema`.`COLUMNS` where `TABLE_SCHEMA`=:schema and `TABLE_NAME`=:table";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(":schema", $this->dbname);
		$stmt->bindParam(":table", $table);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

		Logging::stopTimer($timer, "mysql");
		return $results;
	}
	
	public function get_all_columns_with_meta() {
		$timer = Logging::startTimer(false, "mysql");
		$this->conn();
		$sql = "select * from `information_schema`.`COLUMNS` where `TABLE_SCHEMA`=:schema ORDER BY `TABLE_NAME`";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(":schema", $this->dbname);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

		Logging::stopTimer($timer, "mysql");
		return $results;
	}
	
	function enumoptions($table, $column) {
		$timer = Logging::startTimer(false, "mysql");
		$this->conn();
		$sql = "select `COLUMN_TYPE` from `information_schema`.`COLUMNS` where `TABLE_SCHEMA`=:schema and `TABLE_NAME`=:table and `COLUMN_NAME`=:column";

		$query = $this->db->prepare($sql);
		$pairs = array(
			":schema" => $this->dbname,
			":table" => $table,
			":column" => $column
		);
		$query->execute($pairs);
		$record = $query->fetch(PDO::FETCH_ASSOC);
		Logging::stopTimer($timer, "mysql");
		
		$def = $record['COLUMN_TYPE'];

		if (! preg_match("/^enum/", $def)) {
			return false;
		} else {
			preg_match("/^enum\((.*)\)$/", $def, $matches);
			$_vals = explode(",", $matches[1]);
			$vals = array();
			foreach ($_vals as $v) {
				$vals[] = trim($v, "'");
			}
			return $vals;
		}

	}

	public function enumHasOption($table, $column, $value) {
		$options = $this->enumoptions($table, $column);
		return in_array($value, $options);
	}

	public function get_column_values($table, $column, $count = false, $offset = false) {
		$timer = Logging::startTimer(false, "mysql");
		$this->conn();
		$sql = "select `{$column}` from `{$table}`";
		if ($count != false) {
			if ($offset != false) {
				$sql .= " LIMIT {$count}";
			} else {
				$sql .= " LIMIT {$offset}, {$count}";
			}
		}
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
		Logging::stopTimer($timer, "mysql");
		return array_values($results);
	}


	public function get_nullable_columns($table) {
		$timer = Logging::startTimer(false, "mysql");
		$this->conn();
		$sql = "select `COLUMN_NAME` from `information_schema`.`COLUMNS` where `TABLE_SCHEMA`=:schema and `TABLE_NAME`=:table and `IS_NULLABLE`='YES'";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(":schema", $this->dbname);
		$stmt->bindParam(":table", $table);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
		Logging::stopTimer($timer, "mysql");
		return $results;
	}

	public function column_is_nullable($table, $column) {
		$timer = Logging::startTimer(false, "mysql");
		$this->conn();
		$sql = "select `COLUMN_NAME` from `information_schema`.`COLUMNS` where `TABLE_SCHEMA`=:schema and `TABLE_NAME`=:table and `COLUMN_NAME`=:column and `IS_NULLABLE`='YES'";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(":schema", $this->dbname);
		$stmt->bindParam(":table", $table);
		$stmt->bindParam(":column", $column);
		$stmt->execute();
		$record = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
		if (count($record) > 0 && $record !== false) {
			Logging::stopTimer($timer, "mysql");
			return true;
		}
		Logging::stopTimer($timer, "mysql");
		return false;
	}

	public function table_exists($table) {
		$timer = Logging::startTimer(false, "mysql");
		$this->conn();
		$sql = "select `TABLE_NAME` from `information_schema`.`TABLES` where `TABLE_SCHEMA`=:schema and `TABLE_NAME`=:table";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(":schema", $this->dbname);
		$stmt->bindParam(":table", $table);
		$stmt->execute();

		if ($stmt->rowCount() > 0) {
			Logging::stopTimer($timer, "mysql");
			return true;
		}
		Logging::stopTimer($timer, "mysql");
		return false;
	}

	public function column_exists($table, $column) {
		$timer = Logging::startTimer(false, "mysql");
		$this->conn();
		$sql = "select `COLUMN_NAME` from `information_schema`.`COLUMNS` where `TABLE_SCHEMA`=:schema and `TABLE_NAME`=:table and `COLUMN_NAME`=:column";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(":schema", $this->dbname);
		$stmt->bindParam(":table", $table);
		$stmt->bindParam(":column", $column);
		$stmt->execute();
		
		if ($stmt->rowCount() > 0) {
			Logging::stopTimer($timer, "mysql");
			return true;
		}
		Logging::stopTimer($timer, "mysql");
		return false;
	}

	public function get_tables() {
		$timer = Logging::startTimer(false, "mysql");
		$sql = "select distinct `TABLE_NAME` from `information_schema`.`COLUMNS` where `TABLE_SCHEMA`=:schema";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(":schema", $this->dbname);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
		Logging::stopTimer($timer, "mysql");
		return $results;
	}

	public function get_primary_key($table) {
		$timer = Logging::startTimer(false, "mysql");
		$sql = "select distinct `COLUMN_NAME` from `information_schema`.`COLUMNS` where `TABLE_SCHEMA`=:schema and `TABLE_NAME`=:table and `COLUMN_KEY`='PRI'";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(":schema", $this->dbname);
		$stmt->bindParam(":table", $table);
		$stmt->execute();
		$results = $stmt->fetch(PDO::FETCH_COLUMN, 0);
		Logging::stopTimer($timer, "mysql");
		return $results;
	}

	public function get_related_tables($table, $type, &$linking_tables = array()) {
		$timer = Logging::startTimer(false, "mysql");
		switch ($type) {
			case "outbound":
				//any results will have the foreign tables represented in "column_name" field

				//which of my columns have the same name as another table?
				$sql = "select `COLUMN_NAME` from `information_schema`.`COLUMNS` where `TABLE_SCHEMA`=:schema and `TABLE_NAME`=:table and `COLUMN_NAME` in
							(select `TABLE_NAME` from `information_schema`.`TABLES` where `TABLE_SCHEMA`=:schema and `TABLE_NAME`!=:table)";
				$stmt = $this->db->prepare($sql);
				$stmt->bindParam(":schema", $this->dbname);
				$stmt->bindParam(":table", $table);
				$stmt->execute();

				//TODO replace with return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
				$results = array();
				while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
					$results[] = $row->COLUMN_NAME;
				}
				Logging::stopTimer($timer, "mysql");
				return $results;


				break;

			case "inbound":
				//any results will have the foreign tables represented in "table_name" field

				//which tables have columns with the same name as me?
				$sql = "select `TABLE_NAME` from `information_schema`.`COLUMNS` where `TABLE_SCHEMA`=:schema and `column_name`=:table and `table_name`!=:table and `table_name` not like 'x_%'";
				$stmt = $this->db->prepare($sql);
				$stmt->bindParam(":schema", $this->dbname);
				$stmt->bindParam(":table", $table);
				$stmt->execute();

				$results = array();
				while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
					$results[] = $row->TABLE_NAME;
				}
				Logging::stopTimer($timer, "mysql");
				return $results;
				break;

			case "linking":
				//any results will have the linking table name in "table_name" field, which we must then parse.
				$sql = "select `TABLE_NAME` from `information_schema`.`TABLES` where `TABLE_NAME` like 'x_{$table}_link%' or `TABLE_NAME` like 'x_%_link_{$table}'";
				$stmt = $this->db->prepare($sql);
				$stmt->bindParam(":schema", $this->dbname);
				$stmt->bindParam(":table", $table);
				$stmt->execute();

				$results = array();
				$linking_tables = array();
				while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
					if (preg_match("/^x_{$table}_link_(.*)$/", $row->TABLE_NAME, $matches)) {
						$results[] = $matches[1];
						$linking_tables[] = "x_{$table}_link_" . $matches[1];
					} elseif (preg_match("/^x_(.*)_link_{$table}$/", $row->TABLE_NAME, $matches)) {
						$results[] = $matches[1];
						$linking_tables[] = "x_" . $matches[1] . "_link_{$table}";
					}

				}
				Logging::stopTimer($timer, "mysql");
				return $results;

				break;
		}
	}

	public function getDistinctValues($table, $field) {
		$timer = Logging::startTimer(false, "mysql");
		$sql = "select distinct `$field` from `$table`";
		$this->conn();
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
		Logging::stopTimer($timer, "mysql");
		return $results;
	}

	public function numRows($table) {
		$timer = Logging::startTimer(false, "mysql");
		$sql = "select count(*) from `$table`";
		$this->conn();
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetch(PDO::FETCH_COLUMN, 0);
		Logging::stopTimer($timer, "mysql");
		return $results;
	}

} /*** end of class ***/
