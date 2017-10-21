<?php
class PageController {
	private $page = false;
	private $action = false;
	private $main_tpl = 'main.tpl';
	private $viewPage;
	private $viewAction;
	private $metaPage;
	private $metaAction;
	protected $bottomLoad = false;
	protected $_handleMissingView = false;


	public function getPage() {
		return $this->page;
	}

	public function getAction() {
		return $this->action;
	}
	
	public function setMainTpl($path) {
		$this->main_tpl = $path;
	}

	public function setPage($page) {
		$this->page = $page;
	}

	public function setAction($action) {
		$this->action = $action;
	}

	public function setView($page, $action) {
		$this->setViewPage($page);
		$this->setViewAction($action);
	}

	public function setViewPage($page) {
		$this->viewPage = $page;
	}

	public function setViewAction($action) {
		$this->viewAction = $action;
	}
	
	public function getViewAction() {
		return $this->viewAction;
	}
	
	public function getViewPage() {
		return $this->viewPage;
	}

	public function setMetaPage($page) {
		$this->metaPage = $page;
	}

	public function setMetaAction($action) {
		$this->metaAction = $action;
	}
	
	public function getMetaPage() {
		return $this->metaPage;
	}
	
	public function getMetaAction() {
		return $this->metaAction;
	}

	public function prepare($page, $action) {
		$this->setPage($page);
		$this->setAction($action);

		$this->setViewPage($page);
		$this->setViewAction($action);
		
		$this->setMetaPage($page);
		$this->setMetaAction($action);
	}
	
	public function run() {
		try {
			$this->init();
			if (method_exists($this, $this->action)) {
				$this->{$this->action}();
			}
		} catch (AuthenticationDisallowedException $e) {
			if (is_object(auth())) {
				if (!auth()->valid()) {
					//feedback()->error("To proceed, please login.");
					redirect(SITE_URL . "/?page=login&path=" . urlencode(currentURL()));			
				} else {
					if (method_exists($this, $this->action)) {
						$this->{$this->action}();
					}					
				}
			}
		}
		$tpl_path = $this->tpl_path();
		if ($tpl_path !== false && file_exists($tpl_path)) {
			smarty()->assign("content_tpl", $tpl_path);
		} else {
			$this->handleMissingView();
		}
		$this->render();

		if (input()->_history == "true") {
			if (input()->getHistoryHold() == true) {
				input()->storeHistory(input()->_history_name);
			} else {
				input()->clearHistory(input()->_history_name);
			}
		}


		// always clear feedback after the page is rendered; you cannot count on having displayed the _feedback.tpl
		// but we don't want messages "backing up" in the session and throwing off wasError().
		feedback()->clear();
		MainControllerBase::logRuntime();		
		
	}

	public function render() {
		if (! isset($this->metaPage) ) {
			$this->setMetaPage($this->page);
		}
		if (! isset($this->metaAction) ) {
			$this->setMetaAction($this->action);
		}
		smarty()->assign(array(
			"page" => $this->getViewPage(),
			"action" => $this->getViewAction(),
			"viewPage" => $this->getViewPage(),
			"viewAction" => $this->getViewAction(),
			"metaPage" => $this->getMetaPage(),
			"metaAction" => $this->getMetaAction(),
			"navPage" => input()->page,
			"navAction" => (input()->action == '') ? "index" : input()->action,
			"_path" => input()->path,
			"path" => input()->path
		));
		$obj_metatag = new CMS_Metatag;
		$metatags = current($obj_metatag->fetchByPage($this->metaPage, $this->metaAction));
		if ($metatags !== false) {
			smarty()->assignByRef("metatags", $metatags);
			$GLOBALS[APP_NAME]["PAGE_TITLE"] = $metatags->title;
		}
		smarty()->assign("isMicro", input()->isMicro);
		smarty()->display($this->main_tpl);
		if ($this->bottomLoad != false) {
			$func = $this->bottomLoad;
			$func($this);
		}
	}

	public function redirect($url = '') {
		if (input()->_history == "true") {
			if (feedback()->wasError()) {
				input()->storeHistory(input()->_history_name);
			} else {
				input()->clearHistory(input()->_history_name);
			}
		}
		
		// Built-in path handling -- just set _path in a form post and it will override any other redirect target.
		if (input()->_path != '' && $url == '') {
			if (urldecode(input()->_path) !== input()->_path) {
				$url = urldecode(input()->_path);
			} else {
				$url = input()->_path;
			}
		}
		MainControllerBase::logRuntime();		
		redirect($url);
	}

	public function init() {
		return true;
	}

	public function index() {
		
	}

	public function tpl_path() {
		if (file_exists(APP_PROTECTED_PATH . "/tpl/{$this->viewPage}/{$this->viewAction}.tpl")) {
			return APP_PROTECTED_PATH . "/tpl/{$this->viewPage}/{$this->viewAction}.tpl";
		} elseif (file_exists(APP_PROTECTED_PATH . "/tpl/{$this->viewPage}.tpl")) {
			return APP_PROTECTED_PATH . "/tpl/{$this->viewPage}.tpl";
		} elseif (file_exists(ENGINE_PROTECTED_PATH . "/tpl/{$this->viewPage}/{$this->viewAction}.tpl")) {
			return ENGINE_PROTECTED_PATH . "/tpl/{$this->viewPage}/{$this->viewAction}.tpl";
		} elseif (file_exists(ENGINE_PROTECTED_PATH . "/tpl/{$this->viewPage}.tpl")) {
			return ENGINE_PROTECTED_PATH . "/tpl/{$this->viewPage}.tpl";
		} else {
			return false;
		}
	}

	public static function getPages() {
		$pages = array();
		$d = dir(APP_PROTECTED_PATH . "/tpl");
		while ($entry = $d->read()) {
			if ($entry == '.' || $entry == '..' || $entry == 'dynpage' || $entry == 'dynpage.tpl') {
				continue;
			}
			if (preg_match("/^_/", $entry)) {
				continue;
			}
			if (preg_match("/^(.*)\.tpl/", $entry, $matches)) {
				$pages[] = $matches[1];
			} elseif (is_dir(APP_PROTECTED_PATH . "/tpl/{$entry}")) {
				$d2 = dir(APP_PROTECTED_PATH . "/tpl/{$entry}");
				while ($e2 = $d2->read()) {
					if (preg_match("/^(.*)\.tpl/", $e2, $matches2)) {
						$pages[] = $entry . "/" . $matches2[1];
					}
				}
				$d2->close();
			}
		}

		$dynpages = CMS_Dynpage::fetchPageNames();
		foreach ($dynpages as $d => $dTitle) {
			$pages[] = $d;
		}

		sort($pages);
		return $pages;
	}
	
	public function setBottomLoad(closure $func) {
		$this->bottomLoad = $func;
	}
	
	public function setHandleMissingView(closure $func) {
		$this->handleMissingView = $func;
	}
	
	public function handleMissingView() {
		if ($this->_handleMissingView != false) {
			$func = $this->_handleMissingView;
			$func($this);
		} else {
			$main = MainController::getInstance();
			$func = $main->getHandleMissingView();
			if ($func != false) {
				$func($this);
			} else {
				header("HTTP/1.0 404 Not Found");
				session_write_close();
				exit;
			}
		}
	}

}
