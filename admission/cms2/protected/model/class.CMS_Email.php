<?php

class CMS_Email extends CMS_Table {
	
	public static $table = "_email";
	protected static $enableCreateStructure = true;
	public static $inAdmin = false;
	
	/**
	 * General constructor
	 * 
	 * @param int $id       
	 * @param int $multi_idx 
	 * 
	 * @return CMS_Email    
	 */
	public function __construct($id = false, $multi_idx = 0) {
		parent::__construct($id, $multi_idx);
		if (! in_array("datetime_created", self::$columns[static::$table])) {
			db()->query("ALTER TABLE `" . static::$table . "` add `datetime_created` datetime default NULL");
			self::importTableColumns(true);
		}
		if (! in_array("recipient_email", self::$columns[static::$table])) {
			db()->query("ALTER TABLE `" . static::$table . "` add `recipient_email` text default NULL");
			self::importTableColumns(true);
		}
	}
}