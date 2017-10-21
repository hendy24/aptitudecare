<?php

/*
* Generic model class that maps to a single table with a primary key named 'id', performing no joins,
*
*/

abstract class CMS_Table extends Model {

	public static $table;						// must be set!
	public static $inAdmin = true;				//true, false, or "root"
	public static $enableAdminNew = true;		//true, false, or "root"
	public static $enableAdminEdit = true;		//true, false, or "root"
	public static $enableAdminDelete = true;	//true, false, or "root"
	public static $modelTitle = false;
	public static $enableSearch = false;		//true or false
	public static $searchCols = array();
	public static $searchShadowTable ;
	public static $similarModels = array();		//will display links to these CMS record_index pages
												//at the top of the record_index and form admin pages
												//eg array("category", "news")
	public static $enablePagination = true;
	protected static $adminLockFromEdit = array();	// takes an array of ids
	protected static $adminLockFromDelete = array(); // takes an array of ids
	protected static $adminLockFromEditAndDelete = array();	// shortcut to the previous two
	protected static $adminInstructions = '';
	
	protected static $pk_field = "id";
	protected static $pubid_length = 10;
	protected static $default_orderby;
	protected static $flags = array();
	protected static $flags_checked = false;
	protected static $metaLoaded = false;
	protected static $metadata = array();
	protected static $columns = array();
	protected static $columnsMeta = array();
	protected static $columnsLoaded = false;
	protected static $relatedInbound = array();
	protected static $relatedOutbound = array();
	protected static $relatedLinking = array();
	protected static $enableCreateStructure = false;	// if true, will run static::createStructure() in __construct if necessary

	public $multi_idx = 0;
	protected $record = false;
	protected $adminForm = array();
	protected $actions = array();

	protected static $modelActions = array();

	protected $paginationOn = false;
	protected $paginationCount = null;
	protected $paginationCountPreOverlap = null;
	protected $paginationPerSlice = 25;
	protected $paginationSlice = 1;
	protected $paginationOverlap = 0; 			// if true, offsets will be mangled so that the first record
												// of each slice is actually the last record of the previous slice
												
	protected $paginationMaxLinks = 10;			// used to control how many links to display in pagination
												// navigation, to make eg, "1 2 3 4 5 ... 30 31"

	/**
	 * Constructor
	 * @param	mixed	$id			Takes either a numeric id (auto_increment value) or alphanumeric pubid
	 * @param	Number	$multi_idx	(Not currently in use)
	 * @return	Void				
	 */
	public function __construct($id = false, $multi_idx = 0) {
		//make sure CMS_Table knows about all the tables in the DB
		self::importTableColumns();
		
		//if the derived class calls for it, create table structure
		if (static::$enableCreateStructure == true) {
			static::createStructure();
		}
		
		self::importTableColumns();
		
		if ($id == "___NO_LOAD_EMPTY___") {
			$this->record = new stdClass();
		} else {
			if ($id !== false) {
				$this->load($id);
			} else {
				$this->loadEmpty();
			}
		}
		$this->multi_idx = $multi_idx;

	}
	
	protected static function __importTableColumns() {
		$cols = db()->get_all_columns_with_meta();
		foreach ($cols as $col) {
			if (! isset(self::$columns[$col['TABLE_NAME']]) ) {
				self::$columns[$col['TABLE_NAME']] = array();
				self::$columnsMeta[$col['TABLE_NAME']] = array();
			}
			self::$columns[$col['TABLE_NAME']][] = $col['COLUMN_NAME'];
			self::$columnsMeta[$col['TABLE_NAME']][$col['COLUMN_NAME']] = $col;
			
			// all pubid columns need to be indexed
			if ($col['COLUMN_NAME'] == 'pubid' && $col['COLUMN_KEY'] == '') {
				db()->query("ALTER TABLE `" . $col['TABLE_NAME'] . "` ADD INDEX(`" . $col['COLUMN_NAME'] . "`)", array());
			}
		}
		static::$columnsLoaded = true;
	}
	
	protected static function importTableColumns($force = false) {
		if (static::$columnsLoaded == true && $force == false) {
			return;
		}
		
		$cacheDir = APP_PROTECTED_PATH . "/var/db-cache";
		$cacheFile = $cacheDir . "/" . APP_NAME . ".orm";
		
		// this page request has been requested to write the columns cache
		if (ORM_CACHE_WRITE == true) {
			// populate columns hash
			self::__importTableColumns();
			
			// prepare cache directory if necessary
			if (! file_exists($cacheDir)) {
				$_old = umask(0);
				@mkdir($cacheDir, 0777, true);
				umask($_old);
			}
			
			// write to the cache file
			file_put_contents($cacheFile, serialize(
				array(
					"columns" => self::$columns,
					"columnsMeta" => self::$columnsMeta,
				)
			));
			@chmod($cacheFile, 0777);
		}
		
		// this page request has been requested to utilize cached columns
		if (ORM_CACHE_UTILIZE == true) {
			// if the cache file exists, put its contents into columns hash
			if (file_exists($cacheFile)) {
				$cache = file_get_contents($cacheFile);
				$cache = unserialize($cache);
				self::$columns = $cache["columns"];
				self::$columnsMeta = $cache["columnsMeta"];
				static::$columnsLoaded = true;
			}
			// but if cache file doesn't exist, try to populate it
			else {
				self::__importTableColumns();

				// prepare cache directory if necessary
				if (! file_exists($cacheDir)) {
					$_old = umask(0);
					@mkdir($cacheDir, 0777, true);
					umask($_old);
				}
				
				// write to the cache file
				file_put_contents($cacheFile, serialize(
					array(
						"columns" => self::$columns,
						"columnsMeta" => self::$columnsMeta,
					)
				));
				@chmod($cacheFile, 0777);
			}
		}
		// no cache utilization requested
		else {
			// but we may have already run this query if ORM_CACHE_WRITE was true
			if (self::$columnsLoaded == false) {
				self::__importTableColumns();
			}
		}
	}

	/**
	 * Returns a new, random pubid of length determined by model preferences
	 * @return	String		
	 */
	public static function generatePubid() {
		return random_string(static::$pubid_length);
	}

