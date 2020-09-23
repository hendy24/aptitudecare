<?php

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class Texture extends Dietary {

	protected $table = "texture";
	protected $join = "texture_id";


	public function fetchTextures($patient_id) {
		$patient_texture = $this->loadTable('PatientTexture');
    	$sql = "SELECT DISTINCT
					t.id,
					pt.patient_id,
					CASE
						WHEN t.is_other = 1 AND pt.patient_id = :patient_id THEN t.name
						WHEN t.is_other = 0 THEN t.name
					END AS name,
					t.is_other,
					t.is_liquid,
					t.is_puree
				FROM {$this->tableName()} t
				LEFT JOIN {$patient_texture->tableName()} pt ON pt.texture_id = t.id AND pt.patient_id = :patient_id
				GROUP BY t.id HAVING name IS NOT NULL";
		
		$params[":patient_id"] = $patient_id;
    	return $this->fetchAll($sql, $params);
	}



} // END class Texture extends Dietary 