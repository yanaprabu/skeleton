<?php
/**
 * Generate URLs
 *
 * @package  A
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Url
 *
 * This class provides various functions with which to create, manipulate, and
 * read URLs
 */
class A_Url {
	protected $action_param = '';
	
	/**
	 * __construct
	 *
	 * @param string $action_param
	 */
	public function __construct($action_param='') {
		$this->action_param = $action_param;
	}
	
	/**
	 * setActionParam
	 *
	 * @param string $action_param
	 * @return A_Url This object instance
	 */
	public function setActionParam($action_param) {
		$this->action_param = $action_param;
		return $this;
	}
	
	/**
	 * getActionParam
	 *
	 * @return string The current action parameter
	 */
	public function getActionParam() {
		return $this->action_param;
	}
	
	/**
	 * getProtocol
	 *
	 * @return string The protocol of the URL
	 */
	public function getProtocol() {
		if (isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == 'on')) {
			return 'https';
		} else {
			return 'http';
		}
	}
	
	/**
	 * getBaseUrl
	 *
	 * @param string $page Route to desired page (optional)
	 * @param string $server The server name (optional)
	 * @param string $protocol The protocol for the URL (optional)
	 * @return string All parameters merged into a valid URL
	 */
	public function getBaseUrl ($page='', $server='', $protocol='') {
		if (! $page) {
			$page = $_SERVER['SCRIPT_NAME'];
		}
		if (! $server) {
			$server = $_SERVER['SERVER_NAME'];
		}
		if (! $protocol) {
			$protocol = $this->getProtocol();
		}
	
		return "$protocol://$server$page";
	}
	
	/**
	 * getParams
	 *
	 * @param array $params (optional)
	 * @return string HTTP data in the form of a query
	 */
	public function getParams ($params=array()) {
		if ($params) {
			if (is_array($params)) {
/*
				$p = array();
				foreach ($params as $key => $val) {
					$p[] = "$key=$val";
				}
				$str = implode('&', $p);
*/
				$str = http_build_query($params);
			} elseif (is_string($params)) {
				$str .= $params;
			}
		}
		return $str;
	}
	
	/**
	 * getUrl
	 *
	 * @param array $params HTTP query parameters (optional)
	 * @param string $page Route to desired file (optional)
	 * @param string $server The server for the URL (optional)
	 * @param string $protocol The protocol to put the URL in (optional)
	 * @return string Valid fully constructed URL
	 */
	public function getUrl($params=array(), $page='', $server='', $protocol='') {
		return $this->getBaseUrl($page, $server, $protocol) . '?' . $this->getParams($params);
	}
	
	/**
	 * getClearUrl
	 *
	 * @param mixed $action ???
	 * @param array $params HTTP query parameters (optional)
	 * @param string $page Route to desired file (optional)
	 * @param string $server The server for the URL (optional)
	 * @param string $protocol The protocol to put the URL in (optional)
	 * @return string Valid fully constructed URL
	 */
	public function getCleanUrl($action, $params=array(), $page='', $server='', $protocol='') 	{
		$params[$this->action_param] = $action;
		return $this->getUrl($params, $page, $server, $protocol);
	}
	
}
