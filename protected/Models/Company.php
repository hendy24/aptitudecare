<?php

class Company extends AppData {
	
	protected $table = 'company';
	
	public function getEmailExt() {
		$sql = "select global_email_ext from {$this->tableName()} where id=1";
		
		$result = $this->fetchOne($sql);

		if (!is_null($result)) {
			return $result;
		}

		return false;
	}
	
}