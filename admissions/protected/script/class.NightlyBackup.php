<?php

class NightlyBackup extends CLIScript {
	protected static $firstRun = '2014-07-24 06:30:00';
	protected static $intervalDays = 1;
	protected static $intervalHours = 0;
	protected static $intervalMinutes = 0;
	protected static $enabled = false;
	
	public static function exec() {
		// Set the current time
		$now = date('Y-m-d', strtotime('now'));
		$path = ROOT_PATH;
		$site = SITE_NAME;
		$db = DB;
		$folder = $now . "_" . $site;
						
		if ($site != '') {
			//create a new folder for today's backup
			mkdir(ROOT_PATH . "/backups/{$folder}");
			
			// Backup database
			exec("mysqldump -e admit_dev > {$path}/backups/{$folder}/{$db}_{$now}.sql");
			
			// Backup code
			exec("rsync -avzp --exclude protected/assets/* {$path}/dev/ {$path}/backups/{$folder}/{$site}");
			
			// Backup assets
			exec("rsync -avuzp {$path}/dev/protected/assets {$path}/backups/{$folder}/protected_assets");
			
			// tarball it up
			exec("tar zcvf {$path}/backups/{$folder}.tar.gz {$path}/backups/{$folder}");
			
			// drop the directory we just tarred up
			exec("rm -rf {$path}/backups/{$folder}");
			
			// find any files older than 14 days and delete them
			$files = preg_grep('/^([^.])/', scandir("{$path}/backups"));
			
			foreach ($files as $f) {
				$date = explode("_", $f);
				$first_day =  (date('Y-m-d', strtotime("{$now} - 14 days")));
				if (strtotime($date[0]) < strtotime($first_day)) {
					exec("rm -rf {$path}/backups/" . $date[0] . "_" . $date[1]);
				}
			}
		}
	}
	
	
	
}