<?php
/**
 * Provide
 * @author Christopher Thompson
 *
 */
class A_Db_Config_Masterslave {

	/**
	 * User-provided configuration
	 * @var array
	 */
	protected $_config = array();
	

	/**
	 * convert connnect keys based on this table
	 * @var array
	 */
	protected $_sql_to_name = array(
									''=>'slave', 
									'INSERT'=>'master',
									'UPDATE'=>'master',
									'DELETE'=>'master',
									'START'=>'master',
									'SAVEPOINT'=>'master',
									'COMMIT'=>'master',
									'ROLLBACK'=>'master',
									);

	protected $_choose_config_func;									

	/**
	 * Database connection
	 * @var object|resource|null
	 */
	protected $_connection = array();
	
	/**
	 *
	 * @param  array|A_Config $config
	 * @throws A_Db_Exception
	 */
	public function __construct($config=array()) {
		if ($config) {
			$this->config($config); 
		}
		$this->_choose_config_func = array($this, 'chooseRand');
	}

	/**
	 *
	 */
	public function config($config) {
		if (is_object($config) && method_exists($config, 'toArray')) {
			$config = $config->toArray();
		}
		if (is_array($config)) {
			$this->_config  = $config; 
#echo '_config: <pre>' . print_r($config, 1) . "</pre>\n";
		}			
	}

	/**
	 *
	 */
	public function getConfig($name='') {
		if ($name == '') {
			$name = $this->_sql_to_name[''];						// get default connection name
		}
echo "A_Db_Config_Masterslave::getConfig name=$name<br/>";
		if (isset($this->_config[$name])) {
			return array('name'=>$name, 'data'=>$this->chooseConfigData($name));
		} else {
			return array('name'=>'', 'data'=>array());
		}
	}

	/**
	 *
	 */
	public function getConfigName($sql='') {
		$name = $this->_sql_to_name[''];							// get default connection name
		if ($sql) {
			$pos = strpos($sql, ' ', 0);
			if ($pos === false) {
				$pos = strlen($sql);
			}
			if ($pos || strlen($sql)) {
				$keyword = strtoupper(substr($sql, 0, $pos));
echo "A_Db_Config_Masterslave::getConfigBySql keyword=$keyword<br/>";
				if (isset($this->_sql_to_name[$keyword])) {		// is there a connection name for this SQL keyword?
					$name = $this->_sql_to_name[$keyword];
				}
			}
		}
		return $name;
	}

	/**
	 *
	 */
	public function chooseConfigData($name='', $number=null) {
		if ($name) {
			if ($number === null) {
				return call_user_func($this->_choose_config_func, $this->_config[$name]);
			} elseif (isset($this->_config[$name][$number])) {
				return $this->_config[$name][$number];
			}
		}
	}

	/**
	 *
	 */
	public function setChooseConfigFunc($func) {
		$this->_choose_config_func = $func;
	}

	/**
	 *
	 */
	public function chooseRand($config) {
		if (is_array($config)) {
			$number = rand(0, count($config)-1);
			if (isset($config[$number])) {
				return $config[$number];
			}
		}
		return array();
	}

	/**
	 *
	 */
	public function chooseNext($config) {
		if (is_array($config)) {
			return each($config);
		}
		return array();
	}

	/**
	 *
	 */
	public function setSqlMapping($sql, $name) {
	}

}
