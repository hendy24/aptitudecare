<?php

abstract class PageControllerAdminBase extends PageController {
	public $auth;

	public function init() {
		//use alternate site frame
		$this->setMainTpl(ENGINE_PROTECTED_PATH . "/tpl/admin/main.tpl");

		//authentication
		$this->auth = Authentication_Admin::getInstance();
		smarty()->assign("admin_auth", $this->auth);
		
		//require login
		if (! $this->auth->valid()) {
			// redirect to login if you're not already there.
			if ($this->getAction() != "login" && $this->getAction() != "login_post") {
				redirect(SITE_URL . "/?page=admin&action=login&path=" . urlencode(currentURL()));
			}
		} else {
			//get table names for navigation
			$tables_nav = array();
			$tables = CMS_Table::getCMSTables();
			foreach ($tables as $t) {
				$cls = Model::clsname($t);
				$inAdmin = $cls::$inAdmin ;
				if ($this->auth->getRecord()->hasAccess($cls) && $inAdmin == true) {
					$tables_nav[$cls] = $cls::getModelTitle();
				}
			}
			
			smarty()->assign("tables_nav", $tables_nav);

			//get revtime for footer
			if (file_exists(ENGINE_PROTECTED_PATH . "/.revtime")) {
				$revtime = filemtime(ENGINE_PROTECTED_PATH . "/.revtime");
				smarty()->assign("revtime", $revtime);
			}

		}		
		
	}

	public function login() {
		$path = input()->path;
		smarty()->assign("path", $path);
		
	}

	public function login_post() {
		$email = input()->post("email");
		$password = input()->post("password");
		$path = urldecode(input()->post("path"));

		$this->auth = Authentication_Admin::getInstance();
		$this->auth->login($email, $password, $override);

		if ($this->auth->valid()) {
			if ($path != '') {
				redirect($path);
			} else {
				redirect(SITE_URL . "/?page=admin");
			}
		} else {
			feedback()->error("Invalid username or password. Please try again.");
			redirect(SITE_URL . "/?page=admin&action=login&path=" . urlencode($path));
		}
	}


	public function index() {

	}

