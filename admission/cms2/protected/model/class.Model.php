<?php

abstract class Model {
	
	protected $record;
	protected $columnsUpdated = array();
	protected $columnsPopulated = array();
	
	public static $pk_col = "id";
	public static $table;

	public function __construct($pk_val) {
		$this->load($pk_val);
	}

	public static function clsname($table) {
		return "CMS_" . clsname($table);
	}
	
	// this uses get_called_class(), which in turn uses late static bindings. Thus, you can always call self::generate() no matter
	// what extended class you're in, and you'll get a new instantiation of that extended class.
	public static function generate($table = null) {
		if (! is_null($table) ) {
			$cls = self::clsname($table);
		} else {
			$cls = get_called_class();
		}
		return new $cls;
	}

	public function save() {
		// get the columns this table actually accepts
		$actualCols = db()->get_table_columns(static::$table);

		$pairs = get_object_vars($this->record);
		
		// get rid of any $pairs entries that don't correspond to real cols
		foreach ($pairs as $key => $val) {
			if (! in_array($key, $actualCols)) {
				unset($pairs[$key]);
			}
		}

		// PK is non-empty: update
		if ($pairs[static::$pk_col] != '') {
			unset($pairs[static::$pk_col]);
			if (! db()->simple_update(static::$table, $pairs, $this->pk(), static::$pk_col)) {
				throw new ORMException("Failed to update table/record.");
			} else {
				$this->load($this->pk());
			}
		}
		
		// PK is empty: insert
		else {
			unset($pairs[static::$pk_col]);
			try {
				if (!db()->simple_insert($this->getTable(), $pairs)) {
					throw new ORMException();
				} else {
					$id = db()->insert_id();
					$this->load($id);
				}
			} catch (PDOException $e) {
				/*
				if ($e->getCode() == 23000) {
					throw new ORMUniqueException($e->getMessage(), $e->getCode());
				} else {
					throw new ORMException($e->getMessage(), $e->getCode());
				}
				*/
				handle_pdo_exception($e, get_called_class());
			}
		}
	}

	public function pk() {
		return $this->record->{static::$pk_col};
	}

	public function load($pk_val) {
		$this->record = db()->getRow(static::$table, array(static::$pk_col => $pk_val));
		$this->clearColumnsUpdated();
		$this->setAllColumnsPopulated();
	}

	public function valid() {
		if ($this->record == false)
			return false;
		return true;
	}

	public function __get($key) {
		if (array_key_exists($key, $this->record)) {
			return $this->record->{$key};			
		} elseif (property_exists($this, $key)) {
			return $this->{$key};
		}
	}
	
	public function __set($key, $value) {
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
	}

	public function __unset($key) {
		unset ($this->record->{$key});
		$this->setColumnNotUpdated($key);
	}

	
	public function getColumnsUpdated() {
		return $this->columnsUpdated;
	}
	
	public function clearColumnsUpdated() {
		$this->columnsUpdated = array();
	}	
	
	public function columnIsUpdated($key) {
		return in_array($key, $this->columnsUpdated);
	}

	public function getColumnsPopulated() {
		return $this->columnsPopulated;
	}
	
	public function clearColumnsPopulated() {
		$this->columnsPopulated = array();
	}
	
	public function setAllColumnsPopulated() {
		if (isset($this->record) && is_object($this->record)) {
			$columns = array_keys(get_object_vars($this->record));
		} else {
			$columns = array();
		}
		$this->columnsPopulated = $columns;
	}
	public function setAllColumnsUpdated() {
		if (isset($this->record) && is_object($this->record)) {
			$columns = array_keys(get_object_vars($this->record));
		} else {
			$columns = array();
		}
		$this->columnsUpdated = $columns;
	}
	
	public function columnIsPopulated($key) {
		return in_array($key, $this->columnsPopulated);
	}
	
	public function setColumnNotUpdated($key) {
		$idx = array_search($key, $this->columnsUpdated);
		if ($idx !== false) {
			unset($this->columnsUpdated[$idx]);
		}
	}
	
	public function setColumnUpdated($key) {
		if (! $this->columnIsUpdated($key) ) {
			$this->columnsUpdated[] = $key;
		}
	}

	public function setColumnNotPopulated($key) {
		$idx = array_search($key, $this->columnsPopulated);
		if ($idx !== false) {
			unset($this->columnsPopulated[$idx]);
		}
	}
	
	public function setColumnPopulated($key) {
		if (! $this->columnIsPopulated($key) ) {
			$this->columnsPopulated[] = $key;
		}
	}
	
	public function getRecord() {
		return $this->record;
	}
	
	
}