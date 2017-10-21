<?php

class CalculateAndSaveADC extends CLIScript {
	protected static $firstRun = '2014-06-06 00:01:00';
	protected static $intervalDays = 1;
	protected static $intervalHours = 0;
	protected static $intervalMinutes = 0;
	protected static $enabled = true;
	
	
	public static function exec() {
		$date = date('Y-m-d 23:59:59', strtotime('now - 1 day'));
		
		$dayCount = date('j', strtotime($date));
		$currentVals = array();
		
		$obj = new CMS_Census_Data_Month();
		
		// If first day of month
		if ($dayCount == 1) {
			// Calculate total ADC for month and save to the census_data table
			CMS_Census_Data::calcAndSaveData();
			
			// clear the census_data month table for all locations
			$obj->clearTable();
		} 
		
				
		// Get the census for today for all locations
		$todayCensus = CMS_ROOM::fetchCurrentCensus($date);
		
		
		// save census info to census_data_month
		foreach ($todayCensus[0] as $c) {
			$obj->saveDayCensusData($c->facility, $c->census, $date);
		}	

/*
 *
 * This code can be used to calculate values for a month all at once
		$i = 0;
		while ($i < 4) {
			if ($i != 0) {
				$date = date('Y-m-d 23:59:59', strtotime($date . " + 1 days"));
			}
			
			$todayCensus = CMS_ROOM::fetchCurrentCensus($date);
			
			// save census info to census_data_month
			foreach ($todayCensus[0] as $c) {
				$obj->saveDayCensusData($c->facility, $c->census, $date);
			}	
			$i++;
		}
*/

	}
}