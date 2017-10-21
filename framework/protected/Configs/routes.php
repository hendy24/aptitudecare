<?php

/*
*
* -------------------------------------------------------------
* ROUTE TO THE CORRECT PAGE BASED ON URL
* -------------------------------------------------------------
*
* NOTE:  This routes file will handle urls like site_url/page/action or site_url?page=page&action=action.
*
*/

	// Get the requested URL
	$request = $_SERVER['REQUEST_URI'];

	$camelizedAction = '';
	$underscored_action = '';

	/*
	 * Check first for post variables which contain a page and action
	 */

if (strstr ($request, "?")) {
	if (isset (input()->page)) {
		$page = ucfirst(camelizeString(input()->page));
	} else {
		$page = "MainPage";
	}

	if (isset (input()->action)) {
		$action = input()->action;
		$camelizedAction = camelizeString($action);
		$underscored_action = underscoreString($action);
	} else {
		$action = "index";
		$camelizedAction = camelizeString($action);
		$underscored_action = underscoreString($action);
	}
} else {
	$query_string = explode("/", $request);

	// array item 1 is the page
	if (isset ($query_string[1]) && !empty ($query_string[1])) {
		$page = ucfirst(camelizeString($query_string[1]));
	} else {
		$page = "MainPage";
	}

	// array item 2 is the action
	if (isset ($query_string[2])) {
		$action = $query_string[2];
		$camelizedAction = camelizeString($query_string[2]);
		$underscored_action = underscoreString($query_string[2]);
	} else {
		$action = "index";
		$camelizedAction = camelizeString($action);
		$underscored_action = underscoreString($action);
	}
}

	// load the app_routes.php file for any app specific route functionality
	 require (APP_PROTECTED_DIR . '/Configs/app_routes.php');

	 if (file_exists (APP_PROTECTED_DIR . DS . 'Controllers' . DS . $page.'Controller.php')) {
	 	include_once (APP_PROTECTED_DIR . DS . 'Controllers' . DS . $page.'Controller.php');
	}
	$className = $page.'Controller';




	// If the class exists, instantiate it and load the coorespondig view from the Views folder. Otherwise, load the
	// error page.
	if (class_exists($className)) {
		$controller = new $className;
		$controller->page = underscoreString($page);

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
