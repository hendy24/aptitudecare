<?php

/*
 *	All other classes will extend the MainController, so use functions here that need to be
 *	used in all other controllers.
 *
 */


class MainController {

	public $page;
	public $action;
	protected $template = 'main';
	protected $current_url;
	protected $helper = null;
	public $allow_access = false;



	/*
	 * -------------------------------------------------------------------------
	 *  CONSTRUCT THE CLASS
	 * -------------------------------------------------------------------------
	 */

	public function __construct() {
		// Load any other components defined in the child class
		if (!empty($this->components)) {
			foreach($this->components as $c) {
				$this->loadComponent($c);
			}
		}
	}


	/*
	 * -------------------------------------------------------------------------
	 *  AJAX CALL TO DELETE BY ID
	 * -------------------------------------------------------------------------
	 */

	public function deleteId() {

		//	If the id var is filled then delete the item with that id
		if (input()->id != '') {
			$model = getModelName(input()->page);
			$class = $this->loadModel($model);

			$class->public_id = input()->id;
			if ($class->delete()) {
				return true;
			}

			return false;
		}

		return false;
	}


	public function delete($id) {
		$model = getModelname(input()->page);
		$class = $this->loadModel($model);

		$class->public_id = $id;
		if ($class->delete()) {
			return true;
		}

		return false;
	}


	public function getUrl() {
		return $this->current_url;
	}




	/*
	 *
	 * -------------------------------------------------------------
	 *  LOAD MODELS, VIEWS, PLUGINS, COMPONENTS, AND HELPERS
	 * -------------------------------------------------------------
	 *
	 */


	protected function loadModel($name, $id = false, $module = false) {
		if ($module) {
			if (file_exists (MODULES_DIR . DS . $module . DS . 'Models' . DS . $name . '.php')) {
				require_once ( MODULES_DIR . DS . $module . DS . 'Models' . DS . $name . '.php');
			}
		} else {
			if (file_exists (FRAMEWORK_PROTECTED_DIR . DS . 'Models' . DS . $name . '.php')) {
				require_once (FRAMEWORK_PROTECTED_DIR . DS . 'Models' . DS . $name . '.php');
			} elseif (file_exists (APP_PROTECTED_DIR . DS . 'Models' . DS . $name . '.php')) {
				require_once (APP_PROTECTED_DIR . DS . 'Models' . DS . $name . '.php');
			} elseif (file_exists ( MODULES_DIR . DS . $this->module . DS . 'Models' . DS . $name . '.php')) {
				require_once ( MODULES_DIR . DS . $this->module . DS . 'Models' . DS . $name . '.php');
			}
		}

		if (class_exists($name)) {
			$class = new $name;
		} else {
			smarty()->assign('message', "Could not find the class {$name}");
			$errorController = new ErrorController;
			$errorController->action = "index";
			$this->loadView($errorController);
			exit;
		}


		if ($id) {
			return $class->fetchById($id);
		} else {
			//  This is an empty object, get the column names
			//	If the table is schedule then it is trying to access the admission dashboard
			//	we won't have access to this and don't need to get the column names from that
			//	table anyway.

			if ($class->tableName() != "schedule") {
				return $class->fetchColumnNames();
			} else {
				//	If the table variable isn't set in the model, then just return an empty object.
				return $class;
			}

		}

	}



	/*
	 * -------------------------------------------------------------------------
	 * PAGE VIEW
	 * -------------------------------------------------------------------------
	 *
	 *	Set the content - this is the tpl file fort method which is called in the
	 *	controller,  then call the default main.tpl file.
	 *
	 */

	public function loadView() {
		// This function used to be loadView($folder, $name, $module). These have been moved in to the controller
		// $folder = $controller->page, $name = $controller->action, $module = $controller->module
		// need to be able to allow specific controllers and/or actions past this login block
		// Make sure the user is logged in

		if (!auth()->isLoggedIn()) {
			// If the user is not logged in, check if public access to this page is allowed
			if (method_exists($this, 'allow_access')) {
				if (in_array($this->action, $this->allow_access())) {
					$this->allow_access = true;
				}
			}

			if (!$this->allow_access) {
				// If access is denied then re-direct to the login page
				$this->redirect(array('page' => 'login', 'action' => 'index'));
			}
		}

		// Check if the user is trying to logout from the admission module
		// this is a temporary fix and will be removed once the admission module is re-built in the new framework
		if (isset ($this->action)) {
			if ($this->action != "admission_logout") {
				$this->getSiteInfo();
			}
			// Create a variable for the current url to be used for re-direction, etc.
			$this->current_url = SITE_URL . $_SERVER['REQUEST_URI'];
			smarty()->assign('current_url', $this->current_url);

			// set the title for the view. Setting it in the controller method with overwrite this
			smarty()->assign('title', stringify($this->action));
			// Call the method in the controller connected with the view
			// This connects the controller method to the view file. IMPORTANT!!!
			$this->{$this->action}();
		}


		// Assign the controller to $this for access from within the view
		smarty()->assign("this", $this);

		// If a helper is called, load it
		if ($this->helper != null) {
			$helper = $this->loadHelper($this->helper, $this->module);
			smarty()->assignByRef(lcfirst($this->helper), $helper);
		}

		// Check session for errors to be displayed
		session()->checkFlashMessages();

		//	If is_micro is set in the url then display a blank template
		if (isset (input()->isMicro) && input()->isMicro == true) {
			$this->template = 'blank';
		}

		// set the base template
		if (isset (input()->pdf) && input()->pdf == true) {
			$this->createPDF();
		} else {
			smarty()->display("layouts/{$this->template}.tpl");
		}

	}