	public function record_index() {
		$m = input()->m;
		if (! $this->auth->getRecord()->hasAccess($m)) {
			feedback()->error("Permission denied.");
			$this->redirect(SITE_URL . "/?page=admin");
		}
		$slice = input()->slice;
		
		$sliceSize = input()->sliceSize;
		if ($sliceSize == '') {
			$sliceSize = 25;
		}
		
		$obj = new $m;
		if ($m::$enablePagination == true) {
			$obj->paginationOn();
			$obj->paginationSetOverlap(true);
			$obj->paginationSetSliceSize($sliceSize);
			$obj->paginationSetSlice($slice);
		}
		
		// filter capabilities to be made available:
		$filterFields = array();
		$_filterFields = $m::getMetaByType("related_single");
		if (count($_filterFields) > 0) {
			$filterFields["related_single"] = $_filterFields;
		}

		if ($m::$enableSearch && input()->query != '') {
			$records = $obj->search(array(input()->query => false));
			smarty()->assign("query", input()->query);
		} else {
			// any filtering that may have already been posted:		
			$filterParamStr = "";
			$filterParams = array();
			// it's possible we've inherited filter params via base64 encoded
			// vals passed from record_index through to form and back to record_index,
			// putting us here.
			if (input()->filterParamStr != '') {
				parse_str(base64_decode(input()->filterParamStr), $filterParams);
				unset($filterParams["filter"]);
				$filterParams = $filterParams["record"];
			} else {
				// otherwise, it's possible that a fresh filter set has been posted
				// from the filter fields on record_index
				if (input()->filter == 1) {
					foreach ($filterFields as $type => $fields) {
						foreach ($fields as $filterField => $meta) {
							$filterField = $meta["field"];
							if (input()->record[$filterField] != '') {
								$filterParams[$filterField] = input()->record[$filterField];
							}
						}
					}
				}
			}
			$records = $obj->fetchForAdmin($filterParams);
		}
		
		// construct filter string so we can pass it to the form action, thereby allowing us to pass it to "save and advance"
		if (count($filterParams) > 0) {
			$filterParamStr .= "filter=1&";
			foreach ($filterParams as $k => $v) {
				$filterParamStr .= "record[{$k}]={$v}&";
			}
			$filterParamStr = rtrim($filterParamStr, "&");			
		}
		
		
		// handle request to advance to next row:
		if (input()->moveToNextId != '' && Validate::is_natural(input()->moveToNextId)->success()) {
			foreach ($records as $idx => $record) {
				if ($record->pk() == input()->moveToNextId) {
					if (! isset($records[$idx + 1]) ) {
					
						// I found myself in this slice's list, but me + 1 doesn't exist.
						
						if ($obj->paginationIsLastSlice()) {
							// We are the end of the last slice. You're not going anywhere!
							feedback()->warning("There are no more records!");
							$this->redirect(SITE_URL . "/?page=admin&action=record_index&m={$m}&slice={$obj->paginationGetSlice()}");
							break;
						} else {
							$this->redirect(SITE_URL . "/?page=admin&action=record_index&m={$m}&slice={$obj->paginationNextSlice()}&autoEditFirst=1");
						}
						
					} else {
						$nextRecord = $records[$idx + 1];	
					}
					break;
				}
			}
			$this->redirect(SITE_URL . "/?page=admin&action=form&m={$m}&from_slice={$obj->paginationGetSlice()}&{$m::$pk_col}={$nextRecord->pk()}");
		}
		
		// handle request to auto-edit the first row (which is actually how we handle the 'advance' request across two slices.
		if (input()->autoEditFirst == 1) {
			$record = $records[0];
			$this->redirect(SITE_URL . "/?page=admin&action=form&m={$m}&from_slice={$obj->paginationGetSlice()}&{$m::$pk_col}={$record->pk()}");
		}
		
		// permissions
		if ($m::getAdminCapability("new") == true || ($m::getAdminCapability("new") == "root" && $this->auth->is_root == 1)) {
			$enableNew = true;
		} else {
			$enableNew = false;
		}
		
		
		smarty()->assign(array(
			"enableNew" => $enableNew
		));
		smarty()->assignByRef("flags", $m::flags());
		smarty()->assignByRef("model_obj", $obj);
		smarty()->assignByRef("records", $records);
		smarty()->assignByRef("filterFields", $filterFields);
		smarty()->assign("model", $m);
		smarty()->assign("isSortable", $m::isSortable());
		smarty()->assign("pk_col", $m::$pk_col);
		smarty()->assign("modelTitle", $m::getModelTitle());
		smarty()->assign("enableSearch", $m::$enableSearch);
		smarty()->assign("modelActions", $m::getModelActions());
		smarty()->assign("enablePagination", $m::$enablePagination);
		smarty()->assign("sliceSize", $sliceSize);
		smarty()->assign("filterParamStr", $filterParamStr);
		
		if (input()->filter == 1 && count($filterParams) > 0) {
			smarty()->assign("filter", input()->record);
		}

		// related tables/models:
		$similarModels = array();
		foreach ($m::$similarModels as $sm) {
			$cl = Model::clsname($sm);
			$similarModels[$cl] = $cl::$modelTitle;
		}
		smarty()->assign("similarModels", $similarModels);
	}
	
