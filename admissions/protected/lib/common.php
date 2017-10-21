<?php 

	function pr($var) {
	     echo '<pre>';
	 	 print_r($var);
	     echo '</pre>';
	}
	
	function is_php($version = '5.0.0') {
		static $is_php;
		$version = (string)$version;
		
		if (! isset($_is_php[$version])) {
			$_is_php[$version] = (version_compare(PHP_VERSION, $version) < 0) ? FALSE : TRUE;
		}
		
		return $_is_php[$version];
	}

 ?>