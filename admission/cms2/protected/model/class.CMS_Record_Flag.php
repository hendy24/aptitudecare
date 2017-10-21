<?php

class CMS_Record_Flag extends CMS_Table {

	protected static $enableCreateStructure = true;
	public static $inAdmin = false;
	public static $table = "record_flag";


	public function fetchByTable($table) {
		return db()->getRows($this->getTable(), array("table" => $table), "CMS_Record_Flag");
	}
	
	public static function fetchByTableAndName($table, $name) {
		return db()->getRow(static::$table, array("table" => $table, "name" => $name), "CMS_Record_Flag");
	}

	// returns all records referenced by the comma-delimited set of pkeys in $this->val
	public function records() {
		$pkey_vals = explode(",", $this->val);
		array_walk($pkey_vals, 'arr_trim');
		$_records = array();
		if (is_array($pkey_vals)) {
			$cls = clsname("CMS_" . $this->table);
			$obj = new $cls;
			foreach ($pkey_vals as $pk_val) {
				$_records[] = $obj->fetchOne($pk_val);
			}
		}
		return $_records;
	}

	public function hasThisFlag(&$obj) {
		if (! $obj->valid()) {
			return false;
		}
		$pkey_vals = explode(",", $this->val);
		array_walk($pkey_vals, 'arr_trim');
		return in_array($obj->pk(), $pkey_vals);
	}

	public function flagRecord(&$obj) {
		if ($obj->getTable() != $this->record->table) {
			throw new ORMException("This object is not of the right type to receive this flag.");
		}
		if ($this->type == "SINGLE" || ($this->type == "MULTI" && $this->val == '')) {
			$this->val = $obj->pk();
			$this->save();
		} elseif ($this->type == "MULTI") {
			$pkey_vals = explode(",", $this->val);
			array_walk($pkey_vals, 'arr_trim');
			if (! in_array($obj->pk(), $pkey_vals) ) {
				$pkey_vals[] = $obj->pk();
				$this->val = implode(",", $pkey_vals);
				$this->save();
			}
		}
	}

	public function unflagRecord(&$obj) {
		if ($obj->getTable() != $this->getRecord()->table) {
			throw new Exception("This object is not of the right type to have this flag.");
		}
		if ($this->type == "SINGLE") {
			if ($this->val == $obj->pk()) {
				$this->val = '';
				$this->save();
			}
		} elseif ($this->type == "MULTI") {
			$pkey_vals = explode(",", $this->val);
			array_walk($pkey_vals, 'arr_trim');
			$_pv = array();
			if (in_array($obj->pk(), $pkey_vals)) {
				foreach ($pkey_vals as $v) {
					if ($v != $obj->pk()) {
						$_pv[] = $v;
					}
				}
				$this->val = implode(",", $_pv);
				$this->save();
			}
		}
	}

}