	public function form() {
		$m = input()->m;
		if (! $this->auth->getRecord()->hasAccess($m)) {
			feedback()->error("Permission denied.");
			$this->redirect(SITE_URL . "/?page=admin");
		}

		$obj = new $m;
		$pk = $m::$pk_col;
		$pk_id = input()->{$pk};
		if ($pk_id != '') {
			$obj->load($pk_id);
			smarty()->assign("method", "update");
		} else {
			if ($m::getAdminCapability("new") == false || ($m::getAdminCapability("new") === "root" && $this->auth->is_root == 0)) {
				feedback()->error("Creating new records is disabled for this table.");
				$this->redirect(SITE_URL . "/?page=admin&action=record_index&m={$m}");
			}
			smarty()->assign("method", "create");
		}
		if ($m::getAdminCapability("new") == true || ($m::getAdminCapability("new") === "root" && $this->auth->is_root == 1)) {
			$enableNew = true;
		} else {
			$enableNew = false;
		}
		smarty()->assign(array(
			"enableNew" => $enableNew
		));

		smarty()->assignByRef("flags", $m::flags());
		smarty()->assignByRef("metadata", $m::loadAllMeta());
		smarty()->assign("model", $m);
		smarty()->assignByRef("obj", $obj);
		smarty()->assign("from_slice", input()->from_slice);
		smarty()->assign("modelTitle", $m::$modelTitle);
		smarty()->assign("enableAdminNew", $m::getAdminCapability("new"));
		smarty()->assign("filterParamStr", input()->filterParamStr);
		smarty()->assign("adminInstructions", $m::adminInstructions());
	}

	public function save() {
		$m = input()->m;
		if (! $this->auth->getRecord()->hasAccess($m)) {
			feedback()->error("Permission denied.");
			$this->redirect(SITE_URL . "/?page=admin");
		}
		$flags = input()->post("flags");

		$obj = $m::generate();
		$pk = $m::$pk_col;
		$from_slice = input()->from_slice;

		if ($m::getAdminCapability("new") == true || ($m::getAdminCapability("new") == "root" && $this->auth->is_root == 1)) {
			$enableNew = true;
		} else {
			$enableNew = false;
		}
		if ($obj->adminCanEdit()) {
			$enableEdit = true;
		} else {
			$enableEdit = false;
		}
		if ($obj->adminCanDelete()) {
			$enableDelete = true;
		} else {
			$enableDelete = false;
		}


		// Array of posted values.  May not contain a key for every column in this table, because browsers
		// will often completely leave out, eg. a column represented by an unchecked checkbox

		$input = input()->record;

		if ($input[$pk] != '') {
			// editing
			if ($enableEdit == false) {
				feedback()->error("Permission denied -- editing is disabled for this record.");
				$this->redirect(SITE_URL . "/?page=admin&action=record_index&m={$m}&slice={$from_slice}");
			}
			$obj->load($input[$pk]);
		} else {
			// new record
			if ($enableNew == false) {
				feedback()->error("Permission denied -- creating new records is disabled.");
				$this->redirect(SITE_URL . "/?page=admin&action=record_index&m={$m}&slice={$from_slice}");
			}
		}
		$existing = get_object_vars($obj->getRecord());

		// Not all table cols are necessarily posted by the form, and not all posted variables necessarily represent table cols
		// (eg, vals from multis)
		$all_keys = array_unique(array_merge(array_keys($m::loadAllMeta()), array_keys($existing), array_keys($input)));
		
		

		foreach ($all_keys as $k) {
			$meta = $obj->getFieldMeta($k);
			// ignore file fields where nothing was POSTed
			if ($meta["widget"] == "file") {
				if ($input[$k] == '') {
					continue;
				}
			}

			// if field is related-multi but nothing was posted, send an
			// empty array so CMS_Table::save() knows to clear those associations
			if ($meta["widget"] == "related_multi") {
				if ($input[$k] == '') {
					$obj->{$k} = array();
					continue;
				}
			}
			
			if ($meta["widget"] == "datetime") {
				if ($input[$k] != '') {
					$obj->{$k} = datetime(strtotime($input[$k]));
					continue;
				}
			}

			if ($meta["widget"] == "date") {
				if ($input[$k] != '') {
					$obj->{$k} = date("Y-m-d", strtotime($input[$k]));
					continue;
				}
			}

			if (array_key_exists($k, $input)) {
				// by default, pull value from the form post
				$v = $input[$k];
				$obj->{$k} = $v;
			} else {
				// the field was not represented in the form post.
				// we should leave it alone, unless it's a boolean, in which
				// case its absence implies it was unchecked.
				
				if ($meta["widget"] == "boolean") {
					$obj->{$k} = 0;
				}
  			    
			}
		}
		try {
			$obj->save();
		} catch (Exception $e) {
			feedback()->error("An error occurred - your changes were not saved.");
			if (DEVELOPMENT == true) {
				feedback()->error($e->getMessage());
			}
		}
		if (! feedback()->waserror()) {
			feedback()->conf("Your changes have been saved.");
		}

		$all_flags = $m::flags();
		foreach ($all_flags as $f) {
			$f->unflagRecord($obj);
		}
		if (is_array($flags)) {
			foreach ($flags as $flag_id => $val) {
				$flag_obj = new CMS_Record_Flag($flag_id);
				$flag_obj->flagRecord($obj);
			}
		}
		
		// handle "save and create new"
		if ((input()->save_and_new_x != '' && input()->save_and_new_y != '') || input()->save_and_new != '') {
			$this->redirect(SITE_URL . "/?page=admin&action=form&m={$m}&from_slice={$from_slice}");
		} 
		// handle "save and advance"
		elseif (input()->nextId != '' && (input()->save_and_advance_x != '' && input()->save_and_advance_y != '') || input()->save_and_advance != '') {
			$url = SITE_URL . "/?page=admin&action=record_index&m={$m}&slice={$from_slice}&moveToNextId={$obj->pk()}";
			if (input()->filterParamStr != '') {
				$url .= "&filterParamStr=" . input()->filterParamStr;
			}
			$this->redirect($url);
		} 
		// otherwise
		else {
			$this->redirect(SITE_URL . "/?page=admin&action=form&m={$m}&{$pk}={$obj->pk()}&from_slice={$from_slice}");
		}
	}

