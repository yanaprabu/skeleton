<?php
/**
 * Json.php
 *
 * @package  A_Socket_Message
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author   Jonah Dahlquist <jonah@nucleussystems.com>
 */

/**
 * Hold a JSON message
 */
class A_Socket_Message_Json extends A_Socket_Message_Base
{

	/**
	 * Constructor
	 * 
	 * @param mixed $message Actual message data
	 * @param object $client Sender client
	 * @param array $clients All clients
	 */
	public function __construct($message, $client = null, $clients = array())
	{
		parent::__construct(json_decode($message), $client, $clients);
	}

	/**
	 * Reply to client(s)
	 *
	 * @param mixed $data Data to send
	 * @param mixed $recipient Set of clients to send message to
	 * @return A_Socket_Message_Json
	 */
	public function reply($data, $recipient = self::SENDER)
	{
		$this->_reply(json_encode($data), $recipient);
		return $this;
	}

	/**
	 * Get route data from message
	 */
	public function getRoute()
	{
		if (isset($this->message->route)) {
			return $this->message->route;
		} elseif (isset($this->message->type->module, $this->message->type->controller, $this->message->type->action)) {
			return array(
				'module' => $this->message->type->module,
				'controller' => $this->message->type->controller,
				'action' => $this->message->type->action
			);
		} else {
			return null;
		}
	}
}
