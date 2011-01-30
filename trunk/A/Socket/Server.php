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
	
	private $_master;
	
	private $_sockets = array();
	
	private $_clients = array();
	
	private $_locator;
	
	private $_path;
	
	private $_host;
	
	private $_port;
	
	private $_appPath;
	
	private $_eventManager;
	/**
	 * Constructor
	 */
	public function __construct()
	{
		
	}

	/**
	 * Main server loop
	 */
	public function run($locator)
	{
		$socketConfig = $locator->get('Config')->get('SOCKET');
		
		$this->_host = $socketConfig->get('host');
		$this->_port = $socketConfig->get('port');

		$this->_client_class = $socketConfig->get('client-class');
		if (new $this->_client_class instanceof A_Socket_Client_Abstract) {
			throw new Exception('A_Socket_Server: the client class is invalid.');
		}

		$this->_message_class = $socketConfig->get('message-class');
		if (new $this->_message_class instanceof A_Socket_Message) {
			throw new Exception('A_Socket_Server: the message class is invalid.');
		}

		$this->_parser_class = $socketConfig->get('parser-class');
		if (new $this->_parser_class instanceof A_Socket_Parser) {
			throw new Exception('A_Socket_Server: the parser class is invalid.');
		}
		
		$this->_locator = $locator;
		$this->_eventManager = $locator->get('EventManager');
		
		if ($locator->get('SocketEventListener') instanceof A_WebSocket_EventListener_Abstract) {
			$this->_eventManager->addEventListener($locator->get('SocketEventListener'));
		} else {
			throw new Exception('A_Socket_Server: the event listener provided is not valid.');
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
							$client->connect($data);
							$this->fireEvent(
								'onconnect',
								$client
							);
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

		$res = socket_bind($this->_master, $this->host, $this->port);
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
		$parser = new $this->_parser_class($data);
		
		$blocks = $parser->parseMessages();
		
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
