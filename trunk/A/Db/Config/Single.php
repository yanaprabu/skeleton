<?php
/**
 * Single.php
 *
 * @package  A_Db
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Db_Config_Single
 * 
 * This is the default connnection configuration class.  This class can be replaceable with a class that provides other connection functionality, like master/slave support.  See A_Db_Config_* for other options. 
 */
class A_Db_Config_Single {
	/**
	 * User-provided configuration data
	 * @var array
	 */
	protected $_config = array();
	
	/**
	 *
	 * @param  array|A_Config $config
	 * @throws A_Db_Exception
	 */
	public function __construct($config=array()) {
		if ($config) {
			$this->config($config); 
		}
	}

	/**
	 * Set config array directly. Containers are converted to an array.
	 */
	public function config($config) {
		if (is_object($config) && method_exists($config, 'toArray')) {
			$config = $config->toArray();
		}
		if (is_array($config)) {
			$this->_config  = $config; 
		}			
	}

	/**
	 * Method called by connect() to get config data
	 */
	public function getConfig($sql='') {
		if (isset($this->_config)) {
			return array('name'=>'', 'data'=>$this->_config);
		} else {
			return array('name'=>'', 'data'=>array());
		}
	}

	/**
	 * Method called by query(), etc. to get config data
	 */
	public function getConfigName($sql='') {
		return '';
	}

}

