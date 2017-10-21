<?php

abstract class MainControllerBase extends Singleton {

	protected $page = false;
	protected $action = false;
	protected $controller = false;
	protected $handleMissingView = false;
	
	public function init() { }
	
	public function prepare() {

		if (! is_CLI()) {
			$_page = input()->page;
			$_action = input()->action;
			if (empty($_page)) {
				$_page = "home";
			}

			if (empty($_action)) {
				$_action = "index";
			}

			$this->action = $_action;
			$this->page = $_page;

			$_c = "PageController" . clsname($this->page);
			if (class_exists($_c)) {
				$this->controller = new $_c;
			} else {
				$record = new CMS_Dynpage;
				$record->loadByName($this->page);
				if ($record->valid()) {
					$this->controller = new PageControllerDynpage;
					$this->controller->setDynRecord($record);
					$this->page ="dynpage";
					$this->action = "index";
					$this->controller->prepare($this->page, $this->action);
					return;
				} else {
					$this->controller = new PageControllerGeneric;
				}
			}
			//ob_start('ob_tidyhandler');
			$this->controller->prepare($this->page, $this->action);
			
		}
	}

	public function run() {
		if (! is_CLI()) {
			$this->controller->run();
		}
	}
	
	public static function startTimer() {
		Logging::startTimer("total-runtime");
	}
	
	public static function logRuntime() {
		// Logging both total and SQL -- combine into a single statement
		if (defined('SITE_LOG_SQL_RUNTIME') && SITE_LOG_SQL_RUNTIME == TRUE && defined('SITE_LOG_TOTAL_RUNTIME') && SITE_LOG_TOTAL_RUNTIME == TRUE) {
			$idx = Logging::lookupTimerByAlias("total-runtime");
			$totalTime = Logging::stopTimer($idx);
			$sqlTime = Logging::getTimerGroupRuntime("mysql");
			$nonSQLTime = $totalTime - $sqlTime;
			Logging::diskWriteApp("[TOTAL] - {$totalTime} ({$sqlTime} sql, {$nonSQLTime} non-sql" . " - " . currentURL(), "runtime");
		} else {
			// Log one or the other
			if (defined('SITE_LOG_SQL_RUNTIME') && SITE_LOG_SQL_RUNTIME == TRUE) {
				$sqlTime = Logging::getTimerGroupRuntime("mysql");
				Logging::diskWriteApp("[SQL] {$sqlTime} - " . currentURL(), "runtime");
			}
			if (defined('SITE_LOG_TOTAL_RUNTIME') && SITE_LOG_TOTAL_RUNTIME == TRUE) {
				$idx = Logging::lookupTimerByAlias("total-runtime");
				$totalTime = Logging::stopTimer($idx);
				Logging::diskWriteApp("[TOTAL] {$totalTime} - " . currentURL(), "runtime");
			}
		}
		
	}
	
	public function setHandleMissingView(closure $func) {
		$this->handleMissingView = $func;
	}
	
	public function getHandleMissingView() {
		return $this->handleMissingView;
	}
	
}