	/*
	 * -------------------------------------------------------------------------
	 *  LOAD AN ELEMENT
	 * -------------------------------------------------------------------------
	 */

	public function loadElement($name, $var = array()) {
		smarty()->assign("var", $var);
		smarty()->display("elements/{$name}.tpl");
	}




	/*
	 * -------------------------------------------------------------------------
	 *  LOAD A PLUGIN
	 * -------------------------------------------------------------------------
	 */

	public function loadPlugin($name) {
		if (file_exists (PROTECTED_DIR . '/plugins/' . $name . '.php')) {
			require (PROTECTED_DIR . '/plugins/' . $name . '.php');
		}
	}




	/*
	 * -------------------------------------------------------------------------
	 *  LOAD A HELPER -- this is a view helper
	 * -------------------------------------------------------------------------
	 */

	public function loadHelper($name, $module = null) {

		if (file_exists (FRAMEWORK_PROTECTED_DIR . '/Views/helpers/' . $name . 'Helper.php')) {
			require (FRAMEWORK_PROTECTED_DIR . '/Views/helpers/' . $name . 'Helper.php');
		} elseif (file_exists (APP_PROTECTED_DIR . DS . 'Views/helpers/' . $name . 'Helper.php')) {
			require (APP_PROTECTED_DIR . '/Views/helpers/' . $name . 'Helper.php');
		} elseif (file_exists (MODULES_DIR . DS . $module . DS . 'Views/helpers/' . $name . 'Helper.php')) {
			require (MODULES_DIR . DS . $module . DS . 'Views/helpers/' . $name . 'Helper.php');
		}

		$className = $name . 'Helper';

		$helper = new $className;
		return $helper;
	}




	/*
	 * -------------------------------------------------------------------------
	 *  LOAD A COMPONENT CLASS
	 * -------------------------------------------------------------------------
	 */

	public function loadComponent($name) {
		$component = new $name;
		return $component;
	}



	/*
	 * -------------------------------------------------------------------------
	 *  LOAD AN ALTERNATE TEMPLATE TO USE
	 * -------------------------------------------------------------------------
	 */

	public function template($name = false) {
		global $config;
		if ($name) {
			$config['main_template'] = $name.'.tpl';
		}

	}



	/*
	 * -------------------------------------------------------------------------
	 *  SET A VARIABLE TO BE LOADED WITH THE CLASS
	 * -------------------------------------------------------------------------
	 */

	public function set($name, $var) {
		$this->$name = $var;
	}







	/*
	 *
	 * -------------------------------------------------------------
	 *  PAGE REDIRECTION
	 * -------------------------------------------------------------
	 */

	public function redirect($params = false) {

		if (is_array($params)) {
				$redirect_url = SITE_URL . "/?";

				if (isset ($params['page'])) {
					$params['page'] =  strtolower(preg_replace('/([^A-Z-])([A-Z])/', '$1-$2', $params['page']));
				}

				if (isset ($params['action'])) {
					if ($params['action'] == 'index') {
						unset ($params['action']);
					}
				}
				foreach ($params as $k => $p) {
					$redirect_url .= "{$k}={$p}&";
				}

				$redirect_url = trim ($redirect_url, "&amp;");
		} elseif ($params) {
			$redirect_url = $params;
		} else {
			//$redirect_url = SITE_URL . "/?module=" . $this->module;
			$redirect_url = SITE_URL;
		}
		$this->redirectTo($redirect_url);

	}

	private function redirectTo($url) {
		header("Location: " . $url);
		exit;
	}


	/*
	 *
	 * -------------------------------------------------------------
	 *  VALIDATE DATA
	 * -------------------------------------------------------------
	 *
	 */

