<?php

class PatientNote extends AppData {
	
	protected $table = "patient_note";

	public function fetchNotes($patient_id) {
		$sql = "SELECT * FROM {$this->tableName()} WHERE patient_id = :patient_id";
		$params[":patient_id"] = $patient_id;
		return $this->fetchAll($sql, $params);
	}

	public function checkExisting() {
		// search for matching records
		$sql = "SELECT * FROM {$this->tableName()} WHERE patient_id = :patient_id AND file = :filename LIMIT 1";
		$params = array(
			":patient_id" => $this->patient_id,
			":filename" => $this->file
		);

		$result = $this->fetchOne($sql, $params);

		if (!empty ($result)) {
			return true;
		}
		
		return false;
	}


	public function fetchNote($patient_id = false, $filename = false) {
		$sql = "SELECT * FROM {$this->tableName()} WHERE patient_id = :patient_id AND file = :filename LIMIT 1";
		$params = array(
			":patient_id" => $patient_id,
			":filename" => $filename
		);

		return $this->fetchOne($sql, $params);
	}


}