<?php

class A_Exception {

	public function getInstance($class_or_obj, $message) {
		$obj = null;
		if (is_string($class_or_obj)) {
			if (! class_exists($class_or_obj)) {
				$file_name = str_replace('_', '/', $class_or_obj) . '.php';
				include $file_name;
			}
			if (class_exists($class_or_obj)) {
				$obj = new $class_or_obj($message);
			}
		} elseif ($class_or_obj instanceof Exception) {
			return $class_or_obj;
		}
		return $obj;
	}

}