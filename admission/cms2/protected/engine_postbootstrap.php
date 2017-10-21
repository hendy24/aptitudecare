<?php
//Build search shadow tables if necessary
CMS_Table::buildAllSearchShadowTables() ;

// This is the final hand-off after site initialization.  If we are not inside a CLI script, render the website.
if (! is_CLI() ) {
	$main = MainController::getInstance();
	$main->prepare();
	$main->run();
	session_write_close();
}