	/**
	 * Returns true if the pubid value supplied is a valid pubid for this model
	 * @param	String	$pubid	
	 * @return	Boolean
	 */
	public static function is_pubid($pubid) {
		return Validate::is_pubid($pubid, static::$pubid_length)->success();
	}
	
	
	protected static function columnAdminType($table, $column) {
		self::importTableColumns();
		
		// get the list of tables I link to through a linking table
		$related_linking = static::relatedTablesLinking(static::$table);
		
		// if our field is one of those, return related_multi
		if (in_array($column, $related_linking)) {
			return "related_multi";
		}
		
		/* File fields should be named with "file_{XXXX}" */
		if (preg_match("/file_/", $column)) {
			return "file";
		}

		/* Get metadata from information_schema */
		self::importTableColumns();
		$col = (Object) self::$columnsMeta[$table][$column];
		
		if ($col->COLUMN_NAME == "page") {
			return "pages";
		}

		elseif ($col->COLUMN_NAME == "pubid") {
			return "off";
		}

		/* By convention, varchar(128) is file */
		if ($col->COLUMN_TYPE == "varchar(128)") {
			return "file";
		}

		/* Self explanatory; text fields generally contain text values. */
		if ($col->COLUMN_TYPE == "text" || $col->DATA_TYPE == "varchar" || $col->DATA_TYPE == "char") {
			return "text";
		}

		/* Enum options are just 'enum' */
		if ($col->DATA_TYPE == "enum") {
			return "enum";
		}

		/* Decimals and floats are also probably text */
		elseif ($col->DATA_TYPE == "decimal" || $col->DATA_TYPE == "float") {
			return "text";
		}

		elseif (preg_match("/^(int|smallint|mediumint|bigint)/", $col->COLUMN_TYPE)) {
			if ($col->COLUMN_KEY == 'PRI') {
				return "primary_key";
			}

			elseif ($col->COLUMN_NAME == 'priority') {
				return "priority";
			}

			/* An int column could refer to a remote table */
			//elseif (db()->table_exists($col->COLUMN_NAME)) {
			elseif (in_array($col->COLUMN_NAME, array_keys(self::$columns))) {
				return "related_single";
			}
			else {
				return "text";
			}
		}

		elseif ($col->COLUMN_TYPE == "datetime" || $col->COLUMN_TYPE == "timestamp") {
			return "datetime";
		}

		elseif ($col->COLUMN_TYPE == "date") {
			return "date";
		}

		elseif ($col->COLUMN_TYPE == "tinyint(1)") {
			return "boolean";
		}
		
		elseif ($col->COLUMN_TYPE == "mediumtext") {
			return "textarea";
		}

		/* By convention, longtext is HTML textarea */
		elseif ($col->COLUMN_TYPE == "longtext") {
			return "textarea_html";
		}

		/* Give up, text. */
		else {
			return "text";
		}

	}
	
	
	protected static function createStructure() {
		if (! in_array(static::$table, array_keys(self::$columns))) {
			if (file_exists(APP_PROTECTED_PATH . "/var/sql/" . static::$table . ".sql")) {
				$sql = file_get_contents(APP_PROTECTED_PATH . "/var/sql/" . static::$table . ".sql");
			} elseif (file_exists(ENGINE_PROTECTED_PATH . "/var/sql/" . static::$table . ".sql")) {
				$sql = file_get_contents(ENGINE_PROTECTED_PATH . "/var/sql/" . static::$table . ".sql");
			} else {
				throw new ORMException("Tried to run createStructure() but no table definition (sql) file found in either CMS or site var/ directories");
			}
			db()->rawQuery($sql);

			// check again to make sure it worked
			if (db()->table_exists(static::$table)) {
				// force reload of table columns and metadata
				self::importTableColumns(true);
			} else {
				throw new ORMException("Tried to run createStucture but table does not exist even after running SQL. Please check your SQL def file.");
			}
		}
	}

	public function getActions() {
		return $this->actions;
	}

	public static function getModelActions() {
		return static::$modelActions;
	}

	public function executeAction($action, $args = array()) {
		if (is_array($this->actions)) {
			if (isset($this->actions[$action])) {
				call_user_func_array(array($this, $action), $args);
			}
		}
	}

	public static function executeModelAction($action, $args = array()) {
		if (is_array(static::$modelActions)) {
			if (isset(static::$modelActions[$action])) {
				call_user_func_array(array(get_called_class(), $action), $args);
			}
		}

	}

	public function validate($field, $value) {
		//TODO obviously this is not what we want. the goal here is to provide sane, but VERY non-restrictive default validation
		return true;
	}


	public static function isSortable() {
		return in_array("priority", self::$columns[static::$table]);
	}

	public static function adminInstructions() {
		return static::$adminInstructions;
	}

	// "new", "edit", or "delete"
	public static function getAdminCapability($type) {
		switch ($type) {
			case "new":
				if (! isset(static::$enableAdminNew) ) {
					return true;
				} else {
					return static::$enableAdminNew;
				}
				break;
			case "edit":
				if (! isset(static::$enableAdminEdit) ) {
					return true;
				} else {
					return static::$enableAdminEdit;
				}
				break;
			case "delete":
				if (! isset(static::$enableAdminDelete) ) {
					return true;
				} else {
					return static::$enableAdminDelete;
				}
				break;
			default:
				throw new ORMException("Invalid capability requested.");
				break;
		}
	}
	
	public function adminCanEdit() {
		// check row-level locking first
		if (in_array($this->id, static::$adminLockFromEdit) || in_array($this->id, static::$adminLockFromEditAndDelete)) {
			return false;
		}
		// now check table level locking
		if ( ! (static::getAdminCapability("edit") == true || (static::getAdminCapability("edit") == "root" && Authentication_Admin::getInstance()->getRecord()->is_root == 1)))  {
			return false;
		}
		// not locked
		return true;
	}
	
	public function adminCanDelete() {
		// check row-level locking first
		if (in_array($this->id, static::$adminLockFromDelete) || in_array($this->id, static::$adminLockFromEditAndDelete)) {
			return false;
		}
		// now check table level locking
		if ( ! (static::getAdminCapability("delete") == true || (static::getAdminCapability("delete") == "root" && Authentication_Admin::getInstance()->getRecord()->is_root == 1)))  {
			return false;
		}
		// not locked
		return true;		
	}

	public function setPrioritySequence(Array $sequence) {
		$sql = "";
		$pk_field = $this->getPrimaryKeyField();
		foreach ($sequence as $idx => $pk_val) {
			$priority = $idx + 1;
			db()->simple_update($this->getTable(), array("priority" => $priority), $pk_val, $pk_field);
		}
	}

	public function getPrioritySequence() {
		if ($this->isSortable()) {
			//$records = db()->get_column_values(static::$table, "priority");
			$pk_field = $this->getPrimaryKeyField();
			$records = db()->getRows(static::$table, array(), 'std', array($pk_field), "priority");
			$ret = array();
			foreach ($records as $r) {
				$ret[] = $r->{$pk_field};
			}
			return $ret;
		}
	}

	public static function getNextPriority() {
		$record = db()->getRowCustom("SELECT max(`priority`) AS m FROM `" . static::$table . "`");
		return $record->m + 1;
	}

	//returns summary of all available flags for this table, including which records currently hold those flags.
	public static function flags() {
		 $obj = new CMS_Record_Flag;
		 return $obj->fetch(array("table" => static::$table));
	}


	// pulls from cms_record_flag
	public function flaggedAs($name) {
		$obj = new CMS_Record_Flag;
		$records = $obj->fetch(array("name" => $name, "table" => static::$table));
		$_records = array();
		if ($records !== false && count($records) > 0) {
			$record = current($records);
			if ($record->val != '') {
				$pkey_vals = explode(",", $record->val);
				array_walk($pkey_vals, 'arr_trim');
				if (is_array($pkey_vals)) {
					foreach ($pkey_vals as $pk_val) {
						$_records[] = $this->fetchOne($pk_val);
					}
				}
			}
		}
		if (count($_records) == 1) {
			return current($_records);
		} else {
			return $_records;
		}
	}

