<?php

$smarty->registerPlugin("function", "img", "smarty_img");
function smarty_img($params, &$smarty) {
	$relpath = $params['image'];
	
	$sourceProtected = (isset($params['sourceProtected']) && $params['sourceProtected'] === true) ? true : false;
	$cacheProtected = $params['cacheProtected'];
	$maxWidth = (isset($params['maxWidth']) && $params['maxWidth'] != '') ? $params['maxWidth'] : false;
	$maxHeight = (isset($params['maxHeight']) && $params['maxHeight'] != '') ? $params['maxHeight'] : false;
	$maxArea = (isset($params['maxArea']) && $params['maxArea'] != '') ? $params['maxArea'] : false;
	$crop = (isset($params['crop']) && $params['crop'] != '' && $params['crop'] == '1') ? 1: 0;
	$quality = (isset($params['quality']) && $params['quality'] != '') ? $params['quality'] : 0;
	$greyscale = (isset($params['greyscale']) && $params['greyscale'] != '' && $params['greyscale'] == '1') ? 1 : 0;
	$canvasWidth = (isset($params['canvasWidth']) && $params['canvasWidth'] != '') ? $params['canvasWidth'] : false;
	$canvasHeight = (isset($params['canvasHeight']) && $params['canvasHeight'] != '') ? $params['canvasHeight'] : false;
	$canvasBackgroundColor = (isset($params['canvasBackgroundColor']) && $params['canvasBackgroundColor'] != '') ? $params['canvasBackgroundColor'] : '#ffffff';
	$force = (isset($params['force']) && $params['force'] != '') ? $params['force'] : false;
	$forceCache = (isset($params['forceCache']) && $params['forceCache'] != '') ? $params['forceCache'] : false;
	$outputFormat = (isset($params['outputFormat']) && $params['outputFormat'] != '') ? $params['outputFormat'] : 'jpg';
	
	if ($outputFormat != 'png' && $outputFormat != 'jpg' && $outputFormat != 'gif') {
		$outputFormat = 'jpg';
	}
	
	if ($force == true) {
		$forceCache = true;
	}
	
	//if (DEVELOPMENT == true) {
	//	$force = true;
	//}
	
	
	if ($sourceProtected) {
		// if you haven't specified otherwise in calling code, protected source files end up in protected cache and
		// must be read via PHP
		if (! isset($cacheProtected)) {
			$cacheProtected = true;
		}
		
	} else {
		// if you haven't specified otherwise in calling code, public source files end up in public cache and
		// may be called directly by URL
		if (! isset($cacheProtected)) {
			$cacheProtected = false;
		}
	}
	
		
	$image = new Image_Cache($relpath, $sourceProtected, $cacheProtected);
	$image->setOutputFormat($outputFormat);
	$image->setParams(array(
		'maxWidth' => $maxWidth,
		'maxHeight' => $maxHeight,
		'maxArea' => $maxArea,
		'crop' => $crop,
		'forceCache' => $forceCache,
		'quality' => $quality,
		'canvasWidth' => $canvasWidth,
		'canvasHeight' => $canvasHeight,
		'canvasBackgroundColor' => $canvasBackgroundColor
	));
	
	if ($sourceProtected) {
		$image->authorizeProtectedImage();
	}
	
	// Don't execute if cache protected, because Image_Cache::url() is just going to return a URI
	// to image.php which will
	// result in the filesystem getting hit twice: once by PHP when Smarty parses this call, and again when
	// the browser makes the subsequent call to image.php
	if ($image->fileInCache()) {
		if (! $cacheProtected) {
			$image->execute();	
		}
	}
	
	return $image->url();
	
}

// Loads from CMS_Content
$smarty->registerPlugin("function", "loadContent", "smarty_load_content");
$smarty->registerPlugin("function", "load_content", "smarty_load_content");
function smarty_load_content($params, &$smarty) {
	$obj = new CMS_Content($params['name']);
	if (! $obj->valid() ) {
		unset($obj);
		$obj = new CMS_Content;
		$obj->name = $params['name'];
		if ($params['title'] == '') {
			$obj->title = ucfirst(str_replace("_", " ", $params['name']));
		} else {
			$obj->title = $params['title'];
		}
		if ($params['seed'] != '') {
			$obj->content = $params['seed'];
		}
		$obj->save();
	}
	if ($params['assign'] != '') {
		$smarty->assign($params['assign'], $obj);
	} else {
		return $obj->content;
	}
}

