<?php
/**
 * Encapsulate session data
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Session
 *
 * This class provides various utility functions pertaining to the user session
 *
 * @package A
 */
class A_Session
{

	protected $_a_namespace = 'A_Session';
	protected $_namespace;
	protected $_regenerate;
	protected $_isstarted = false;
	protected $_p3p = '';
	public $_debug = array();

	/**
	 * __construct
	 *
	 * @param mixed $namespace ??? (optional)
	 * @param mixed $regenerate ??? (optional)
	 */
	public function __construct($namespace=null, $regenerate=false)
	{
$this->_debug[] = 'headers_sent=' . headers_sent() . ', ' . '__construct()';
		$this->initNamespace($namespace);
		$this->_regenerate = $regenerate;
	}

	/**
	 * initNamespace
	 *
	 * @param mixed $namespace ??? (optional)
	 * @return null
	 */
	public function initNamespace($namespace=null)
	{
$this->_debug[] = 'headers_sent=' . headers_sent() . ', ' . 'initNamespace()';
		if ($namespace) {
			$this->_namespace = $namespace;
		}
		if (session_id() != '') {
			if ($this->_namespace) {
				if (! isset($_SESSION[$this->_namespace])) {
					$_SESSION[$this->_namespace] = array();
				}
			}
			$this->_isstarted = true;	// already started
			$this->doExpiration();
		}
	}

	/**
	 * setHandler
	 *
	 * @param mixed $handler ???
	 * @return $this
	 */
	public function setHandler($handler)
	{
		session_set_save_handler(
			array(&$handler, 'open'),
			array(&$handler, 'close'),
			array(&$handler, 'read'),
			array(&$handler, 'write'),
			array(&$handler, 'destroy'),
			array(&$handler, 'gc')
		);
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
	public function setP3P($policy='P3P: CP="CAO PSA OUR"')
	{
		$this->_p3p = $policy;
	}

	/**
	 * start
	 *
	 * @return null
	 */
	public function start()
	{
$this->_debug[] = 'headers_sent=' . headers_sent() . ', ' . 'start()';
		if (session_id() == '') {
			$msie = isset($_SERVER['HTTP_USER_AGENT']) ? strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE') : '';
$this->_debug[] = 'headers_sent=' . headers_sent() . ', ' . "msie=$msie";
			if ($msie) {
$this->_debug[] = 'headers_sent=' . headers_sent() . ', ' . 'session_cache_limiter()';
				session_cache_limiter('must-revalidate');
			}
			session_start();
			if ($msie && $this->_p3p) {
				header($this->_p3p);
			}
			if ($this->_regenerate) {
				session_regenerate_id();
			}
		}
		$this->initNamespace();
	}

	/**
	 * get
	 *
	 * @param mixed $name ???
	 * @param mixed $default ??? (optional)
	 */
	public function get($name, $default=null)
	{
		$this->start();
		if ($name !== null) {
			if ($this->_namespace) {
				if (isset($_SESSION[$this->_namespace][$name])) {
					return $_SESSION[$this->_namespace][$name];
				}
			} elseif (isset($_SESSION[$name])) {
				return $_SESSION[$name];
			}
		}
		return $default;
	}

	/**
	 * getRef
	 *
	 * @param mixed $name ??? (optional)
	 */
	public function & getRef($name=null)
	{
		$this->start();
		if ($name !== null) {
			if ($this->_namespace) {
				if (!isset($_SESSION[$this->_namespace][$name])) {
					$_SESSION[$this->_namespace][$name] = array();
				}
				return $_SESSION[$this->_namespace][$name];
			} else {
				//
				if (!isset($_SESSION[$name])) {
					$_SESSION[$name] = array();
				}
				return $_SESSION[$name];
			}
		}
		$value = array();
		return $value;
	}

	/**
	 * set
	 *
	 * @param mixed $name ???
	 * @param mixed $value ???
	 * @param integer $count ??? (optional)
	 * @return A_Session This object instance
	 */
	public function set($name, $value, $count=0)
	{
		if ($name) {
			$this->start();
			if ($name) {
				if ($value !== null) {
					if ($this->_namespace) {
						$_SESSION[$this->_namespace][$name] = $value;
					} else {
						$_SESSION[$name] = $value;
					}
				} elseif ($this->_namespace) {
					unset($_SESSION[$this->_namespace][$name]);
				} else {
					unset($_SESSION[$name]);
				}
				if ($count > 0) {
					$this->expire($name, $count);
				}
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
	public function has($name)
	{
		$this->start();
		if ($this->_namespace) {
			return isset($_SESSION[$this->_namespace][$name]);
		} else {
			return isset($_SESSION[$name]);
		}
	}

	/**
	 * __get
	 *
	 * @param mixed $name ???
	 * @return mixed The object in the session with the key $name
	 */
	public function __get($name)
	{
		return $this->get($name);
	}

	/**
	 * __set
	 *
	 * @param mixed $name ???
	 * @param mixed $value ???
	 * @return boolean True if successful
	 */
	public function __set($name, $value)
	{
		return $this->set($name, $value);
	}

	/**
	 * expire
	 *
	 * @param mixed $name The key to set expiration for
	 * @param mixed $count The expiration to set (optional)
	 */
	public function expire($name, $count=0)
	{
		$this->start();
		$_SESSION[$this->_a_namespace]['expire'][$name] = $count;
	}

	/**
	 * doExpiration
	 *
	 * @return null
	 */
	protected function doExpiration()
	{
$this->_debug[] = 'headers_sent=' . headers_sent() . ', ' . 'doExpiration()';
		if (isset($_SESSION[$this->_a_namespace]['expire'])) {
			foreach ($_SESSION[$this->_a_namespace]['expire'] as $name => $value) {
				if ($value > 0) {
					$_SESSION[$this->_a_namespace]['expire'][$name]--;		// decrement counter if > 1
				} else {
					if ($this->_namespace) {
						unset($_SESSION[$this->_namespace][$name]);			// remove session var
					} else {
						unset($_SESSION[$name]);							// remove session var
					}
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
	public function close()
	{
		$this->start();
		session_write_close();
	}

	/**
	 * destroy
	 *
	 * @return null
	 */
	public function destroy()
	{
$this->_debug[] = 'headers_sent=' . headers_sent() . ', ' . 'destroy()';
		$this->start();
		if ($this->_namespace) {
			$_SESSION[$this->_namespace] = array();
		} else {
			$_SESSION = array();
		}
	}

}
