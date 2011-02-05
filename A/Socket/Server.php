<?php

/**
 * A_Socket_Server
 *
 * This handles connecting with Socket clients.  It also receives
 * JSON data from clients, maps to controller/action, and dispatches.
 *
 * @package A_WebSocket
 */
class A_Socket_Server
{

	const BASE_CLIENT = 'A_Socket_Client_Abstract';
	const BASE_MESSAGE = 'A_Socket_Message';
	
	private $_master;
	
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
	public function __construct(A_Socket_EventListener_Abstract $eventListener, A_Socket_Parser $parser)
	{
		$this->_eventManager = new A_Event_Manager();
		$this->_eventManager->addEventListener($eventListener);
		$this->_parser = $parser;
	}

	/**
	 * Main server loop
	 */
	public function run($config = array())
	{
		$this->_host = $config['host'];
		$this->_port = $config['port'];

		$this->_client_class = $config['client-class'];
		if ($this->_client_class != self::BASE_CLIENT && !is_subclass_of($this->_client_class, self::BASE_CLIENT)) {
			throw new Exception('A_Socket_Server: the client class is invalid.');
		}

		$this->_message_class = $config['message-class'];
		if ($this->_message_class != self::BASE_MESSAGE && !is_subclass_of($this->_message_class, self::BASE_MESSAGE)) {
			throw new Exception('A_Socket_Server: the message class is invalid.');
		}
		
		$this->prepareMaster();
		$stopLoop = false;
		
		while ($stopLoop == false) {
			$updated_sockets = $this->_sockets;
			socket_select($updated_sockets, $write = NULL, $exceptions = NULL, NULL);
				
			foreach ($updated_sockets as $socket) {
				if ($socket == $this->_master) {
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
									'onconnect',
									$client
								);
							}
						}
					} else {
						$this->fireEvent(
							'ondisconnect',
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

	protected function prepareMaster()
	{
		$this->_master = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($this->_master < 0) {
			throw new Exception('Could not create socket: ' . socket_strerror($this->_master));
		}

		socket_set_option($this->_master, SOL_SOCKET, SO_REUSEADDR, 1);

		$res = socket_bind($this->_master, $this->_host, $this->_port);
		if ($res < 0) {
			throw new Exception('Could not bind socket: ' . socket_strerror($res));
		}

		$res = socket_listen($this->_master, 5);
		if ($res < 0) {
			throw new Exception('Could not listen to socket: ' . socket_strerror($res));
		}

		$this->_sockets[] = $this->_master;
	}
	
	protected function parseData($data, $client)
	{
		$blocks = $this->_parser->parseMessages($data);
		
		foreach ($blocks as $block) {
			// do something with $block
			$this->fireEvent(
				'onmessage',
				$client,
				$block
			);
		}
	}
	
	protected function fireEvent($event, $client, $message = null)
	{
		$this->_eventManager->fireEvent(
			'a.socket.' . $event,
			new $this->_message_class($message, $client, $this->_clients)
		);
	}
}