// Loads from CMS_Simple_Content
$smarty->registerPlugin("function", "loadSimple", "smarty_load_simple");
function smarty_load_simple($params, &$smarty) {
	/*
	$obj = new CMS_Simple_Content($params['name']);
	if (! $obj->valid() ) {
		unset($obj);
		$obj = new CMS_Simple_Content;
		$obj->name = $params['name'];
		if ($params['title'] == '') {
			$obj->title = ucfirst(str_replace("_", " ", $params['name']));
		} else {
			$obj->title = $params['title'];
		}
		if ($params['seed'] != '') {
			$obj->value = $params['seed'];
		}
		$obj->save();
	}
	*/
	$obj = CMS_Simple_Content::loadSimple($params['name'], $params['title'], $params['seed']);
	if (isset($params['assign']) && $params['assign'] != '') {
		$smarty->assign($params['assign'], $obj);
	} else {
		return $obj->value;
	}
}

$smarty->registerPlugin("modifier", "datetime_format", "smarty_datetime_format");
function smarty_datetime_format($datetime) {
	return datetime_format($datetime);
}

// More advanced, and preferred, method of content loading
/*
$smarty->registerPlugin("block", "cms", "smarty_cms", $cacheable = true, $cache_attr = array());
function smarty_cms($params, $content, &$smarty) {
	$obj = new CMS_Content;
	if ($params['name'] != '') {
		$obj->name = $params['name'];
	} else {
		$page = $smarty->tpl_vars['page']->value;
		for ($i=1; $i<20; $i++) {
			$_name = "{$page}_{$i}";
			if (! $obj->nameIsUsed($_name)) {
				$obj->name = $_name;
				break;
			}
		}
	}

	if ($params['title'] != '') {
		$obj->title = $params['title'];
	} else {
		$obj->title = ucfirst($obj->name);
	}

	$obj->content = $content;
	$obj->save();


}
 * 
 */
$smarty->registerPlugin("block", "onLoad", "smarty_onload", true, array());
function smarty_onload($params, $contents, &$smarty, &$repeat) {
	if (! isset($GLOBALS[APP_NAME]["onLoad"])) {
		$GLOBALS[APP_NAME]["onLoad"] = "";	
	}
	$GLOBALS[APP_NAME]["onLoad"] .= $contents ;
}
 
$smarty->registerPlugin("block", "head", "smarty_head", true, array());
function smarty_head($params, $contents, &$smarty, &$repeat) {
	if (! isset($GLOBALS[APP_NAME]["head"])) {
		$GLOBALS[APP_NAME]["head"] = "";	
	}
	$GLOBALS[APP_NAME]["head"] .= $contents ;
}
$smarty->registerPlugin("block", "foot", "smarty_foot", true, array());
function smarty_foot($params, $contents, &$smarty, &$repeat) {
	if (! isset($GLOBALS[APP_NAME]["foot"])) {
		$GLOBALS[APP_NAME]["foot"] = "";	
	}
	$GLOBALS[APP_NAME]["foot"] .= $contents ;
}
$smarty->registerPlugin("block", "jQueryReady", "smarty_jQueryReady", true, array());
function smarty_jQueryReady($params, $contents, &$smarty, &$repeat) {
	if (! isset($GLOBALS[APP_NAME]["jQueryReady"])) {
		$GLOBALS[APP_NAME]["jQueryReady"] = "";	
	}
	$GLOBALS[APP_NAME]["jQueryReady"] .= $contents ;
}

$smarty->registerPlugin("block", "javascript", "smarty_javascript", true, array());
function smarty_javascript($params, $contents, &$smarty, &$repeat) {
	if (! isset($GLOBALS[APP_NAME]["javascript"])) {
		$GLOBALS[APP_NAME]["javascript"] = "";	
	}
	$GLOBALS[APP_NAME]["javascript"] .= $contents ;
}

