<?php

/**
 * A_Socket_Server
 *
 * This handles connecting with Socket clients.  It also receives
 * JSON data from clients, maps to controller/action, and dispatches.
 *
 * @package A_Socket
 */
class A_Socket_Server
{

	const BASE_CLIENT = 'A_Socket_Client_Abstract';
	const BASE_MESSAGE = 'A_Socket_Message_Abstract';

	const EVENT_CONNECT = 'a.socket.onconnect';
	const EVENT_DISCONNECT = 'a.socket.ondisconnect';
	const EVENT_MESSAGE = 'a.socket.onmessage';
	
	private $_socket;
	
	private $_sockets = array();
	
	private $_clients = array();
	
	private $_host;
	
	private $_port;
	
	private $_eventManager;

	private $_parser;

	private $_client_class;

	private $_message_class;
	
	/**
	 * Constructor
	 */
	public function __construct(
			A_Socket_EventListener_Abstract $eventListener,
			A_Socket_Parser $parser,
			A_Socket_Message_Abstract $connectMessage,
			A_Socket_Message_Abstract $disconnectMessage)
	{
		$this->_eventManager = new A_Event_Manager();
		$this->_eventManager->addEventListener($eventListener);
		$this->_parser = $parser;
		$this->_connectMessage = $connectMessage;
		$this->_disconnectMessage = $disconnectMessage;
	}

	/**
	 * Main server loop
	 */
	public function run($config = array())
	{
		$this->_host = $config['host'];
		$this->_port = $config['port'];

		$this->_client_class = $config['client-class'];
		if (!is_subclass_of($this->_client_class, self::BASE_CLIENT)) {
			throw new Exception('A_Socket_Server: the client class is invalid.');
		}

		$this->_message_class = $config['message-class'];
		if (!is_subclass_of($this->_message_class, self::BASE_MESSAGE)) {
			throw new Exception('A_Socket_Server: the message class is invalid.');
		}
		
		$this->initializeSocket();
		$stopLoop = false;
		
		while ($stopLoop == false) {
			$updated_sockets = $this->_sockets;
			socket_select($updated_sockets, $write = NULL, $exceptions = NULL, NULL);
				
			foreach ($updated_sockets as $socket) {
				if ($socket == $this->_socket) {
					$resource = socket_accept($socket);
					if ($resource !== false) {
						$client = new $this->_client_class($resource);
						$this->_clients[$resource] = $client;
						$this->_sockets[] = $resource;
					} else {
						// socket error
					}
				} else {
					$client = $this->_clients[$socket];
					$bytes = socket_recv($socket, $data, 4096, 0);
					if ($bytes !== 0) {
						if ($client->isConnected()) {
							$this->parseData($data, $client);
						} else {
							if ($client->connect($data)) {
								$this->fireEvent(
									self::EVENT_CONNECT,
									$client
								);
							}
						}
					} else {
						$this->fireEvent(
							self::EVENT_DISCONNECT,
							$client
						);
						unset($this->_clients[$socket]);
						$index = array_search($socket, $this->_sockets);
						unset($this->_sockets[$index]);
						unset($client);
					}
				}
			}
		}
	}

	protected function initializeSocket()
	{
		$this->_socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($this->_socket === false) {
			throw new Exception('Could not create socket: ' . socket_strerror(socket_last_error()));
		}

		socket_set_option($this->_socket, SOL_SOCKET, SO_REUSEADDR, 1);

		$success = socket_bind($this->_socket, $this->_host, $this->_port);
		if ($success === false) {
			throw new Exception('Could not bind socket: ' . socket_strerror(socket_last_error()));
		}

		$success = socket_listen($this->_socket, 5);
		if ($success === false) {
			throw new Exception('Could not listen to socket: ' . socket_strerror(socket_last_error()));
		}

		$this->_sockets[] = $this->_socket;
	}
	
	protected function parseData($data, $client)
	{
		$blocks = $this->_parser->parseMessages($data);
		
		foreach ($blocks as $block) {
			// do something with $block
			$this->fireEvent(
				self::EVENT_MESSAGE,
				$client,
				$block
			);
		}
	}
	
	protected function fireEvent($event, $client, $message = null)
	{
		switch ($event) {
			case self::EVENT_CONNECT:
				$message = $this->_connectMessage;
				$message->setClients($client, $this->_clients);
				break;
			case self::EVENT_DISCONNECT:
				$message = $this->_disconnectMessage;
				$message->setClients($client, $this->_clients);
				break;
			case self::EVENT_MESSAGE:
				$message = new $this->_message_class($message, $client, $this->_clients);
				break;
		}
		
		$this->_eventManager->fireEvent($event, $message);
	}
}
