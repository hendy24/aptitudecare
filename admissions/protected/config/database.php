<?php

//CMSv2 MySQL db connection
// this connection will be the same for all sites both locally and remote
$dbCMS = new db_mysql();
if (DEVELOPMENT == TRUE) {
	$dbCMS->dbname = "cms2";	
} else {
	$dbCMS->dbname = "cms2";
}

$dbCMS->host = "localhost";
$dbCMS->username = "cms2";
$dbCMS->password = "2ooB6kHA";
$dbCMS->conn();

//function dbCMS() { global $dbCMS; return $dbCMS; }


//MySQL db connection
// this connection needs to be established based on site and whether remote or local
$db = new db_mysql();
$db->dbname = "admit_dev";
$db->host = "localhost";
$db->username = "aptitude";
$db->password = "nd7NE9EmPpid";
$db->conn();
