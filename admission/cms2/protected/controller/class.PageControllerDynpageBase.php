<?php

class PageControllerDynpageBase extends PageController {

	private $record;

	public function init() {

	}
	public function prepare($page, $action) {
		// I will be called with page=dynpage, action=index
		parent::prepare($page, $action);
		
		// this is fine with dynpage/index
		$this->setPage($page);
		$this->setAction($action);

		// same with this
		$this->setViewPage($page);
		$this->setViewAction($action);
		
		// not with this
		$this->setMetaPage($this->record->name);
	}
	
	public function setDynRecord($record) {
		$this->record = $record;
	}

	public function index() {
		smarty()->assignByRef("record", $this->record);
	}

	
}