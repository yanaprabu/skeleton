<?php

/**
 * A_WebSocket_Server
 *
 * This handles connecting with WebSocket clients.  It also receives
 * JSON data from clients, maps to controller/action, and dispatches.
 *
 * @package A_WebSocket
 */
class A_WebSocket_Server
{

	private $master;
	
	private $sockets = array();
	
	private $clients = array();
	
	private $locator;
	
	private $path;
	
	private $host;
	
	private $port;
	
	private $appPath;
	
	private $eventHandler;
	/**
	 * Constructor
	 */
	public function __construct($config)
	{
		$this->locator = $locator;
		$this->host = $config->get('WEBSOCKET')->get('host');
		$this->port = $config->get('WEBSOCKET')->get('port');
		$this->appPath = $config->get('APP');
	}

	/**
	 * Main server loop
	 */
	public function run($locator)
	{
		$this->locator = $locator;
		
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
						if ($this->eventHandler) {
							$this->eventHandler->onOpen($this->createEventObject(null, $client));
						}
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
						}
					} else {
						if ($this->eventHandler) {
							$this->eventHandler->onClose($this->createEventObject(null, $client));
						}
						unset($this->clients[$socket]);
						$index = array_search($socket, $this->sockets);
						unset($this->sockets[$index]);
						unset($client);
					}
				}
			}
		}
	}
	
	public function setEventHandler(A_WebSocket_EventHandler $eventHandler)
	{
		$this->eventHandler = $eventHandler;
	}
	
	public function getClients()
	{
		return $this->clients;
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
			$this->handleData($block, $client);
			
			// get ready for next loop
			$firstChar = substr($data, 0, 1);
			$index = strpos($data, chr(255));
		}
	}
	
	protected function handleData($block, $client)
	{
		if ($this->eventHandler) {
			$this->eventHandler->onMessage($this->createEventObject($block, $client));
		} else {
			$request = new A_WebSocket_Request($block, $this, $client);
			$this->locator->set('Request', $request);
			
			$front = new A_Controller_Front($this->appPath, array('', 'main', 'main'), array('', 'main', 'main'));
			$front->run($this->locator);
		}
	}
	
	protected function createEventObject($data, $client)
	{
		return (object) array('data' => $data, 'client' => $client, 'server' => $this);
	}
}