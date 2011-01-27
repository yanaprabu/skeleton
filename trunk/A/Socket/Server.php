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

	private $master;
	
	private $sockets = array();
	
	private $clients = array();
	
	private $locator;
	
	private $path;
	
	private $host;
	
	private $port;
	
	private $appPath;
	
	private $eventManager;
	/**
	 * Constructor
	 */
	public function __construct($config)
	{
		$this->host = $config->get('SOCKET')->get('host');
		$this->port = $config->get('SOCKET')->get('port');
		$this->appPath = $config->get('APP');
	}

	/**
	 * Main server loop
	 */
	public function run($locator)
	{
		$this->locator = $locator;
		$this->eventManager = $locator->get('EventManager');
		if (!$this->eventManager) {
			$this->eventManager = new A_Event_Manager();
			$this->eventManager->addEventListener(new A_WebSocket_EventListener_FrontController());
		}
		
		$this->prepareMaster();
		$stopLoop = false;
		
		while ($stopLoop == false) {
			$updated_sockets = $this->sockets;
			socket_select($updated_sockets, $write = NULL, $exceptions = NULL, NULL);
				
			foreach ($updated_sockets as $socket) {
				if ($socket == $this->master) {
					$resource = socket_accept($socket);
					if ($resource !== false) {
						$client = new A_WebSocket_Client($resource);
						$this->clients[$resource] = $client;
						$this->sockets[] = $resource;
					} else {
						// socket error
					}
				} else {
					$client = $this->clients[$socket];
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
						unset($this->clients[$socket]);
						$index = array_search($socket, $this->sockets);
						unset($this->sockets[$index]);
						unset($client);
					}
				}
			}
		}
	}

	protected function prepareMaster()
	{
		$this->master = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($this->master < 0) {
			throw new Exception('Could not create socket: ' . socket_strerror($this->master));
		}

		socket_set_option($this->master, SOL_SOCKET, SO_REUSEADDR, 1);

		$res = socket_bind($this->master, $this->host, $this->port);
		if ($res < 0) {
			throw new Exception('Could not bind socket: ' . socket_strerror($res));
		}

		$res = socket_listen($this->master, 5);
		if ($res < 0) {
			throw new Exception('Could not listen to socket: ' . socket_strerror($res));
		}

		$this->sockets[] = $this->master;
	}
	
	protected function parseData($data, $client)
	{
		$firstChar = substr($data, 0, 1);
		$endIndex = strpos($data, chr(255));
		
		while ($firstChar == chr(0) && $endIndex !== false) {
			// get block and remove from data
			$block = substr($data, 1, $endIndex - 1);
			$data = substr($data, $endIndex);
			
			// do something with $block
			$this->fireEvent(
				'onmessage',
				$client,
				$block
			);
			
			// get ready for next loop
			$firstChar = substr($data, 0, 1);
			$index = strpos($data, chr(255));
		}
	}
	
	protected function fireEvent($event, $client, $message = null)
	{
		$this->eventManager->fireEvent(
			'a.socket.' . $event,
			new A_WebSocket_Message_Json($message, $client, $this->clients)
		);
	}
	
	protected function createEventObject($data, $client)
	{
		return (object) array('data' => $data, 'client' => $client, 'server' => $this);
	}
}
