<?php

class URL {

	function URL($script='') {
		if ($_SERVER['HTTPS'] == 'on') {
			$protocol = 'https';
		} else {
			$protocol = 'http';
		}
		$url = $protocol . '://' . $_SERVER['HTTP_HOST'];
		if ($script) {
			$url .= $script;
		} else {
			$url .= $_SERVER['SCRIPT_NAME'];
			if ($_SERVER['PATH_INFO']) {
				$url .= $_SERVER['PATH_INFO'];
			}
		}
		return $url;
	}

}

?>