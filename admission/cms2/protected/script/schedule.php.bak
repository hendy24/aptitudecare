#!/usr/bin/env php
<?php
error_reporting(E_ALL & ~E_NOTICE);
if (php_sapi_name() != 'cli') {
	echo "Error: this script can only be run from the command-line.\n";
	die();
}

if ($_SERVER["argv"][1] == '') {
	echo "
Usage: " . $_SERVER["argv"][0] . " <dir> [force]

Where `dir' is the path to one of the following:
- A directory containing one or more CMSv2 site installation directories
- A single CMSv2 site installation directory

";
exit;
}

$argDir = realpath($_SERVER["argv"][1]);
$forceParam = $_SERVER["argv"][2];

// prepare an associate array of sites, the path to their bootstrap file, and to their script directories
$sites = array();
if (isSiteDir($argDir)) {
	$siteName = basename($argDir);
	$sites[$siteName] = array(
		"bootstrap" => "{$argDir}/bootstrap.php",
		"env" => "{$argDir}/env.php",
		"script" => "{$argDir}/protected/script"
	);
} else {
	checkDir($argDir);
}
if (count($sites) > 0) {
	// cycle through sites in the installation dir
	foreach ($sites as $site => $info) {
		// ignore the "_skel" site, it's not a real site.
		if ($site == "_skel")
			continue;
		
		// make sure the bootstrap file and script directory actually exist
		if (file_exists($info["bootstrap"]) && is_dir($info["script"])) {
			// cycle through the scripts in the script directory
			$d = dir($info["script"]);
			while (false !== ($entry = $d->read())) {
				// ignore anything that doesn't look like a real script class file
				if ($entry == '..' || $entry == '.') {
					continue;
				} elseif (! preg_match("/^class/", $entry)) {
					continue;
				} else {
					// construct the system command for dispatching this script
					$cmdRun =  '/usr/bin/env php ' . dirname(__FILE__) . "/dispatch.php " . $info["bootstrap"] . " " . $info["script"] . "/{$entry} {$forceParam}";
					
					// shell out to run the script
					system($cmdRun, $retval);
				}
			}
		}
		
		// now that scripts are all dispatched for this site, start looking for scripts in that site's CMS engine directory.
		
		// import just the environment info for this site, so we can find its CMS engine
		if (file_exists($info["env"])) {
			require_once $info["env"];
			
			$d = dir(ENGINE_PROTECTED_PATH . "/script_allsites");
			while (false !== ($entry = $d->read())) {
				// ignore anything that doesn't look like a real script class file
				if ($entry == '..' || $entry == '.') {
					continue;
				} elseif (! preg_match("/^class/", $entry)) {
					continue;
				} else {
					// construct the system command for dispatching this script
					$cmdRun =  '/usr/bin/env php ' . dirname(__FILE__) . "/dispatch.php " . $info["bootstrap"] . " " . ENGINE_PROTECTED_PATH . "/script_allsites/{$entry} {$forceParam}";
					
					// shell out to run the script
					system($cmdRun, $retval);
				}
			}
		}
		
		
	}
}

function checkDir($dir) {
	global $sites;
	$d = dir($dir);
	while (false !== ($entry = $d->read())) {
		if ($entry == '..' || $entry == '.') {
			continue;
		} else {
			if (isSiteDir("{$dir}/{$entry}")) {
				$siteName = basename("{$dir}/{$entry}");
				$sites[$siteName] = array(
					"bootstrap" => "{$dir}/{$entry}/bootstrap.php",
					"env" => "{$dir}/{$entry}/env.php",
					"script" => "{$dir}/{$entry}/protected/script"
				);
			}
		}
	}
	$d->close();
}

function isSiteDir($dir) {
	if (file_exists("{$dir}/bootstrap.php")) {
		return true;
	}
	return false;
}

