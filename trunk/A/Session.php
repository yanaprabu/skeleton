<?php
/**
 * Encapsulate session data
 * 
 * @package  A
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Session
 *
 * This class provides various utility functions pertaining to the user session
 */
class A_Session {
	protected $_data = array();
	protected $_a_namespace = 'A_Session';
	protected $_namespace;
	protected $_regenerate;
	protected $_isstarted = false;
	protected $_p3p = '';
	
	/**
	 * __construct
	 *
	 * @param mixed $namespace ??? (optional)
	 * @param mixed $regenerate ??? (optional)
	 */
	public function __construct($namespace=null, $regenerate=false) {
		$this->initNamespace($namespace);
		$this->_regenerate = $regenerate;
	}
	
	/**
	 * initNamespace
	 *
	 * @param mixed $namespace ??? (optional)
	 * @return null
	 */
	public function initNamespace($namespace=null) {
		if ($namespace) {
			$this->_namespace = $namespace;
		}
		if (session_id() != '') {
			if ($this->_namespace) {
				if (! isset($_SESSION[$this->_namespace])) {
					$_SESSION[$this->_namespace] = array();
				}
				$this->_data =& $_SESSION[$this->_namespace];
			} else {
				$this->_data =& $_SESSION;
			}
			$this->_isstarted = true;	// already started
			$this->doExpiration();
		}
	}
	
	/**
	 * setHandler
	 *
	 * @param mixed $handler ???
	 */
	public function setHandler($handler) 
	{
		session_set_save_handler(array(&$handler, 'open'),
									array(&$handler, 'close'),
									array(&$handler, 'read'),
									array(&$handler, 'write'),
									array(&$handler, 'destroy'),
									array(&$handler, 'gc'));
		register_shutdown_function('session_write_close');
		
		//ensure the session is restarted after changing the save handler
		session_destroy();
		session_start();
		session_regenerate_id();

		return $this;
	}
	
	/**
	 * setP3P
	 *
	 * @param string $policy ??? (optional)
	 */
	public function setP3P($policy='P3P: CP="CAO PSA OUR"') {
		$this->_p3p = $policy;
	}
	
	/**
	 * start
	 *
	 * @return null
	 */
	public function start() {
		if (session_id() == '') {
			$msie = isset($_SERVER['HTTP_USER_AGENT']) ? strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE') : '';
			if ($msie) {
				session_cache_limiter('must-revalidate');
			}
			session_start();
			if ($msie && $this->_p3p) {
				header($this->_p3p);
			}
			$this->initNamespace();
			if ($this->_regenerate) {
				session_regenerate_id();
			}
		}
	}
	
	/**
	 * get
	 *
	 * @param mixed $name ???
	 * @param mixed $default ??? (optional)
	 */
	public function get($name, $default=null) {
		$this->start();
		return isset($this->_data[$name]) ? $this->_data[$name] : $default;
	}
	
	/**
	 * getRef
	 *
	 * @param mixed $name ??? (optional)
	 */
	public function & getRef($name=null) {
		$this->start();
		if ($name !== null) {
			if (! isset($this->_data[$name])) {
				$this->_data[$name] = array();
			}
			return $this->_data[$name];
		} else {
			return $this->_data;
		}
	}
	
	/**
	 * set
	 *
	 * @param mixed $name ???
	 * @param mixed $value ???
	 * @param integer $count ??? (optional)
	 * @return A_Session This object instance
	 */
	public function set($name, $value, $count=0) {
		if ($name) {
			$this->start();
			if ($value !== null) {
				$this->_data[$name] = $value;
			} elseif ($this->_namespace) {
				unset($_SESSION[$this->_namespace][$name]);
			} else {
				unset($_SESSION[$name]);
			}
			if ($count > 0) {
				$this->expire($name, $count);
			}
		}
		return $this;
	}
	
	/**
	 * has
	 *
	 * @param mixed $name ???
	 * @return boolean True if session contains that data.  False otherwise
	 */
	public function has($name) {
		$this->start();
		return isset($this->_data[$name]);
	}
	
	/**
	 * __get
	 *
	 * @param mixed $name ???
	 * @return mixed The object in the session with the key $name
	 */
	public function __get($name) {
		return $this->get($name);
	}
	
	/**
	 * __set
	 *
	 * @param mixed $name ???
	 * @param mixed $value ???
	 * @return boolean True if successful
	 */
	public function __set($name, $value) {
		return $this->set($name, $value);
	}
	
	/**
	 * expire
	 *
	 * @param mixed $name The key to set expiration for
	 * @param mixed $count The expiration to set (optional)
	 */
	public function expire($name, $count=0) {
		$this->start();
		$_SESSION[$this->_a_namespace]['expire'][$name] = $count;
	}
	
	/**
	 * doExpiration
	 *
	 * @return null
	 */
	protected function doExpiration() {
		if (isset($_SESSION[$this->_a_namespace]['expire'])) {
			foreach ($_SESSION[$this->_a_namespace]['expire'] as $name => $value) {
				if ($value > 0) {
					--$_SESSION[$this->_a_namespace]['expire'][$name];		// decrement counter if > 1
				} else {
					unset($this->_data[$name]);								// remove session var
					unset($_SESSION[$this->_a_namespace]['expire'][$name]);	// remove counter
				}
			}
		}
	}
	
	/**
	 * close
	 *
	 * @return null
	 */
	public function close() {
		session_write_close();
	}
	
	/**
	 * destroy
	 *
	 * @return null
	 */
	public function destroy() {
		if ($this->_namespace) {
			$_SESSION[$this->_namespace] = array();
		} else {
			$_SESSION = array();
		}
	}
	
}
