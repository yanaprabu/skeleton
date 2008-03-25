<?php

function a_autoload($class) {
	$file = str_replace(array('_','-'), array('/','_'), $class) . '.php';
	if (file_exists($file)) {
		include($file);
	}
}

spl_autoload_register('a_autoload');
