<?php

class EmailDelivery extends CLIScript {

	protected static $firstRun = "1970-01-0-1 23:59:59";
	protected static $intervalDays = 0;
	protected static $intervalHours = 0;
	protected static $intervalMinutes = 3;
	
	protected static $enabled = false;

	public static function exec() {

		echo Email::sendAll();
		
	}


}
