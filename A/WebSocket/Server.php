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
	
	private $eventHandler;
	/**
	 * Constructor
	 */
	public function __construct($locator, $path)
	{
		$this->locator = $locator;
		$this->path = $path;
	}

	/**
	 * Main server loop
	 */
	public function run()
	{
		$this->prepareMaster();
		
		while (true) {
			$changed_sockets = $this->sockets;
			socket_select($changed_sockets, $write = NULL, $exceptions = NULL, NULL);
				
			foreach ($changed_sockets as $socket) {
				if ($socket == $this->master) {
					if (($resource = socket_accept($this->master)) < 0) {
						// Socket error
						continue;
					} else {
						$client = new A_WebSocket_Client($resource, $this);
						$this->clients[$resource] = $client;
						$this->sockets[] = $resource;
					}
				} else {
					$client = $this->clients[$socket];
					$bytes = socket_recv($socket, $data, 4096, 0);
					if ($bytes === 0) {
						unset($this->clients[$socket]);
						$index = array_search($socket, $this->sockets);
						unset($this->sockets[$index]);
						unset($client);
					} else {
						if ($client->isConnected) {
							$this->parseData($data, $client);
						} else {
							$client->connect($data);
						}
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
		if ($master < 0) {
			die('Could not create socket: ' . socket_strerror($this->master));
		}

		socket_set_option($this->master, SOL_SOCKET, SO_REUSEADDR, 1);

		$res = socket_bind($this->master, $host, $port);
		if ($res < 0) {
			die('Could not bind socket: ' . socket_strerror($res));
		}

		$res = socket_listen($this->master, 5);
		if ($res < 0) {
			die('Could not listen to socket: ' . socket_strerror($res));
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
			$this->eventHandler->onMessage((object) array('data' => $block, 'client' => $client, 'server' => $this));
		} else {
			$request = new A_WebSocket_Request($block, $server, $client);
			$this->locator->set('Request', $request);
			
			$front = new A_Controller_Front($path, null, null);
			$front->run($this->locator);
		}
	}
}