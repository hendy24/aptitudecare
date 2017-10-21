<?php

$smarty->registerFilter("pre", "smarty_prefilter_gA");
function smarty_prefilter_gA($tpl_output, &$smarty) {
	if (defined('GOOGLE_ANALYTICS') && GOOGLE_ANALYTICS != '') {
		if (DEVELOPMENT == true) {
			$openComment = '/* 
DEVELOPMENT OR STAGING
';
		$closeComment = '*/';
		} else {
			$openComment = '';
			$closeComment = '';
		}
		$gaCode = "
<script type='text/javascript'>
{$openComment}
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '" . GOOGLE_ANALYTICS . "']);
  _gaq.push(['_trackPageview']);

  (function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
{$closeComment}
</script>";
		
		
		$tpl_output = str_replace("</body>", "\n" . $gaCode . "\n" . "</body>", $tpl_output);
	}
	return trim($tpl_output);
}

$smarty->registerFilter("output", "smarty_outputfilter_compact");
function smarty_outputfilter_compact($tpl_output, &$smarty) {
	return preg_replace("/\n{2,}/", "\n", $tpl_output);
}

$smarty->registerFilter("output","smarty_outputfilter_title");
function smarty_outputfilter_title($tpl_output, &$smarty) {
	$title = strip_tags($GLOBALS[APP_NAME]["PAGE_TITLE"]);
	$title = substr($title, 0, 60);
	if (trim($title) == '') {
		$tpl_output = preg_replace("/<_TITLE_>/", "", $tpl_output);
	} else {
		$tpl_output = preg_replace("/<_TITLE_>/", "<title>$title</title>", $tpl_output);
	}
	return trim($tpl_output);
}

$smarty->registerFilter("output","smarty_outputfilter_javascript");
function smarty_outputfilter_javascript($tpl_output, &$smarty) {
	if (MINIFY_JS == TRUE) {
		try {
			$jsCode = JSMin::minify($GLOBALS[APP_NAME]["jQueryReady"]);
		} catch (JSMinException $e) {
			$JsCode = $GLOBALS[APP_NAME]["jQueryReady"];
		}
	} else {
		$jsCode = $GLOBALS[APP_NAME]["jQueryReady"];
	}
	
	// jQuery().ready()
	$code = "jQuery().ready(function() {
" . $jsCode . "
});";
	$tpl_output = preg_replace("/<JQUERY_READY>/", $code, $tpl_output);
	
	
	// onload	
	if (MINIFY_JS == TRUE) {
		try {
			$jsCode = JSMin::minify($GLOBALS[APP_NAME]["onLoad"]);
		} catch (JSMinException $e) {
			$JsCode = $GLOBALS[APP_NAME]["onLoad"];
		}
	} else {
		$jsCode = $GLOBALS[APP_NAME]["onLoad"];
	}
	
	// jQuery().ready()
	$code = "jQuery(window).load(function() {
" . $jsCode . "
});";
	$tpl_output = preg_replace("/<JQUERY_ONLOAD>/", $code, $tpl_output);	
	

	// vanilla javascript
	if (MINIFY_JS == TRUE) {
		try {
			$jsCode = JSMin::minify($GLOBALS[APP_NAME]["javascript"]);
		} catch (JSMinException $e) {
			$JsCode = $GLOBALS[APP_NAME]["javascript"];
		}
	} else {
		$jsCode = $GLOBALS[APP_NAME]["javascript"];
	}
	$tpl_output = preg_replace("/<JAVASCRIPT>/",$jsCode, $tpl_output);
	
	// bottom-loading javascript
	if (MINIFY_JS == TRUE) {
		try {
			$jsCode = JSMin::minify($GLOBALS[APP_NAME]["javascriptBottom"]);
		} catch (JSMinException $e) {
			$jsCode = $GLOBALS[APP_NAME]["javascriptBottom"];			
		}
	} else {
		$jsCode = $GLOBALS[APP_NAME]["javascriptBottom"];
	}
	$code = '
<script type="text/javascript">
' . $jsCode . '
</script>';
	$tpl_output = preg_replace("/<JAVASCRIPT_BOTTOM>/",$code, $tpl_output);
	
	return trim($tpl_output);
}



