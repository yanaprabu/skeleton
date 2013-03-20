<?php
/**
 * Php.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Config_Php
 *
 * Support storing configuration data in a PHP file.  File must contain a variable named $ConfigArray containing config data
 *
 * @package A_Config
 */
class A_Config_Php extends A_Config_Base
{

	protected function _loadFile($config_array_name='ConfigArray')
	{
		include $this->_filename;
		return isset($$config_array_name) ? $$config_array_name : array();
	}

}
