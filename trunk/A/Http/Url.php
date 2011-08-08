<?php
/**
 * Url.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Http_Url
 *
 * This class provides various functions with which to create, manipulate, and read URLs.
 * 
 * @package A_Http
 */
class A_Http_Url
{

	protected $action_param = '';
	
	public function __construct($action_param='')
	{
		$this->action_param = $action_param;
	}
	
	public function setActionParam($action_param)
	{
		$this->action_param = $action_param;
		return $this;
	}
	
	public function getActionParam()
	{
		return $this->action_param;
	}
	
	public function getProtocol()
	{
		if (isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == 'on')) {
			return 'https';
		} else {
			return 'http';
		}
	}
	
	public function getBaseUrl($page='', $server='', $protocol='')
	{
		if (!$page) {
			$page = $_SERVER['SCRIPT_NAME'];
		}
		if (!$server) {
			$server = $_SERVER['SERVER_NAME'];
		}
		if (! $protocol) {
			$protocol = $this->getProtocol();
		}
		return "$protocol://$server$page";
	}
	
	public function getParams($params=array())
	{
		if ($params) {
			if (is_array($params)) {
				$str = http_build_query($params);
			} elseif (is_string($params)) {
				$str .= $params;
			}
		}
		return $str;
	}
	
	public function getUrl($params=array(), $page='', $server='', $protocol='')
	{
		return $this->getBaseUrl($page, $server, $protocol) . '?' . $this->getParams($params);
	}
	
	public function getCleanUrl($action, $params=array(), $page='', $server='', $protocol='')
	{
		$params[$this->action_param] = $action;
		return $this->getUrl($params, $page, $server, $protocol);
	}

}