	public function addFile() {
		$m = input()->m;
		if (! $this->auth->getRecord()->hasAccess($m)) {
			feedback()->error("Permission denied.");
			$this->redirect(SITE_URL . "/?page=admin");
		}

		$from_slice = input()->from_slice;

		if ($m::getAdminCapability("new") == true || ($m::getAdminCapability("new") == "root" && $this->auth->is_root == 1)) {
			$enableNew = true;
		} else {
			$enableNew = false;
		}
		if ($m::getAdminCapability("edit") == true || ($m::getAdminCapability("edit") == "root" && $this->auth->is_root == 1)) {
			$enableEdit = true;
		} else {
			$enableEdit = false;
		}
		if ($m::getAdminCapability("delete") == true || ($m::getAdminCapability("delete") == "root" && $this->auth->is_root == 1)) {
			$enableDelete = true;
		} else {
			$enableDelete = false;
		}

		if ($enableEdit == false) {
			feedback()->error("Permission denied -- editing is disabled for this record.");
			$this->redirect(SITE_URL . "/?page=admin&action=record_index&m={$m}&slice={$from_slice}");
		}

		
		$file_field = input()->file_field;
		$vars = (Object) input()->post("record");
		$obj = new $m;
		$pk = $obj->getPrimaryKeyField();
		if ($vars->{$pk} != '') {
			$obj->load($vars->{$pk});
			try {
				$obj->addFile($file_field);
			} catch (ORMException $e) {
				feedback()->error("Your file could not be saved.  It may be not be an allowable file type or it could be a corrupted file.");
			}
		}

		redirect(SITE_URL . "/?page=admin&action=form&m={$m}&{$pk}={$vars->{$pk}}&from_slice={$from_slice}");

	}