	 protected function validateData($dataArray = array(), $flash_message = false, $redirect_to = false) {
	 	$fail = false;
		$returnData = array();
		foreach ($dataArray as $key => $data) {
			foreach ($data as $k => $d) {
				 if ($d == '') {
				 	$fail = true;
					session()->setFlash($flash_message);
					$this->redirect($redirect_to);
				} else {
					session()->saveData($k, strip_tags($d));
					$returnData[$key][$k] = strip_tags($d);
				}
			}
		}

		if ($fail) {
			exit;
		}

		return $returnData;

	 }






	/*
	 *
	 * -------------------------------------------------------------
	 *  Looks in a folder and returns the contents
	 * -------------------------------------------------------------
	 *
	 * This method is especially useful for folders with photos (i.e. - for the slideshow on the home page)
	 *
	 */

	protected function directoryToArray($directory, $recursive) {
	    $array_items = array();
	    if ($handle = opendir($directory)) {
	        while (false !== ($file = readdir($handle))) {
	            if ($file != "." && $file != "..") {
	                if (is_dir($directory. "/" . $file)) {
	                    if($recursive) {
	                        $array_items = array_merge($array_items, directoryToArray($directory. "/" . $file, $recursive));
	                    }
	                    $file = $directory . "/" . $file;
	                    $array_items[] = preg_replace("/\/\//si", "/", $file);
	                } else {
	                    $file = $directory . "/" . $file;
	                    $array_items[] = preg_replace("/\/\//si", "/", $file);
	                }
	            }
	        }
	        closedir($handle);
	    } else {
		    echo "<br />Make sure $directory exists and try again.";
		    exit;
	    }

	    foreach ($array_items as $item) {
		    $explodedArray[] = (explode('/', $item));
	    }

	    foreach ($explodedArray as $a) {
		    $filteredArray[] = array_pop($a);

	    }

	    return $filteredArray;
	}




	public function getColumnHeaders($data, $class = null) {
		if (is_object($data)) {
			foreach($data as $key => $column) {
				if (!in_array($key, $data->fetchColumnsToInclude())) {
					unset($data->$key);
				}
			}
		} else {
			foreach($data as $key => $column) {
				if (!in_array($column, $class->fetchColumnsToInclude())) {
					unset($data[$key]);
				}
			}
		}


		return $data;
	}



	/*
	 * -------------------------------------------------------------
	 *  mPDF -- create PDF from HTML page
	 * -------------------------------------------------------------
	 *
	 */

	protected function createPDF() {
		require_once (VENDORS_DIR . DS . 'Libraries' . DS . 'mpdf60' . DS . 'mpdf.php');
		$url = str_replace('&pdf=true', '', SITE_URL . $_SERVER['REQUEST_URI']);

		$html = file_get_contents($url);
		$mpdf = new mPDF('urf-8', 'Letter', 0, '', 0, 0, 0, 0, 0, 0);
		$mpdf->CSSselectMedia = 'mpdf';
		$mpdf->setBasePath($url);
		// get the action name
		$action = $this->getPageAction($url);

		// check if this action needs a landscape or portrait orientation
		if (method_exists($this, "landscape_array")) {
			if ($this->landscape_array($action)) {
				$mpdf->AddPage('L');
			}
		}
		$mpdf->WriteHTML($html);
  		$mpdf->Output();
  		exit;
	}


	private function getPageAction($content) {
		$r = explode("&action=", $content);
		if (isset ($r[1])) {
			$r = explode("&", $r[1]);
			return $r[0];
		}
		return false;
	}


	/*
	 * -------------------------------------------------------------
	 *  PHPMailer -- send emails
	 * -------------------------------------------------------------
	 *
	 */

	public function sendEmail($data) {

		global $config;
		global $params;

		$mail = new PHPMailer(true);
		$mail->IsSMTP();

		/**
		 * These mail settings are specific to bluehost
		 */


		try {
			$mail->SMTPDebug = 2;
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = "ssl";
			$mail->Host = $config['email_host'];  // email must be sent from server for bluehost
			$mail->Port = 465;
			$mail->Username = $config['email_username'];
			$mail->Password = $config['email_password'];
			$mail->SetFrom($data['post']['email'], $data['post']['name']);
			$mail->AddAddress($config['email_to']);
			$mail->Subject = $params['site_name'] . ' Message: ' . $data['post']['subject'];
			$mail->Body = $data['post']['message_body'];
			if ($mail->Send()) {
				return true;
			} else {
				return false;
			}
		} catch (phpmailerException $e) {
			echo $e->errorMessage(); //Pretty error messages from PHPMailer
		} catch (Exception $e) {
			echo $e->getMessage(); //Boring error messages from anything else!
		}
	}



}
