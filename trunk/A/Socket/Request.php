<?php
/**
 * Request.php
 *
 * @package  A_Socket
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author   Jonah Dahlquist <jonah@nucleussystems.com>
 */

/**
 * A_Socket_Request
 * 
 * This class encapsulates a request from a Socket client for passage through the Skeleton Front Controller
 */
class A_Socket_Request
{

	/**
	 * HTTP method to pass to A_Controller_Mapper
	 * @var string
	 */
	protected $method = false;

	/**
	 * Message to pass to controller
	 * @var object
	 */
	protected $data;

	/**
	 * Route to navigate to
	 * @var mixed
	 */
	protected $route;

	/**
	 * Constructor
	 * 
	 * @param object $data Message to pass through
	 */
	public function __construct($data)
	{
		$this->method = 'GET';
		$this->data = $data;
		$this->route = $data->getRoute();
	}

	/**
	 * Gets route/method information from request
	 * 
	 * @param string $index
	 * @return mixed
	 */
	public function get($index)
	{
		if (is_array($this->route) && isset($this->route[$index])) {
			return $this->route[$index];
		} elseif ($index == 'REQUEST_METHOD') {
			return $this->method;
		}
		return false;
	}

	/**
	 * Get message passed from Server
	 * 
	 * @return object
	 */
	public function getData()
	{
		return $this->data;
	}
}
