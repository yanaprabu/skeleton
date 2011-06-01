<?php
/**
 * Ini.php
 *
 * @package  A_Config
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Config_Ini
 * 
 * Support for reading INI configuration files
 */
class A_Config_Ini extends A_Config_Base {

	protected function _loadFile() {
		$data = parse_ini_file($this->_filename, true);
		return $data;
	}
}