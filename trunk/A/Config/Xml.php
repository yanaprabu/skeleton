<?php
/**
 * Xml.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Config_Xml
 * 
 * Support for reading XML configuration files
 * 
 * @package A_Config
 */
class A_Config_Xml extends A_Config_Base
{

	protected function _loadFile()
	{
		return simplexml_load_file($this->_filename);
	}

}
