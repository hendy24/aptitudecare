<?php

class CMS_Metatag extends CMS_Table {

	protected static $enableCreateStructure = true;
	public static $inAdmin = true;
	public static $table = "metatag";
	public static $modelTitle = "SEO / Titles / Metatags";

	protected static $metadata = array(
		"page" => array(
			"label" => "Page (/?page=)",
			"widget" => "pages"
		),
		"title" => array(
			"label" => "Title",
			"widget" => "text"
		)
	);

	public function getTitle() {
		return $this->page;
	}
	
	public function fetchByPage($page, $action) {
		if ($page == '') {
			return false;
		}
		if ($action == '') {
			$action = "index";
		}
		
		$params = array();
		if ($action == "index") {
			$sql = "select * from `" . static::$table . "` where `page`=:page1 or `page`=:page2";
			$params[":page1"] = $page;
			$params[":page2"] = "{$page}/{$action}";
		} else {
			$sql = "select * from `" . static::$table . "` where `page`=:page";
			$params[":page"] = "{$page}/{$action}";
		}
		return $this->fetchCustom($sql, $params);
		
	}
}
