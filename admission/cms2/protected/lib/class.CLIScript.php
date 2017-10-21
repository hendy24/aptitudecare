<?php

abstract class CLIScript extends Singleton {

	protected static $enabled = true;
	protected static $table_exists = false;

	public function init() {
		// the first time this class is loaded, make sure the table `script_log` is created
		if (self::$table_exists == false) {
			if (! dbCMS()->table_exists('script_log') ) {
				$sql = file_get_contents(ENGINE_PROTECTED_PATH . "/var/sql/script_log.sql");
				dbCMS()->rawQuery($sql);
				self::$table_exists = true;
			}
		}
	}

	public static function logRunStart() {
		dbCMS()->simple_insert("script_log", array(
			"datetime_initiated" => datetime(),
			"site" => APP_NAME,
			"script" => get_called_class()
		));
		return dbCMS()->insert_id();
	}

	public static function logRunEnd($id, $output = '') {
		dbCMS()->simple_update("script_log", array(
			"datetime_ended" => datetime(),
			"output" => $output
		), $id);
	}

	public static function getLastRun() {
		$sql = "select * from `script_log` where `site`=:site and `script`=:script order by `datetime_initiated` DESC LIMIT 1";
		$record = dbCMS()->getRowCustom($sql, array(":site" => APP_NAME, ":script" => get_called_class()));
		return $record;
	}

	public static function isDue() {
		if (static::$enabled == false) {
			return false;
		}
		$now = time();
		$lastRun = static::getLastRun();
		if ($lastRun == false) {
			echo "-> No previous runs detected.\n";
			// never been run before. have we passed the $firstRun date/time?
			if (strtotime(static::$firstRun) <= $now) {
				echo "--> Time for first run (" . static::$firstRun . ") has passed. Script will execute.\n";
				return true;
			} else {
				echo "--> Time for first run (" . static::$firstRun . ") has not yet passed. Script will NOT execute.\n";
				return false;
			}
		} else {
			$minutes = static::$intervalMinutes;
			$minutes += static::$intervalHours * 60;
			$minutes += static::$intervalDays * 24 * 60;
			$seconds = $minutes * 60;

			if (strtotime($lastRun->datetime_initiated) + $seconds <= $now) {
				echo "---> Script last ran at {$lastRun->datetime_initiated}.  This is more than " . static::$intervalDays . " days, " . static::$intervalHours . " hours, and " . static::$intervalMinutes . " minutes ago.  Script will execute.\n";
				return true;
			} else {
				echo "---> Script last ran at {$lastRun->datetime_initiated}.  This is less than " . static::$intervalDays . " days, " . static::$intervalHours . " hours, and " . static::$intervalMinutes . " minutes ago.  Script will NOT execute.\n";
				return false;
			}

		}
	}

	abstract public static function exec();



}