	public function removeFile() {
		$m = input()->m;
		if (! $this->auth->getRecord()->hasAccess($m)) {
			feedback()->error("Permission denied.");
			$this->redirect(SITE_URL . "/?page=admin");
		}

		if ($m::getAdminCapability("new") == true || ($m::getAdminCapability("new") == "root" && $this->auth->is_root == 1)) {
			$enableNew = true;
		} else {
			$enableNew = false;
		}
		if ($m::getAdminCapability("edit") == true || ($m::getAdminCapability("edit") == "root" && $this->auth->is_root == 1)) {
			$enableEdit = true;
		} else {
			$enableEdit = false;
		}
		if ($m::getAdminCapability("delete") == true || ($m::getAdminCapability("delete") == "root" && $this->auth->is_root == 1)) {
			$enableDelete = true;
		} else {
			$enableDelete = false;
		}

		if ($enableEdit == false) {
			feedback()->error("Permission denied -- editing is disabled for this record.");
			$this->redirect(SITE_URL . "/?page=admin&action=record_index&m={$m}");
		}

		$obj = new $m;
		$pk = $obj->getPrimaryKeyField();
		$pk_id = input()->{$pk};
		$field = input()->field;
		$obj->load($pk_id);
		$obj->removeFile($field);
		redirect(SITE_URL . "/?page=admin&action=form&m={$m}&{$pk}={$pk_id}");


	}

	public function cropImage() {
		$m = input()->m;
		if (! $this->auth->getRecord()->hasAccess($m)) {
			feedback()->error("Permission denied.");
			$this->redirect(SITE_URL . "/?page=admin");
		}

		if ($m::getAdminCapability("new") == true || ($m::getAdminCapability("new") == "root" && $this->auth->is_root == 1)) {
			$enableNew = true;
		} else {
			$enableNew = false;
		}
		if ($m::getAdminCapability("edit") == true || ($m::getAdminCapability("edit") == "root" && $this->auth->is_root == 1)) {
			$enableEdit = true;
		} else {
			$enableEdit = false;
		}
		if ($m::getAdminCapability("delete") == true || ($m::getAdminCapability("delete") == "root" && $this->auth->is_root == 1)) {
			$enableDelete = true;
		} else {
			$enableDelete = false;
		}

		if ($enableEdit == false) {
			feedback()->error("Permission denied -- editing is disabled for this record.");
			$this->redirect(SITE_URL . "/?page=admin&action=record_index&m={$m}");
		}


		$obj = new $m;
		$pk = $obj->getPrimaryKeyField();
		$pk_id = input()->{$pk};
		$field = input()->field;
		$obj->load($pk_id);

		$widget = $obj->getFieldWidget($field);
		$path = $widget->getAssetPath() . "/" . $widget->value;

		$sizeondisk = getimagesize($path);
		$ratio = $sizeondisk[0] / input()->scaled_width;

		$real_x = input()->x * $ratio;
		$real_x2 = input()->x2 * $ratio;
		$real_y = input()->y * $ratio;
		$real_y2 = input()->y2 * $ratio;

		Image_Cache::cropImageOnDisk($path, $path, $real_x, $real_x2, $real_y, $real_y2);
		redirect(SITE_URL . "/?page=admin&action=form&m={$m}&{$pk}={$pk_id}");

	}

