<?php

class ClientFile extends AppData {

	protected $table = 'client_file';


	public function fetchFiles($prospect) {
		$fileType = $this->loadTable('FileType');

		$sql = "SELECT
					file_type.name,
					client_file.file_name
				FROM {$this->tableName()} as client_file
				INNER JOIN {$fileType->tableName()} as file_type ON file_type.id = client_file.file_type
				WHERE client_file.client = :prospect";
		$params[":prospect"] = $prospect;

		return $this->fetchAll($sql, $params);
	}


	public function fetchFileTypeName() {
		$file_type = $this->loadTable('FileType');

		$sql = "SELECT file_type.name, client_file.file_name FROM {$this->tableName()} as client_file INNER JOIN {$file_type->tableName()} as file_type ON file_type.id = client_file.file_type WHERE client_file.id = :id";
		$params[":id"] = $this->id;

		return $this->fetchOne($sql, $params);
	}

}