$smarty->registerFilter("output","smarty_outputfilter_head");
function smarty_outputfilter_head($tpl_output, &$smarty) {
	$head = $GLOBALS[APP_NAME]["head"];
	if ($head == '') {
		$tpl_output = preg_replace("/<_HEAD_>/", "", $tpl_output);
	} else {
		$tpl_output = preg_replace("/<_HEAD_>/", $head, $tpl_output);
	}
	return trim($tpl_output);
}

$smarty->registerFilter("output","smarty_outputfilter_foot");
function smarty_outputfilter_foot($tpl_output, &$smarty) {
	$foot = $GLOBALS[APP_NAME]["foot"];
	if ($foot == '') {
		$tpl_output = preg_replace("/<_FOOT_>/", "", $tpl_output);
	} else {
		$tpl_output = preg_replace("/<_FOOT_>/", $foot, $tpl_output);
	}
	return trim($tpl_output);
}


//$smarty->registerFilter("output","smarty_outputfilter_cdn");
function smarty_outputfilter_cdn($tpl_output, &$smarty) {
	preg_match_all("/src=(?:\"|')((http:\/\/.*)\/(\?image=.*?))(?:\"|')/", $tpl_output, $matches);
	$replacements = array();
	foreach ($matches[1] as $imageURL) {
		$queryParts = parseQueryString(parse_url(html_entity_decode($imageURL), PHP_URL_QUERY));
		$image = new Image_Cache("public/" . $queryParts["image"]);
		if ($queryParts["max_width"] != '') {
			$image->setMaxWidth($queryParts["max_width"]);
		}
		if ($queryParts["max_height"] != '') {
			$image->setMaxHeight($queryParts["max_height"]);
		}
		if ($queryParts["max_area"] != '') {
			$image->setMaxArea($queryParts["max_area"]);
		}
		if ($queryParts["crop"] != '') {
			$image->setCrop($queryParts["crop"]);
		}
		if ($queryParts["quality"] != '') {
			$image->setQuality($queryParts["quality"]);
		}
		if ($queryParts["greyscale"] != '') {
			$image->setGreyscale($queryParts["greyscale"]);
		}
		$image->do_cache();
		$replacements[] = ENGINE_URL . "/image_cache/" . APP_NAME . $image->getRelativeCachePath();
		str_replace($matches[3], $replacements, $tpl_output);
	}
	$tpl_output = str_replace($matches[1], $replacements, $tpl_output);
	
	if (CDN_URL == '' || !defined('CDN_URL')) {
		return $tpl_output;
	}	
	if (CDN_URL != SITE_URL) {
		$SITE_URL = SITE_URL;
		$CDN_URL = CDN_URL;
		$tpl_output = str_replace("{$SITE_URL}/images/", "{$CDN_URL}/images/" , $tpl_output);
		$tpl_output = str_replace("{$SITE_URL}/assets/", "{$CDN_URL}/assets/" , $tpl_output);
		$tpl_output = str_replace("{$SITE_URL}/?image=", "{$CDN_URL}/?image=" , $tpl_output);
	}

	if (CDN_ENGINE_URL != ENGINE_URL && CDN_ENGINE_URL != CDN_URL) {
		$CDN_ENGINE_URL = CDN_ENGINE_URL;
		$ENGINE_URL = ENGINE_URL;
		$tpl_output = str_replace("{$ENGINE_URL}/images/", "{$CDN_ENGINE_URL}/images/" , $tpl_output);
		$tpl_output = preg_replace("/src=\"(.+)\/image_cache\/_tinymce_phpimage/", "src=\"$CDN_ENGINE_URL/${1}/image_cache/_tinymce_phpimage", $tpl_output);
	}

	return trim($tpl_output);
}



if (class_exists("tidy")) {
	//$smarty->registerFilter("output","smarty_outputfilter_tidy");
}
function smarty_outputfilter_tidy($tpl_output, &$smarty) {
	$tidy = new tidy();
	$config = array(
		'doctype' => 'loose',
		'anchor-as-name' => false,
		'indent' => true,
		'output-xhtml' => true,
		'wrap' => 0,
		'tab-size' => 4,
		'drop-empty-paras' => false,
		'literal-attributes' => true,
		'merge-divs' => false,
		'merge-spans' => false,
		'force-output' => true,
		'preserve-entities' => true,
		'fix-uri' => false
	);
	$tidy->parseString($tpl_output, $config, 'utf8');
	$tidy->cleanRepair();
	return $tidy;
	
}