$smarty->registerPlugin("block", "javascriptBottom", "smarty_javascript_bottom", true, array());
function smarty_javascript_bottom($params, $contents, &$smarty, &$repeat) {
	if (! isset($GLOBALS[APP_NAME]["javascriptBottom"])) {
		$GLOBALS[APP_NAME]["javascriptBottom"] = "";	
	}
	$GLOBALS[APP_NAME]["javascriptBottom"] .= $contents ;
}

//Turns the HTML between {htmlToText}....{/htmlToText} into plain text.
$smarty->registerPlugin("block", "htmlToText", "smarty_htmlToText", true, array());
function smarty_htmlToText($params, $contents, &$smarty, &$repeat) {
	//echo "Called with repeat={$repeat}";
	if ($repeat == true) {
		$smarty->assign("isText", true);
	} else {
		$smarty->assign("isText", false);
	}
	return htmlToText($contents);
}

// Renders a widget.
$smarty->registerPlugin("function", "render_widget", "smarty_render_widget");
function smarty_render_widget($params, &$smarty) {
	$obj = $params['record'];
	$field = $params['field'];
	if ($obj != '' && $field != '') {
		return $obj->meta()->render($field);
	} else {
		return false;
	}
}

// Call this at the top of your page templates
$smarty->registerPlugin("function", "set_title", "smarty_set_title");
$smarty->registerPlugin("function", "setTitle", "smarty_set_title");
function smarty_set_title($params, &$smarty) {
	$GLOBALS[APP_NAME]["PAGE_TITLE"] = $params['title'];
}

// Takes a string dollar value and makes it pretty for the screen - commas and such
$smarty->registerPlugin("modifier", "money", "smarty_money");
function smarty_money($dollars) {
	return number_format($dollars, 2, '.', ',');
}


$smarty->registerPlugin("function", "setURLVar", "smarty_setURLVar");
function smarty_setURLVar($params, &$smarty) {
	$url = setURLVar($params['url'], $params['var'], $params['value']);
	if (isset($params['assign']) && $params['assign'] != '') {
		$smarty->assign($params['assign'], $url);
	} else {
		return $url;
	}
}


// Outputs hidden form elements that enable history for the Input and Feedback classes
// You must supply, at the very least, a value for @name.
$smarty->registerPlugin("function", "formhistory_on", "smarty_form_history_on");
function smarty_form_history_on($params, &$smarty) {
	if ($params["name"] == '') {
		return "";
	}

	/*
	if ($params["page"] == '') {
		$page = $smarty->tpl_vars['page']->value;
	} else {
		$page = $params['page'];
	}
	if ($params["action"] == '') {
		$action = $smarty->tpl_vars['action']->value;
	} else {
		$action = $params['action'];
	}
	*/

	//$_history = (isset($smarty->tpl_vars['_history']->value)) ? $smarty->tpl_vars['_history']->value : array();
	//$_history[$params['name']] = input()->getHistory($params["name"]);
	//$smarty->assign("_history", $_history);

	// if an error was encountered and reported in userland, assign() the contents
	// of the form that caused that error to Smarty so it can be accessed (presumably in
	// the value="" attributes of the form elements)

	$str = "";
	$str .= "<input type=\"hidden\" name=\"_history\" value=\"true\" />\n";
	$str .= "<input type=\"hidden\" name=\"_history_name\" value=\"{$params['name']}\" />\n";
	//$str .= "<input type=\"hidden\" name=\"_history_page\" value=\"{$page}\" />\n";
	//$str .= "<input type=\"hidden\" name=\"_history_action\" value=\"{$action}\" />\n";
	foreach ($params as $key => $value) {
		if ($key == 'name') continue;
		$str .= "<input type=\"hidden\" name=\"_history_{$key}\" value=\"{$value}\" />\n";
	}
	return $str;
}

$smarty->registerPlugin("function", "hardspace", "smarty_hardspace") ;
function smarty_hardspace($params, &$smarty) {
	$str = "";
	if ($params['count'] != '' && is_numeric($params['count']) && $params['count'] > 0) {
		for ($i=0; $i<$params['count']; $i++) {
			$str .= "&nbsp;";
		}
	}
	return $str;
}

