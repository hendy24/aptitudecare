<?php

class Logging {
	
	protected static $table_exists = false;
	protected static $timerStart = false;
	protected static $timerEnd = false;
	
	
	private static $timerIdx = 0;
	private static $timerTotal = 0;
	private static $timers = array();
	private static $timerAliases = array();
	
	
	public static function dumpTimers() {
		vd(self::$timers);
	}
	
	// adds a new timer to the stack and returns the array index of that timer
	public static function startTimer($alias = false, $group = "general") {
		if (! isset(self::$timers[$group] )) {
			self::$timers[$group] = array();
		}
		array_push(self::$timers[$group], array(microtime(true), false));
		$idx =count(self::$timers[$group]) - 1;
		if ($alias !== false) {
			self::$timerAliases[$group][$alias] = $idx;
		}
		return $idx;
	}
	
	// marks end time for given timer and returns the runtime
	public static function stopTimer($idx, $group = "general") {
		self::$timers[$group][$idx][1] = microtime(true);
		return self::$timers[$group][$idx][1] - self::$timers[$group][$idx][0];
	}
	
	public static function lookupTimerByAlias($alias, $group = "general") {
		if (isset(self::$timerAliases[$group][$alias])) {
			$idx = self::$timerAliases[$group][$alias];
			return $idx;
		} else {
			return false;
		}
	}
	
	public static function getTimerRuntime($idx, $group = "general") {
		if (isset(self::$timers[$group][$idx])) {
			$timer = self::$timers[$group][$idx];
			return $timer[1] - $timer[0];
		} else {
			return false;
		}
	}
	
	public static function getTimerGroupRuntime($group = "general") {
		$total = 0;
		foreach (self::$timers[$group] as $timer) {
			$total += $timer[1] - $timer[0];
		}
		return $total;
	}
	
	public static function loadTable() {
		if (self::$table_exists == false) {
			if (! db()->table_exists('_log') ) {
				$sql = file_get_contents(ENGINE_PROTECTED_PATH . "/var/sql/log.sql");
				db()->rawQuery($sql);
				self::$table_exists = true;
			}
		}		
	}
	
	public static function write($message, $class = null) {
		self::loadTable();
		db()->simple_insert("_log", array(
			"class" => $class,
			"datetime" => datetime(),
			"message" => $message
		));
	}
	
	public static function diskWriteApp($message, $class = "general") {
		$message = datetime() . " - " . rtrim($message);
		$_old = umask(0);
		if (! file_exists( APP_PROTECTED_PATH . "/var/log") ) {
			@mkdir(APP_PROTECTED_PATH . "/var/log", 0777, true);
		}
		if (! file_exists( APP_PROTECTED_PATH . "/var/log/{$class}.log") ) {
			@touch(APP_PROTECTED_PATH . "/var/log/{$class}.log");
			@chmod(APP_PROTECTED_PATH . "/var/log/{$class}.log", 0777);
		}
		umask($_old);
		if (! fileperms(APP_PROTECTED_PATH . "/var/log") == 0777) {
			return false;
		}
		$fp = fopen(APP_PROTECTED_PATH . "/var/log/{$class}.log", "a");
		fwrite($fp, $message . "\n");
		fclose($fp);
		
	}
	
}