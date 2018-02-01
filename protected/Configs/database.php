<?php

	$db = new MySqlDb();

	$db->host = 'aptitudecare-dev.cwsngrwvtxuf.us-east-1.rds.amazonaws.com';
	$db->dbname = 'aptitudecareDev';
	$db->username = 'aptitudecare';
	$db->password = 'cio29t9viGiM^Fdm';
	$db->conn();

	$db2 = new MySqlDb();
	$db2->dbname = 'aptitudecareAdmissionsDev';
	$db2->host = 'aptitudecare-admissions.cwsngrwvtxuf.us-east-1.rds.amazonaws.com';
	$db2->username = 'aptitudecare';
	$db2->password = 'cio29t9viGiM^Fdm';
	$db2->conn();