	public function delete() {
		$m = input()->m;
		if (! $this->auth->getRecord()->hasAccess($m)) {
			feedback()->error("Permission denied.");
			$this->redirect(SITE_URL . "/?page=admin");
		}

		if ($m::getAdminCapability("new") == true || ($m::getAdminCapability("new") == "root" && $this->auth->is_root == 1)) {
			$enableNew = true;
		} else {
			$enableNew = false;
		}
		if ($m::getAdminCapability("edit") == true || ($m::getAdminCapability("edit") == "root" && $this->auth->is_root == 1)) {
			$enableEdit = true;
		} else {
			$enableEdit = false;
		}
		if ($m::getAdminCapability("delete") == true || ($m::getAdminCapability("delete") == "root" && $this->auth->is_root == 1)) {
			$enableDelete = true;
		} else {
			$enableDelete = false;
		}

		if ($enableDelete == false) {
			feedback()->error("Permission denied -- deleting is disabled for this record.");
			$this->redirect(SITE_URL . "/?page=admin&action=record_index&m={$m}");
		}

		$obj = new $m;
		$pk = $obj->getPrimaryKeyField();
		$pk_id = input()->{$pk};
		$field = input()->field;
		$obj->load($pk_id);
		call_user_func_array(array($m, "delete"), array($obj));
		
		redirect(SITE_URL . "/?page=admin&action=record_index&m={$m}");

	}

	public function setPriorities() {
		$m = input()->m;
		if (! $this->auth->getRecord()->hasAccess($m)) {
			feedback()->error("Permission denied.");
			$this->redirect(SITE_URL . "/?page=admin");
		}
		$obj = new $m;
		$pk = $obj->getPrimaryKeyField();
		$sequence = input()->{$pk};
		$obj->setPrioritySequence($sequence);
		exit;
	}

	public function logout() {
		$this->auth->logout();
		$this->redirect(SITE_URL . "/?page=admin");
	}

	public function image() {
		if (input()->is_protected == 1) {
			define('SOURCE_IS_PROTECTED', true);
		}
		require_once ENGINE_PUBLIC_PATH . "/image.php";
	}

	public function executeAction() {
		$m = input()->m;
		$obj = new $m;
		$pk = $obj->getPrimaryKeyField();
		$pk_id = input()->{$pk};
		$field = input()->field;
		$obj->load($pk_id);
		$obj->executeAction(input()->a);
		$this->redirect(SITE_URL . "/?page=admin&action=form&m={$m}&id={$pk_id}");
	}

	public function executeModelAction() {
		$m = input()->m;
		call_user_func_array(array($m, "executeModelAction"), array(input()->a));
		$this->redirect(SITE_URL . "/?page=admin&action=record_index&m={$m}");
	}
	
	public function exportCSV() {
		$m = input()->m;
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		header("Cache-Control: no-store, no-cache, must-revalidate"); 
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header("Content-Type: text/csv");
		$filename = $m::$table . "." . time() . ".csv" ;
		header("Content-Disposition: attachment; filename=\"{$filename}\"");
		$m::exportCSV();
		session_write_close();
		exit;
	}
	
	public function submitProfile() {
		
		if (input()->id != $this->auth->getRecord()->id) {
			feedback()->error("Permission denied.");
		}
		
		if ( ! Validate::is_email(input()->email)->success()) {
			feedback()->error("You entered an invalid email address; please try again.");
		}
		
		if (input()->fullname == '') {
			feedback()->error("You must provide your full name.");
		}
		
		if (feedback()->wasError()) {
			$this->redirect(SITE_URL . "/?page=admin&action=profile");
		}
		
		$user = $this->auth->getRecord();
		$user->fullname = input()->fullname;
		$user->email = input()->email;
		
		if (input()->password != '') {
			$user->password = input()->password;
		}

		try {	
			$user->save();
			feedback()->conf("Your profile has been updated.");
		} catch (Exception $e) {
			feedback()->error("There was an error while trying to update your profile.");			
		}
		$this->redirect(SITE_URL . "/?page=admin&action=profile");
		
		exit;
		
	}
	
	public function testSuite() {
		
		// TODO(bcohen) Integrate with the autoload improvements that are coming soon
		$suite = input()->suite;
		if ($suite != '') {
			require_once ENGINE_PROTECTED_PATH . "/test_suite/class.TestSuite_{$suite}.php";
			$cls = "TestSuite_{$suite}";
			$cls::exec();
		}
		
		
		exit;
		
		
	}

}
