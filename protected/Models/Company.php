<?php

class Company extends Model {
	
	public $table = 'gen_company';
	
	public function fetchCompany() {
		$sql = "select * from {$this->table}";
		
		return $this->fetchRow($sql);
	}
	
}