<?php

	// Scan the module directory for use below
	$module_dir = preg_grep('/^([^.])/', scandir(MODULES_DIR));
	// Set module variable to false
	$module = false;

	// If the variables are available from a post request, use them...
	if (isset(input()->module)) {
		// Set module
		$module = input()->module;

		// if the user is trying to access the admission module then send the user info to the single_sign_on page
		// this functionality will be removed after the admission application is re-built in the new framework
		if ($module == "Admission") {
			header("Location: " . SITE_URL . "/admission/?page=login&action=single_sign_on&user=" . auth()->getRecord()->public_id);
			exit;
		}

		// Set the page (or controller)
		if (!isset (input()->page)) {
			$page = $module;
		} else {
			$page = ucfirst(camelizeString(input()->page));
		}


		// Set the controller action
		if (isset(input()->action)) {
			// this is the controller method
			$action = input()->action;
			$camelizedAction = camelizeString($action);
			$underscored_action = underscoreString($action);
		} else {
			$camelizedAction = 'index';
		}

	} elseif (isset (input()->page)) {
		$page = ucfirst(camelizeString(input()->page));

		if (isset (input()->action)) {
			$action = input()->action;
			$camelizedAction = camelizeString($action);
			$underscored_action = underscoreString($action);
		} else {
			$camelizedAction = 'index';
		}
	} elseif (! strstr ($request, '?')) {
		$queryString = explode('/', $request);

		// First check if the first item in the query string is a module
		$queryItem1 = ucfirst(camelizeString($queryString[1]));

		// If the query string is in the modules directory array, we know it is a module...
		if (in_array($queryItem1, $module_dir)) {
			$module = $queryItem1;
		}

		// If the module variable is empty then we are at a global page (login, corporate overview, etc.) and are not utilizing
		// any module controllers or methods.
		if ($module == '') {
			if  (count ($queryString) > 2) {
				// The first item in the query string will be the controller (page).  We will make it camel cased, remove any hyphens,
				// and capitalize the first letter in the string.
				$page = ucfirst(camelizeString($queryString[1]));

			} else {
				$page = 'Login';

			}
			// If there is a second item in the array then this is the controller method (action)
			if (isset ($queryString[2])) {
				$camelizedAction =  camelizeString($queryString[2]);
				$underscored_action = underscoreString($queryString[2]);
			} else {
				$action = 'index';
			}

		/*
		 *	If we are working with a module controller we need to look in the module sub-directory for the controller and views.
		 */

		} else {
			// need to see if there is a 3rd item in the array, if not, then use the default controller...
			if (isset ($queryString[3])) {
				$camelizedAction =  camelizeString($queryString[3]);
				$underscored_action = underscoreString($queryString[3]);
			} else {
				// set controller to be the same as the module
				$page = ucfirst(camelizeString($queryString[2]));
				if (isset($queryString[3])) {
					$camelizedAction = camelizeString($queryString[3]);
					$underscored_action = underscoreString($queryString[3]);
				} else {
					$camelizedAction = 'index';
				}
			}


		}


	/*
	 * 	If there are no variables set and there is a ? in the URL string then...
	 */

	} else {
		$page = 'MainPage';
		$camelizedAction = 'index';

	}


	/*
	 * -------------------------------------------
	 * CREATE ROUTES FOR SPECIFIC URL'S
	 * -------------------------------------------
	 *
	 */

	 // route $SITE_URL/menu
		if ($request == "/menu") {
			$module = "Dietary";
			$page = "Public";
			$action = "index";
		}


	/*
	 * -------------------------------------------
	 * INSTANTIATE THE CONTROLLER CLASS
	 * -------------------------------------------
	 *
	 */

	 	// If the module variable is not empty we can look directly into that controller directory
	 	if ($module) {
		 	foreach ($module_dir as $dir) {
				if (file_exists(MODULES_DIR . DS . $dir . DS . 'Controllers' . DS . $page.'Controller.php')) {
					include_once(MODULES_DIR . DS . $dir . DS . 'Controllers' . DS . $page.'Controller.php');
				}
			}
		//	Next, look in the protected directory for the controller.
	 	} elseif (file_exists (APP_PROTECTED_DIR . DS . 'Controllers' . DS . $page.'Controller.php')) {
			include_once (APP_PROTECTED_DIR . DS . 'Controllers' . DS . $page.'Controller.php');
		} elseif (file_exists (MODULES_DIR . DS . $module . DS . 'Controllers' . DS . $page.'Controller.php')) {
			// Loop through the modules to look for the controller.
			include_once(MODULES_DIR . DS . $module . DS . 'Controllers' . DS . $page.'Controller.php');
		}
		
		$className = $page.'Controller';

		// If the class exists, instantiate it and load the coorespondig view from the Views folder. Otherwise, load the
		// error page.
		
		if (class_exists($className)) {
			$controller = new $className;
			$controller->page = underscoreString($page);
			$controller->module = $module;

			// Check the camelized, underscored, and action variables for the method within the class
			if (method_exists($controller, $camelizedAction)) {
				$controller->action = $camelizedAction;
				$controller->loadView();
			} elseif (method_exists($controller, $underscored_action)) {
				$controller->action = $underscored_action;
				$controller->loadView();
			} elseif (method_exists($controller, $action)) {
				$controller->action = $action;
				$controller->loadView();
			} else {
				$controller = new ErrorController();
				// If it does not exist load the default error view
				$controller->page = "error";
				$controller->action = "index";
				$controller->loadView();
			}

		} else {  // If there is not a matching class redirect to the home page.
			$controller = new MainController();
			$controller->redirect();
		}
		exit;
