#!/usr/bin/env php
<?php

error_reporting(E_ALL & ~E_NOTICE);

if (php_sapi_name() != 'cli') {
	echo "Error: this script can only be run from the command-line.\n";
	die();
}
if ($_SERVER["argv"][1] == '') {
	echo "
Usage: " . basename($_SERVER["argv"][0]) . " <path-to-bootstrap> <path-to-script> [force] [nobuffer]

";
exit;
}

$pathToBootstrap = realpath($_SERVER["argv"][1]);
$pathToScript = realpath($_SERVER["argv"][2]);
$forceParam = $_SERVER["argv"][3];
$noBufferParam = $_SERVER["argv"][4];

if ($forceParam == 'force') {
	$force = true;
} else {
	$force = false;
}

if ($noBufferParam == "nobuffer") {
	$nobuffer = true;
} else {
	$nobuffer = false;
}

if (! file_exists($pathToBootstrap)) {
	echo "Fatal error: bootstrap.php not found at '{$pathToBootstrap}'\n";
	die();
}

if (! file_exists($pathToScript)) {
	echo "Fatal error: script not found at '{$pathToScript}'\n";
	die();
}

$clsName = str_replace("class.", "", basename($pathToScript, ".php"));

require_once $pathToBootstrap;
require_once $pathToScript;


if (class_exists($clsName)) {
	$obj = $clsName::getInstance();
	if ($force == true) {
		echo "Script dispatch attempt (forced): " . APP_NAME . ": {$clsName}\n";
	} else {
		echo "Script dispatch attempt: " . APP_NAME . ": {$clsName}\n";
	}
	if ($clsName::isDue() || $force == true) {
		if ($force == true) {
			echo "---> Force override: script WILL execute.\n";
		}
		if (! $nobuffer) {
			ob_start();
		}
		$id = $clsName::logRunStart();
		$clsName::exec();
		if (! $nobuffer) {
			$output = ob_get_flush();
		} else {
			$output = '';
		}
		$clsName::logRunEnd($id, $output);
	}
} else {
	echo "Fatal error: Class '{$clsName}' does not exist.\n";
	exit;
}



