<?php
/**
 * Support for reading INI configuration files
 *
 * @package A_Config
 */

class A_Config_Ini extends A_Config_Abstract {

	protected function _loadFile() {
		$data = parse_ini_file($this->_filename, true);
		return $data;
	}
}