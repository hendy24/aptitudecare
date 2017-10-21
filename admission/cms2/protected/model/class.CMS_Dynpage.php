<?php

class CMS_Dynpage extends CMS_Content {
	public static $table = "dynpage";
	public static $modelTitle = 'On-The-Fly Pages';
	public static $inAdmin = true;
	public static $enableAdminNew = "root";
	public static $enableAdminEdit = true;
	public static $enableAdminDelete = "root";
	protected static $enableCreateContent = true;

	protected static $metadata = array(
		"name" => array(
			"label" => "Page 'short name'",
			"widget" => "text",
			"instructions" => "This is used in the URL, eg. if you enter 'mypage' here, its URL will be www.mysite.com/?page=mypage"
		),
		"title" => array(
			"label" => "Page 'friendly name'",
			"widget" => "text",
			"instructions" => "This is used in the CMS/Admin for identification purposes."
		),
		"copy" => array(
			"label" => "Content",
			"widget" => "textarea_html"
		)
	);

	public static function fetchPageNames() {
		$obj = static::generate();
		$sql = "select `name`,`title` from `dynpage` order by `title`";
		$records = $obj->fetchCustom($sql, array());
		$names = array();
		foreach ($records as $r) {
			$names[$r->name] = $r->title;
		}
		return $names;
	}

}