	public function isFlaggedAs($name) {
		$obj = new CMS_Record_Flag;
		$records = $obj->fetch(array("name" => $name, "table" => static::$table));
		$_records = array();
		if ($records !== false && count($records) > 0) {
			$record = current($records);
			if ($record->val != '') {
				$pkey_vals = explode(",", $record->val);
				array_walk($pkey_vals, 'arr_trim');
				if (is_array($pkey_vals)) {
					foreach ($pkey_vals as $pk_val) {
						$_records[] = $this->fetchOne($pk_val);
					}
				}
			}
		}
		if (count($_records) == 1) {
			$r = current($_records);
			if ($r->id == $this->id) {
				return true;
			} else {
				return false;
			}
		} else {
			foreach ($_records as $r) {
				if ($r->id == $this->id) {
					return true;
				}
			}
			return false;
		}

	}

	public function valid() {
		self::importTableColumns();
		if (is_object($this->record) && get_class($this->record) == "stdClass") {
			$diff = array_diff(self::$columns[$this->getTable()], array_keys(get_object_vars($this->record)));
			if (is_array($diff) && count($diff) == 0 && $this->pk() != '') {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}

	}


	public static function delete($obj) {
		if (! $obj->valid()) {
			return false;
		}

		// delete any associated files first.
		$fileFields = static::getMetaByType("file");
		foreach ($fileFields as $field => $meta) {
			$obj->deleteFile($field);
		}
		$pk = $obj->pk();
		$pk_field = $obj->getPrimaryKeyField();
		if (! db()->delete($obj->getTable(), $pk_field, $pk) ) {
			throw new ORMException("Delete failed.");
		}
		return true;

	}

	public function __get($key) {
		self::importTableColumns();
		if ($key == "pubid") {
			if ($this->record->pubid == '') {
				if (in_array("pubid", self::$columns[static::$table])) {
					$pubid = static::generatePubid();
					$pairs = array(
						"pubid" => $pubid
					);
					db()->simple_update(static::$table, $pairs, $this->pk(), static::$pk_col);
					$this->pubid = $pubid;
					$this->setColumnNotUpdated("pubid");
					$this->setColumnPopulated("pubid");
				}	
			}
		}
		if (is_object($this->record)) {
			if (property_exists($this->record, $key)) {
				return $this->record->{$key};
			} else {
				if (property_exists($this, $key)) {
					return $this->{$key};
				} else {
					return '';
				}
			}
		} else {
			return '';
		}
	}
	
	public function __set($key, $value) {
		self::importTableColumns();
		if (in_array($key, self::$columns[static::$table])) {
			$this->record->{$key} = $value;
			if (! in_array($key, $this->columnsPopulated)) {
				// column had not yet been populated. record that we've done so.
				$this->columnsPopulated[] = $key;
			} else {
				// column was already populated. this is an update.
				if (! in_array($key, $this->columnsUpdated)) {
					$this->columnsUpdated[] = $key;
				}
			}
		} else {
			$this->{$key} = $value;
		}
	}

	public static function getCMSTables() {
		self::importTableColumns();
		$tables = array_keys(self::$columns);
		$_tables = array();
		foreach ($tables as $t) {
			if (! preg_match("/^x?_/", $t)) {		//nothing that starts with an underscore or an x_
				$_tables[] = $t;
			}
		}
		return $_tables;
	}

	public function pk() {
		$pk_field = static::$pk_col;
		return $this->{$pk_field};
	}

	public function getPrimaryKeyField($table = false) {
		return static::$pk_col;
	}

	public function getRecord() {
		return $this->record;
	}

	public function setRecord($obj) {
		if (! is_object($obj))
			return false;
		if (get_class($obj) != 'stdClass')
			return false;
		$this->record = $obj;
	}

	public function getTable() {
		//return static::$table;
		return static::$table;
	}

	public static function fetchOne($id) {
		$obj = self::generate();
		$obj->load($id);
		return $obj;
	}

	public function fetchForAdmin($params = array()) {
		return $this->fetch($params);
	}

	// $params can be an array, which will generate "WHERE foo='bar' and 'bork'='baz'...."
	// Alternatively, you may pass a WHERE clause as $params
	public function fetch($params = array(), $orderby = false, $count = false, $offset = false) {
		if ($orderby == false) {
			if (static::isSortable() && static::$default_orderby == '') {
				$orderby = "priority";
			} elseif (static::$default_orderby != '') {
				$orderby = static::$default_orderby;
			}
		}
		
		//If pagination mode is on, we need to know how many rows are in the full set.
		if ($this->paginationOn) {
			// the number of records to actually return
			$count = $this->paginationPerSlice;
			
			// default starting point
			$offset = ($this->paginationSlice - 1) * $this->paginationPerSlice;
			
			// in 'overlap' mode, start one record sooner than we ordinarily would
			if ($this->paginationOverlap) {
				if ($this->paginationSlice > 1) {
					$offset = $offset - ($this->paginationSlice -1);
				}
			}
			
			// run the fetch, returning in the process the total number of records the search
			// would have returned if we didn't use a limit
			$records = db()->getRows(static::$table, $params, get_class($this), false, $orderby, $count, $offset, true, $paginationCount);
			$this->paginationCount = $paginationCount;				
			
			// in overlap mode, make a backup of the actual "found rows" value, then adjust the value
			// to account for all the extra records we're adding with this stepstool approach
			if ($this->paginationOverlap) {
				$this->paginationCountPreOverlap = $this->paginationCount;
				$this->paginationCount = $this->paginationCountPreOverlap + $this->paginationNumSlices();
			} else {
				$this->paginationCount = $paginationCount;				
			}
			
		} else {
			try {
				$records = db()->getRows(static::$table, $params, get_class($this), false, $orderby, $count, $offset);
			} catch (PDOException $e) {
				handle_pdo_exception($e, get_called_class());
			}
		}
		return $records;
	}

	public function fetchCustom($sql, $params = array()) {
		//If pagination mode is on, we need to know how many rows are in the full set.
		if ($this->paginationOn) {
			// the number of records to actually return
			$count = $this->paginationPerSlice;

			// default starting point
			$offset = ($this->paginationSlice - 1) * $this->paginationPerSlice;
			
			// in 'overlap' mode, start one record sooner than we ordinarily would
			if ($this->paginationOverlap) {
				if ($this->paginationSlice > 1) {
					$offset = $offset - ($this->paginationSlice -1);
				}
			}
			
			try {
				$records = db()->getRowsCustom($sql, $params, get_class($this), $count, $offset, true, $paginationCount);
			} catch (PDOException $e) {
				handle_pdo_exception($e, get_called_class());
			}
			// in overlap mode, make a backup of the actual "found rows" value, then adjust the value
			// to account for all the extra records we're adding with this stepstool approach
			if ($this->paginationOverlap) {
				$this->paginationCountPreOverlap = $this->paginationCount;
				$this->paginationCount = $this->paginationCountPreOverlap + $this->paginationNumSlices();
			} else {
				$this->paginationCount = $paginationCount;				
			}
		} else {
			try {
				$records = db()->getRowsCustom($sql, $params, get_class($this));
			} catch (PDOException $e) {
				handle_pdo_exception($e, get_called_class());
			}
		}
		return $records;

	}

	// @map = array(
	//			"some query string" => array("column1", "column2"),
	//			"another query string" => array("column1", "column3")
	// )
	// if $cols is false, defaults to all static::$searchCols
	public function search($map, $type = "OR") {
		// Validate: You can't search on all blanks.
		$valid = false;
		foreach ($map as $query => $cols) {
			if ($cols == false) {
				$map[$query] = static::$searchCols;
			}
			if ($query != '') {
				$valid = true;
				break;
			}
		}
		if ($valid == false) {
			return array();
		}

		$sql = "select *";

		$colStrings = array();
		$whereStrings = array();
		$scoreCols = array();
		$scoreCol = 0;
		$params = array();
		foreach ($map as $query => $cols) {
			if ($query != '') {
				// MySQL ISAM fulltext search indices do not support queries
				// shorter than 3 characters, so we need to work around that.
				//if (strlen($query) < 4 && strlen($query) > 2) {
				if (strlen($query) == 1) {
					return array();
				} elseif (strlen($query) == 3 || strpos($query, "-") !== false) {
					foreach ($cols as $col) {
						$whereStrings[] = "`{$col}` LIKE :{$col}";
						$params[":{$col}"] = "%{$query}%";
					}
				} elseif (strlen($query) == 2) {
					foreach ($cols as $col) {
						$whereStrings[] = "`{$col}`=:{$col}";
						$params[":{$col}"] = $query;
					}
				} else {
					$colList = "`" . implode("`,`", $cols) . "`";
					$scoreCols[] = "_score$scoreCol";
					$colStrings[] = "MATCH($colList) AGAINST (:query_{$scoreCol} IN BOOLEAN MODE) as _score{$scoreCol}";
					$whereStrings[] = "MATCH($colList) AGAINST (:query_{$scoreCol} IN BOOLEAN MODE)";
					$params[":query_{$scoreCol}"] = $query;
				}
			}
			$scoreCol++;
		}

		if (count($colStrings) > 0) {
			$sql .= "," . implode(", ", $colStrings);
		}
		$sql .= " FROM `" . static::searchShadowTable() . "`";
		$sql .= " WHERE " . implode(" {$type} ", $whereStrings) ;
		if (count($scoreCols) > 0) {
			$sql .= " ORDER BY (" . implode(" + ", $scoreCols) . ")";
		}
		//If pagination mode is on, we need to know how many rows are in the full set.
		if ($this->paginationOn) {
			// the number of records to actually return
			$count = $this->paginationPerSlice;

			// default starting point
			$offset = ($this->paginationSlice - 1) * $this->paginationPerSlice;
			
			// in 'overlap' mode, start one record sooner than we ordinarily would
			if ($this->paginationOverlap) {
				if ($this->paginationSlice > 1) {
					$offset = $offset - ($this->paginationSlice -1);
				}
			}
			
			try {
				$records = db()->getRowsCustom($sql, $params, get_class($this), $count, $offset, true, $paginationCount);
			} catch (PDOException $e) {
				handle_pdo_exception($e, get_called_class());
			}
			// in overlap mode, make a backup of the actual "found rows" value, then adjust the value
			// to account for all the extra records we're adding with this stepstool approach
			if ($this->paginationOverlap) {
				$this->paginationCountPreOverlap = $this->paginationCount;
				$this->paginationCount = $this->paginationCountPreOverlap + $this->paginationNumSlices();
			} else {
				$this->paginationCount = $paginationCount;				
			}
		} else {
			try {
				$records = db()->getRowsCustom($sql, $params, get_class($this));
			} catch (PDOException $e) {
				handle_pdo_exception($e, get_called_class());
			}
		}

		return $records;
	}
	
	public function getScore() {
		if (static::$enableSearch == true) {
			return $this->_score;
		}
		return NULL;
	}

	public function getDistinctValues($fieldname) {
		return db()->getDistinctValues(static::$table, $fieldname);
	}

	public static function translateId($id, $table = false) {
		if ($table == false) {
			$table = static::$table;
			$cls = get_called_class();
		} else {
			$cls = Model::clsname($table);
		}
		
		// foreign object should load regardless of ID format
		$obj = $cls::generate();
		$obj->load($id);

		// if it did not load, throw exception; there is a problem with the id you are trying to translate.
		if (! $obj->valid()) {
			throw new ORMException("Record not found (table={$table}, id={$id}.");
		}

		// if you made it this far, you should have a numeric id or pubid on your hands.
		
		// is the id numeric?
		if (Validate::is_natural($id)->success()) {
			// make sure there's actually a pubid column to translate to
			if (! array_key_exists('pubid', $record) ) {
				// nothing to translate to! bail out.
				throw new ORMException("Cannot translate ID from primary key val to public; this table does not track public keys.");
			}
			// otherwise return the pubid
			return $obj->pubid;
		}
		// or is the id a pubid?
		elseif ($cls::is_pubid($id)) {
			// return the pk value
			return $obj->pk();
		}
		// anything else is definitely an exit point for the script.
		else {
			throw new ORMException("Supplied value ({$id}) is not a valid primary key or public ID in " . get_called_class() . ".");
		}
	}

	/* Manually populate this instance with a certain row in the table, by id or pubid */
	public function load($pk_val) {
		self::importTableColumns();
		if (Validate::is_natural($pk_val, false)->success()) {
			parent::load($pk_val);
		} elseif (static::is_pubid($pk_val) && in_array('pubid', self::$columns[static::$table])) {
			$this->record = db()->getRow(static::$table, array("pubid" => $pk_val));
			$this->clearColumnsUpdated();
			$this->setAllColumnsPopulated();
		} else {
			//throw new Exception("Failed to load record from table `{static::$table}` with id '{$pk_val}'.");
			// This ensures that valid() will return false.
			$this->loadEmpty();
			return;
		}

		if (in_array('pubid', self::$columns[static::$table]) && $this->pubid == '') {
			$pubid = static::generatePubid();
			$pairs = array(
				"pubid" => $pubid
			);
			db()->simple_update(static::$table, $pairs, $this->pk(), static::$pk_col);
			$this->pubid = $pubid;
			$this->clearColumnsUpdated();
			$this->setAllColumnsPopulated();
		}

		$this->initActions();
	}

	public function initActions() {
		return true;
	}

	public function truncate() {
		db()->truncate(static::$table);
	}

	public function loadEmpty() {
		self::importTableColumns();
		$columns = self::$columns[static::$table];
		$map = array();
		if (! is_array($columns) ) {
			$this->record = new stdClass;
		} else {
			foreach ($columns as $c) {
				$map[$c] = '';
			}
			$this->record = (Object) $map;
		}
		$this->clearColumnsUpdated();
		$this->setAllColumnsPopulated();
	}

	public function save($validate = false) {
		// Make a copy of the old record so we can make comparisons later on.
		$cls = get_class($this);
		$existing = new $cls;
		if ($this->pk() != '') {
			// this is an existing record
			$existing->load($this->pk());
		}
		
		if (in_array('pubid', self::$columns[static::$table]) && $this->pubid == '') {
			$this->pubid = static::generatePubid();
		}
		//
		//Foreign tables that are referenced by a column in my table.
		//Make sure all these fields contain simple primary key integers.
		//
		$related_outbound = db()->get_related_tables(static::$table, "outbound");
		foreach ($related_outbound as $t) {
			$foreignCls = Model::clsname($t);
			if ($this->{$t} != '') {
				if (is_object($this->{$t})) {
					if (Validate::is_natural($this->{$t}->pk())->success()) {
						$this->{$t} = $this->{$t}->pk();
					} else {
						throw new ORMException("Tried to save an invalid value in field `{$t}`.");
					}
				} elseif ($foreignCls::is_pubid($this->{$t})) {
					$this->{$t} = self::translateId($this->{$t}, $t);
				}
			}
		}

		// master array that will hold all foreign values we're about to derive
		$_foreign = array();

		//Foreign tables that have a column referencing my table.
		$related_inbound = db()->get_related_tables(static::$table, "inbound");
		
		// Cycle through table names we found that have a column referencing my table.
		foreach ($related_inbound as $foreign) {
			// get the model class name of that table
			$foreignCls = Model::clsname($foreign);
			
			// make sure the value is legit: must contain an array, as this is one-to-many
			if ($this->{$foreign} != '' && ! is_array($this->{$foreign})) {
				throw new ORMException("Expected field `{$foreign}` to contain an array, instead contains " . $this->{$foreign});
			} else {
				$_foreign[$foreign] = array();
				if (is_array($this->{$foreign})) {
					// cycle through the array that the user assigned
					foreach ($this->{$foreign} as $i => $entry) {
						if ($entry != '') {
							// this entry in the array is an object.
							if (is_object($entry)) {
								// make sure it's an object that has a valid primary key
								if (Validate::is_natural($entry->pk())->success()) {
									$_foreign[$foreign][] = $entry->pk();
								} else {
									throw new ORMException("At least one entry in foreign associations `{$foreign}` was invalid.");
								}
							}
							// this entry in the array is a pubid
							elseif ($foreignCls::is_pubid($entry)) {
								// turn the pubid into a natural id
								$_foreign[$foreign][] = $this->translateId($entry, $foreign);
							}
							// this entry had better be a natural id...
							// todo(bcohen) check that here, throw otherwise.
							else {
								$_foreign[$foreign][] = $entry;
							}
						}
					}
				}
			}
		}

		/* Foreign tables that I share a linking table with */
		$related_linking = db()->get_related_tables(static::$table, "linking", $linking_tables);
		foreach ($related_linking as $foreign) {
			$foreignCls = Model::clsname($foreign);
			if ($this->{$foreign} == '') {
				continue;
			}
			if (! is_array($this->{$foreign})) {
				throw new ORMException("Expected field `{$foreign}` to contain an array, instead contains " . $this->{$foreign});
			} else {
				if (is_array($this->{$foreign})) {
					$_foreign[$foreign] = array();
					foreach ($this->{$foreign} as $i => $entry) {
						if ($entry != '') {
							if (is_object($entry)) {
								if (Validate::is_natural($entry->pk())->success()) {
									$_foreign[$foreign][] = $entry->pk();
								} else {
									throw new ORMException("At least one entry in foreign associations `{$foreign}` was invalid.");
								}
							} elseif ($foreignCls::is_pubid($entry)) {
								$_foreign[$foreign][] = $this->translateId($entry, $foreign);
							} else {
								$_foreign[$foreign][] = $entry;
							}
						}
					}
				}
			}
		}


		// priority
		if (static::isSortable()) {
			if ($this->priority == '') {
				$this->priority = static::getNextPriority();
			}
		}		
		
		// pre-save callbacks
		static::loadAllMeta();
		$vars = get_object_vars($this->record);
		foreach ($vars as $k => $v) {
			if (! $this->columnIsUpdated($k)) {
				continue;
			}
			$meta = $this->getFieldMeta($k);
			if (! is_array($meta) ) {
				$meta = array();
			}
			if (array_key_exists("callback_beforeSave", $meta)) {
				if ($meta["callback_beforeSave"] != '') {
					if ($v == '') { 
						continue;
					}
					$func = $meta["callback_beforeSave"];
					if (strpos($func, "::") !== false) {
						list($cls, $method) = explode("::", $func);
						$this->{$k} = call_user_func_array(array($cls, $method), array($v, &$this));
					} else {
						$this->{$k} = call_user_func_array($func, array($v, &$this));
					}
				}
			}
		}

		foreach ($vars as $k => $v) {
			if (!$this->columnIsUpdated($k) && $k != static::$pk_field) {
				unset($this->record->{$k});
			}
		}
		parent::save();

		// post-save callback -- should be a static method, take post-save obj ($this), and pre-save state ($existing) as params
		if (method_exists(get_called_class(), 'callback_afterSave')) {
			call_user_func_array(array(get_called_class(), 'callback_afterSave'), array(&$this, &$existing));
		}
		
		foreach ($related_inbound as $t) {
			if (is_array($_foreign[$t]) && count($_foreign[$t]) > 0) {
				foreach ($_foreign[$t] as $_id) {
					if (!db()->update($t, static::$table, $this->pk(), $_id)) {
						throw new ORMException();
					}
				}
			}
		}
		foreach ($related_linking as $i => $t) {
			$linking_table = $linking_tables[$i];

			//now make new entries
			if (is_array($_foreign[$t])) {
				//for this linking table, remove everything that refers to me.
				db()->delete($linking_table, static::$table, $this->pk(), static::$pk_col);
				foreach ($_foreign[$t] as $_id) {
					$_pairs = array(
						static::$table => $this->pk(),
						$t => $_id
					);
						
					db()->simple_insert($linking_table, $_pairs);
				}
			}
		}
	}

	public function deleteFile($field) {
		static::loadAllMeta();

		if (static::getFieldWidgetType($field) != "file") {
			return false;
		}

		$widget = static::getFieldWidget($field);
		$path = $widget->getAssetPath() . "/" . $this->{$field};

		if (file_exists($path) && ! is_dir($path)) {
			@unlink($path);
		}
	}

	public function addFile($field, $nameInForm = false, $autoSave = true) {
		$existing = clone $this->getRecord();
		static::loadAllMeta();

		if (static::getFieldWidgetType($field) != "file") {
			return false;
		}

		if ($nameInForm == false) {
			$nameInForm = $field;
		}
		if ($_FILES[$nameInForm]['name'] != '') {
			if (is_uploaded_file($_FILES[$nameInForm]['tmp_name'])) {
				$widget = static::getFieldWidget($field);
				$meta = static::getFieldMeta($field);
				$mime_type = getMimeType($_FILES[$nameInForm]['tmp_name']);
				if (in_array($mime_type, $meta["options"]["mime_types"])) {
					$filename = random_string(10) . "." . get_filename_ext($_FILES[$nameInForm]['name'], true);
					$_old = umask(0);
					@mkdir($widget->getAssetPath(), 0777, true);
					umask($_old);
					if (move_uploaded_file($_FILES[$nameInForm]['tmp_name'], $widget->getAssetPath() . "/" . $filename)) {
						@chmod($widget->getAssetPath() . "/" . $filename, 0777);
						$this->{$field} = $filename;
						if ($autoSave) {
							$this->save();
						}
						if ($this->pk() != '' && $existing->{$field} != '') {
							if (file_exists($widget->getAssetPath() . "/" . $existing->{$field})) {
								@unlink($widget->getAssetPath() . "/" . $existing->{$field});
							}
						}
						return $filename;
					}
				} else {
					@unlink($_FILES[$nameInForm]['tmp_name']);
					throw new ORMException("That file type is not permitted to be uploaded.");
				}
			} else {
				return false;
			}
		}
	}

	public function removeFile($field) {
		//$fieldmeta = $this->meta()->get_meta($field);
		$fieldmeta = $this->getFieldMeta($field);
		if ($fieldmeta["widget"] == "file") {
			//$widget = $this->meta()->getFieldWidget($field);
			$widget = $this->getFieldWidget($field);
			$filename = $this->{$field};
			if (file_exists($widget->getAssetPath() . "/" . $filename)) {
				if (@ unlink($widget->getAssetPath() . "/" . $filename)) {
					$this->{$field} = '';
					$this->save();
				}
			}
		}
	}


	public static function relatedTablesOutbound($table) {
		if (! isset(self::$relatedOutbound[$table])) {
			db()->get_related_tables($table, "outbound", $linking_tables);
			self::$relatedOutbound[$table] = $linking_tables;
		}
		return self::$relatedOutbound[$table];
	}

	public static function relatedTablesInbound($table) {
		if (! isset(self::$relatedInbound[$table])) {
			db()->get_related_tables($table, "inbound", $linking_tables);
			self::$relatedInbound = $linking_tables;
		}
		return self::$relatedInbound[$table];
	}

	public static function relatedTablesLinking($table) {
		if (! isset(self::$relatedLinking[$table])) {
			db()->get_related_tables($table, "linking", $linking_tables);
			$_tables = array();
			foreach ($linking_tables as $t) {
				if (preg_match("/(^x_" . $table. "_link_(.*))$/", $t, $matches)) {
					$_tables[$matches[1]] = $matches[2];
				} elseif (preg_match("/(^x_(.*)_link_" . $table . ")$/", $t, $matches)) {
					$_tables[$matches[1]] = $matches[2];
				}
			}
			self::$relatedLinking[$table] = $_tables;
		}
		return self::$relatedLinking[$table];
	}

	/* This is a "do what I mean" function. It tries to do the "right thing" by trial and error. */
	public function related($table, $only_mine = true, $only_fields = false, $orderby = false, $count = false, $offset = false) {
		self::importTableColumns();
		
		/*  Does the calling table contain a column named after the related table?
		*	("I am a product. Return the category I belong to.")
		*/
		if (in_array($table, self::$columns[static::$table])) {		
			if ($only_mine) {
				/* If so, get the single foreign record. */
				$row = db()->getRow($table, array(static::$pk_col => $this->{$table}), Model::clsname($table), $only_fields);
				if ($row == false) {
					$cls = Model::clsname($table);
					return new $cls;
				} else {
					return $row;
				}
			} else {
				return db()->getRows($table, array(), Model::clsname($table), $only_fields, $orderby, $count, $offset);
			}
		}

		/* Does the related table contain a column named after the calling table?
		*  ("I am a category. Return all the products that belong to me.")
		*/
		if (in_array(static::$table, self::$columns[$table])) {
			if ($only_mine) {
				/* If so, get all records from the foreign table that refer to me. */
				$rows = db()->getRows($table, array(static::$table => $this->pk()), Model::clsname($table), $only_fields, $orderby, $count, $offset);
				if ($rows == false) {
					return array();
				} else {
					return $rows;
				}
			} else {
				return db()->getRows($table, array(), Model::clsname($table), $only_fields, $orderby, $count, $offset);
			}
		}

		/* Then try N-to-N linking */
		/* Get the tables we link to via multi-linkers, along with a list of those multi-linkers */
		//$multi_linked = db()->get_related_tables(static::$table, "linking", $linking_tables);
		$multi_linked = static::relatedTablesLinking(static::$table);
		$linking_table = array_search($table, $multi_linked);
		
		if ($_key !== false) {
			if ($only_mine) {
				$sql = "select a.* from `{$table}` a, `{$linking_table}` b
				where b.`{$this->getTable()}`=:id and a.`id`=b.`{$table}`";
				if ($orderby != false) {
					$sql .= " order by {$orderby}";
				}
				$params = array(
					":id" => $this->pk()
				);
			} else {
				$sql = "select * from {$table}";
				if ($orderby != false) {
					$sql .= " order by {$orderby}";
				}
				$params = array();
			}
			return db()->getRowsCustom($sql, $params, Model::clsname($table), $count, $offset);
		}

	}

	public static function areRelated($obj1, $obj2) {
		self::importTableColumns();
		
		$table1 = $obj1->getTable();
		$table2 = $obj2->getTable();

		/*
		 * Example:  table1 = "product", table2 = "category"
		 *
		 */
		/*  Does table1 contain a column named after table2?
		 */

		if (in_array($table2, self::$columns[$table1])) {
			if ($obj1->{$table2} == $obj2->pk()) {
				return true;
			}
			return false;
		}

		/* Does table2 contain a column named after table1?
		*/
		if (in_array($table1, self::$columns[$table2])) {
			if ($obj2->{$table1} == $obj1->pk()) {
				return true;
			}
			return false;
		}

		/* Then try N-to-N linking */
		/* Get the tables we link to via multi-linkers, along with a list of those multi-linkers */
		$multi_linked = db()->get_related_tables($table1, "linking", $linking_tables);
		$_key = array_search($table1, $multi_linked);
		if ($_key == false) {
			$_key = array_search($table2, $multi_linked);
		}
		if ($_key !== false) {
			$linking_table = $linking_tables[$_key];
			$sql = "select * from `{$linking_table}` b
			where b.`{$table1}`=:id1 and b.`{$table2}`=:id2";
			$params = array(
				":id1" => $obj1->pk(),
				":id2" => $obj2->pk()
			);
			$records = db()->getRowsCustom($sql, $params);
			return (count($records) != 0);
		}

	}

	/* Metadata related methods */

	protected static function get_default_meta($field) {
		$vals = array(
			//default label: convert underlines to spaces and capitalize all resulting words
			"field" => $field,
			"label" => guesslabel($field),
			"widget" => self::columnAdminType(static::$table, $field),
			"options" => array(

			)
		);
		
		if ($vals["widget"] == "related_single" || $vals["widget"] == "related_multi") {
			if ($vals["options"]["table"] != '') {
				$foreignModel = Model::clsname($vals["options"]["table"]);
			} else {
				$foreignModel = Model::clsname($field);
			}
			$vals["label"] = $foreignModel::getModelTitle();
		}
			
		if ($vals["widget"] == "file") {
			$vals["options"]["mime_types"] = array(
				"image/jpg",
				"image/jpeg",
				"image/png",
				"image/tiff",
				"image/gif",
				"image/vnd.adobe.photoshop",
				"image/x-ms-bmp",
				"application/postscript",
				"application/msword",
				"application/pdf",
				"application/vnd.ms-excel",
				"application/vnd.ms-powerpoint",
				"application/vnd.oasis.opendocument.text",
				"application/vnd.oasis.opendocument.spreadsheet",
				"application/vnd.ms-office",
				"application/zip",
				"audio/wav",
				"audio/ogg",
				"audio/mpeg",
				"video/quicktime",
				"video/mp4",
				"video/x-flv",
				"video/H264",
				"text/rtf",
				"text/plain",
				"text/html",
				"text/css"			
			);
		}
		return $vals;

	}
	

	public static function generate() {
		$obj = parent::generate();
		$obj->loadEmpty();
		return $obj;
	}

	public static function loadAllMeta() {
		if (static::$metaLoaded == false) {
			$obj = self::generate();
			$fields = array_keys(get_object_vars($obj->getRecord()));
			$fields = array_merge($fields, static::relatedTablesLinking(static::$table));
			foreach ($fields as $f) {
				$default_meta = static::get_default_meta($f);
				if (! isset(static::$metadata[$f]) ) {
					static::$metadata[$f] = $default_meta;
				} else {
					foreach ($default_meta as $key => $val) {
						if (! isset(static::$metadata[$f][$key])) {
							static::$metadata[$f][$key] = $val;
						}
					}
					if (isset(static::$metadata[$f]["options"]) && is_array(static::$metadata[$f]["options"])) {
						foreach ($default_meta["options"] as $key => $val) {
							if (! isset(static::$metadata[$f]["options"][$key]) ) {
								static::$metadata[$f]["options"][$key] = $val;
							}
						}
					}
				}
			}
			static::$metaLoaded = true;
		}
		return static::$metadata;
	}

	public static function getFieldMeta($field) {
		if (static::$metaLoaded == false) {
			static::loadAllMeta();
		}
		return static::$metadata[$field];
	}

	public static function getMetaByType($type) {
		static::loadAllMeta();
		$retval = array();
		foreach (static::$metadata as $field => $field_meta) {
			if ($field_meta["widget"] == $type) {
				$retval[$field] = $field_meta;
			}
		}
		return $retval;
	}

	// the title of this record. overload this function in extended classes.
	public function getTitle() {
		if (static::$metaLoaded == false) {
			static::loadAllMeta();
		}
		$fields = array_merge(static::getMetaByType("text"), static::getMetaByType("pages"));
		$values = array();
		foreach ($fields as $field => $fieldmeta) {
			$values[] = $this->getRecord()->{$field};
		}
		return implode(" - ", $values);
	}


	public static function getFieldWidgetType($field) {
		if (static::$metaLoaded == false) {
			static::loadAllMeta();
		}
		return static::$metadata[$field]["widget"];
	}

	public function getFieldWidget($field, $css_class = "") {
		if (static::$metaLoaded == false) {
			static::loadAllMeta();
		}
		$meta = static::getFieldMeta($field);
		$_widget_cls = clsname("Widget_" . $meta["widget"]);
		$widget = new $_widget_cls($this);
		$widget->fieldname = $field;
		$widget->class = $css_class;
		$widget->value = $this->{$field};
		$widget->multi_idx = $this->multi_idx;
		
		$auth = Authentication_Admin::getInstance();

		$options = $meta["options"];

		//enforce readOnly if editing disabled or permission denied to signed-in user
		if (! $this->adminCanEdit()) {
			$options["readOnly"] = true;
		}

		//override readOnly if you are root.
		//elseif ($auth->is_root == 1) {
		//	$options["readOnly"] = false;
		//}
		$widget->options = $options;
		return $widget;
	}

	public function buildForm($css_class = false) {
		if (static::$metaLoaded == false) {
			static::loadAllMeta();
		}
		if (count($this->adminForm) == 0) {
			foreach (static::$metadata as $field => $field_meta) {
				$widget = $this->getFieldWidget($field, $css_class);
				$this->adminForm[$field] = array(
					"render_widget" => $widget->render(),
					"jquery_ready" => $widget->jqueryReady(),
					"jquery" => $widget->jquery()
				);
			}
		}
	}

	public function renderFieldWidget($field) {
		if (static::$metaLoaded == false) {
			static::loadAllMeta();
		}
		$this->buildForm();
		echo $this->adminForm[$field]["render_widget"];
	}

	public function renderJqueryReady() {
		if (static::$metaLoaded == false) {
			static::loadAllMeta();
		}
		$this->buildForm();
		foreach ($this->adminForm as $field => $field_form) {
			if ($field_form["jquery_ready"] != '') {
				echo $field_form["jquery_ready"] . "\n";
			}
		}
	}

	public function renderJquery() {
		if (static::$metaLoaded == false) {
			static::loadAllMeta();
		}
		$this->buildForm();
		foreach ($this->adminForm as $field => $field_form) {
			if ($field_form["jquery"] != '')
				echo $field_form["jquery"] . "\n";
		}
	}

	public function getLabel($field) {
		if (static::$metaLoaded == false) {
			static::loadAllMeta();
		}
		$_meta = static::getFieldMeta($field);
		return $_meta["label"];
	}

	public static function getModelTitle() {
		//if (static::$metaLoaded == false) {
		//	static::loadAllMeta();
		//}
		if (static::$modelTitle == false) {
			return guesslabel(static::$table);
		} else {
			return static::$modelTitle;
		}

	}

	/* Pagination methods */
	public function paginationOn() {
		$this->paginationOn = true;
		
		// set to 0 so no exceptions are thrown.
		$this->paginationCount = 0;
	}

	public function paginationOff() {
		$this->paginationOn = false;
	}

	public function paginationGetCount() {
		return $this->paginationCount;
	}
	
	public function paginationSetMaxLinks($max) {
		$this->paginationMaxLinks = $max;
	}
	
	public function paginationSetSliceSize($size) {
		$this->paginationPerSlice = $size;
	}

	public function paginationSetSlice($index) {
		if (! Validate::is_natural($index)->success()) {
			$index = 1;
		}
		$this->paginationSlice = $index;
	}

	public function paginationGetSlice() {
		return $this->paginationSlice;
	}

	public function paginationNextSlice() {
		return $this->paginationSlice + 1;
	}

	public function paginationPrevSlice() {
		if ($this->paginationSlice > 1)
			return $this->paginationSlice - 1;
		else
			return 1;
	}

	public function paginationLastSlice() {
		if (is_null($this->paginationCount)) {
			throw new ORMException("Unable to determine last slice; fetch hasn't run yet, or has been run with pagination turned off.");
		}
		$val = ceil($this->paginationCount / $this->paginationPerSlice);
		
		// zero does not cut it as a "last slice" value. default to one.
		if ($val == 0) {
			return 1;
		}
		return $val;
	}
	
	public function paginationIsFirstSlice() {
		return ($this->paginationSlice == 1);
	}
	
	public function paginationIsLastSlice() {
		return ($this->paginationSlice == $this->paginationLastSlice());
	}

	// since we are 1-indexed, the number of slices is the name as the index of the last slice.
	public function paginationNumSlices() {
		return $this->paginationLastSlice();
	}

	// the assumption here is that the current URL will just be mangled on the 'slice' parameter
	public function paginationGetURL($slice, $key = "slice") {
		return setURLVar(maskedCurrentURL(), $key, $slice);
	}

	public function paginationGetSliceLinks($maxBefore = 3, $maxAfter = 3) {
		// return empty array if there were no results
		if ($this->paginationGetCount() == 0) {
			return array();
		}
		// derive "before" and "after" distances:
		$distanceBefore = floor(($this->paginationMaxLinks - 6) / 2);
		$distanceAfter = ceil(($this->paginationMaxLinks - 6) / 2) - 1;
		$slices = array();

		//always include the first three slices, assuming we have that many.
		$slices[] = 1;
		if ($maxBefore >= 2) {
			if ($this->paginationNumSlices() > 1) {
				$slices[] = 2;
			}
		}
		if ($maxBefore >= 3) {
			if ($this->paginationNumSlices() > 2) {
				$slices[] = 3;
			}
		}

		//always include our current slice
		$slices[] = $this->paginationGetSlice();

		//always include a certain number of slices before our current slice, assuming they are non-negative
		for ($i=1; $i<$distanceBefore + 1; $i++) {
			if ($this->paginationGetSlice() - $i > 0) {
				$slices[] = $this->paginationGetSlice() - $i;
			}
		}
		//always include a certain number of slices after our current slice, assuming they are non-negative
		for ($i=1; $i<$distanceAfter + 1; $i++) {
			if ($this->paginationGetSlice() + $i <= $this->paginationNumSlices()) {
				$slices[] = $this->paginationGetSlice() + $i;
			}
		}

		//always include the last three slices
		$slices[] = $this->paginationNumSlices();		// the last slice
		if ($maxAfter >= 2) {
			if ($this->paginationNumSlices() - 1 > 0) {
				$slices[] = $this->paginationNumSlices() - 1;
			}
		}
		if ($maxAfter >= 3) {
			if ($this->paginationNumSlices() - 2 > 0) {
				$slices[] = $this->paginationNumSlices() - 2;
			}
		}

		//remove duplicates and sort
		sort($slices, SORT_NUMERIC);
		$slices = array_values(array_unique($slices));


		//break into chunks so that our rendering code can easily insert ellipseses:
		$chunks = array(array());
		$chunk = 0;
		$last_slice = 0;
		foreach ($slices as $i) {
			if ($i != $last_slice + 1) {
				$chunk++;
			}
			if (! isset($chunks[$chunk])) {
				$chunks[$chunk] = array();
			}
			$chunks[$chunk][] = $i;
			$last_slice = $i;
		}
		return $chunks;
	}
	
	public function paginationSetOverlap($bool) {
		$this->paginationOverlap = (int) $bool;
	}

	public static function createSearchShadowTable() {
		// what search shadow table? doesn't seem to exist.
		if (! isset(self::$columns[static::searchShadowTable()])) {
				
			// get the name of the proposed search shadow table
			$searchShadowTable = static::searchShadowTable();
			$table = static::$table;

			// drop if already exists
			db()->rawQuery("DROP TABLE IF EXISTS `{$searchShadowTable}`");
			
			// recreate it
			db()->rawQuery("CREATE TABLE `{$searchShadowTable}` like `{$table}`");
			db()->rawQuery("ALTER TABLE `{$searchShadowTable}` ENGINE=MyISAM");
			$pk_field = static::$pk_field;

			$sql = "";

			// drop the triggers in case they existed. we want to start clean.
			$sql .= "DROP TRIGGER IF EXISTS `{$searchShadowTable}_insert`;";
			$sql .= "DROP TRIGGER IF EXISTS `{$searchShadowTable}_update`;";
			$sql .= "DROP TRIGGER IF EXISTS `{$searchShadowTable}_delete`;";

			db()->rawQueryMulti($sql);
			

			// create one fulltext index per column
			$sql = "";
			foreach (static::$searchCols as $col) {
				$sql .= "ALTER TABLE `{$searchShadowTable}` ADD FULLTEXT INDEX (`{$col}`);\n";
			}

			// now create one for ALL columns
			$colStr = '`' . implode('`,`', static::$searchCols) . '`';
			$sql .= "ALTER TABLE `{$searchShadowTable}` ADD FULLTEXT INDEX ({$colStr});\n";


			db()->rawQueryMulti($sql);

			// sleep
			sleep(5);


			$sql = "CREATE TRIGGER `{$searchShadowTable}_insert` AFTER INSERT ON `{$table}` FOR EACH ROW INSERT INTO `{$searchShadowTable}` SELECT * FROM `{$table}` WHERE `{$pk_field}`=NEW.`{$pk_field}`;\n";
			db()->rawQueryMulti($sql);

			$sql = "CREATE TRIGGER `{$searchShadowTable}_delete` AFTER DELETE ON `{$table}` FOR EACH ROW DELETE FROM `{$searchShadowTable}` WHERE `{$pk_field}`=OLD.`{$pk_field}`;\n";
			db()->rawQueryMulti($sql);

			$sql = "
CREATE TRIGGER `{$searchShadowTable}_update` AFTER UPDATE ON `{$table}`
  FOR EACH ROW BEGIN
	DELETE FROM `{$searchShadowTable}` WHERE `{$pk_field}`=NEW.`{$pk_field}`;
	INSERT INTO `{$searchShadowTable}` SELECT * FROM `{$table}` WHERE `{$pk_field}`=NEW.`{$pk_field}`;
  END;
";
			db()->rawQueryMulti($sql);


			// populate it
			db()->rawQuery("INSERT INTO `" . static::searchShadowTable() . "` SELECT * FROM `" . static::$table);

			// sleep
			sleep(10);
			
			// force total column reload
			self::importTableColumns(true);

		} else {
			$searchShadowTable = static::searchShadowTable();

			// the table does exist. do its columns match?
			$shadow_columns = db()->get_table_columns($searchShadowTable);
			$main_columns = db()->get_table_columns(static::$table);

			if (count(array_diff($shadow_columns, $main_columns)) > 0 || count(array_diff($main_columns, $shadow_columns)) > 0) {
				// drop the triggers
				$sql = "DROP TRIGGER IF EXISTS `{$searchShadowTable}_insert`";
				db()->rawQueryMulti($sql);
				$sql = "DROP TRIGGER IF EXISTS `{$searchShadowTable}_update`";
				db()->rawQueryMulti($sql);
				$sql = "DROP TRIGGER IF EXISTS `{$searchShadowTable}_delete`";
				db()->rawQueryMulti($sql);

				// drop the shadow
				db()->rawQuery("DROP TABLE IF EXISTS `" . static::searchShadowTable() . "`");

				// drop myself from columns index
				unset(self::$columns[static::searchShadowTable()]);
				unset(self::$columnsMeta[static::searchShadowTable()]);
				
				// call myself recursively.
				static::createSearchShadowTable();
			}
		}
		//static::$searchShadowExists = true;
	}

	protected static function rebuildSearchShadowTable() {
		if (db()->table_exists(static::searchShadowTable())) {
		}
	}

	public static function searchShadowTable() {
		return "_" . static::$table . "_fulltext";
	}

	public static function buildAllSearchShadowTables() {
		$cmsTables = self::getCMSTables();
		foreach ($cmsTables as $t) {
			$cls = Model::clsname($t);
			if ($cls::$enableSearch == true) {
				$cls::createSearchShadowTable();
			}
		}
		return true;
	}

	
	public static function exportCSV() {
		// build header row
		static::loadAllMeta();

		// rather than fetch with fetch(), we're going to stream it.
		$sql = "select * from `" . static::$table . "`";
		$query = db()->db->prepare($sql);
		$query->execute();
		$count = -1;
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			$count++;
			
			if ($count == 0) {
				$header = array();
				// build header row
				$headerCols = array_keys($row);
				foreach ($headerCols as $col) {
					if (isset(static::$metadata[$col])) {
						$fieldmeta = static::$metadata[$col];
						$header[] = $fieldmeta["label"];
					} else {
						$header[] = $col;
					}
				}
				echo csv_implode($header);
				continue;
			}
			echo csv_implode(array_values($row));
		}
	
	}
	
}
