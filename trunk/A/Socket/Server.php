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

	// client class interface to check
	const BASE_CLIENT = 'A_Socket_Client_Abstract';
	// message class interface to check
	const BASE_MESSAGE = 'A_Socket_Message_Abstract';

	// connect event identifier
	const EVENT_CONNECT = 'a.socket.onconnect';
	// disconnect event identifier
	const EVENT_DISCONNECT = 'a.socket.ondisconnect';
	// message event identifier
	const EVENT_MESSAGE = 'a.socket.onmessage';

	/**
	 * Holds the main socket listening for connections
	 * @var resource
	 */
	protected $_socket;

	/**
	 * Holds all connected sockets
	 * @var array
	 */
	protected $_sockets = array();
	
	/**
	 * Holds all connected client objects
	 * @var array
	 */
	protected $_clients = array();

	/**
	 * The host to listen on
	 * @var string
	 */
	protected $_host;

	/**
	 * The port to listen on
	 * @var integer
	 */
	protected $_port;

	/**
	 * Event manager.  Used to fire events on connect, disconnect, and message
	 * @var A_Event
	 */
	protected $_eventManager;

	/**
	 * Parser object to extract messages from read stream
	 * @var A_Socket_Parser
	 */
	protected $_parser;

	/**
	 * The class name to use when instantiating a new client
	 * @var string
	 */
	protected $_client_class;

	/**
	 * The class name to use when instantiating a new message
	 * @var string
	 */
	protected $_message_class;

	/**
	 * The message to send when a connection is made
	 * @var string
	 */
	protected $_connectMessage;

	/**
	 * The message to send when a client disconnects
	 * @var string
	 */
	protected $_disconnectMessage;
	
	/**
	 * Constructor.
	 *
	 * @param A_Socket_EventListener_Abstract $eventListener event listener object to handle events
	 * @param A_Socket_Parser $parser parser object to extract messages from read stream
	 */
	public function __construct(A_Socket_EventListener_Abstract $eventListener, A_Socket_Parser $parser)
	{
		$this->_eventManager = new A_Event_Manager();
		$this->_eventManager->addEventListener($eventListener);
		$this->_parser = $parser;
	}

	/**
	 * Main server loop
	 *
	 * @param array $config Configuration array
	 */
	public function run($config = array())
	{
		$this->_host = $config['host'];
		$this->_port = $config['port'];
		$this->_connectMessage = $config['message-connect'];
		$this->_disconnectMessage = $config['message-disconnect'];

		$this->_client_class = $config['class-client'];
		if (!is_subclass_of($this->_client_class, self::BASE_CLIENT)) {
			throw new Exception('A_Socket_Server: the client class is invalid.');
		}

		$this->_message_class = $config['class-message'];
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
					// A new connection
					$resource = socket_accept($socket);
					if ($resource !== false) {
						$client = new $this->_client_class($resource);
						$this->_clients[$resource] = $client;
						$this->_sockets[] = $resource;
					} else {
						// socket error
					}
				} else {
					// a new message
					$client = $this->_clients[$socket];
					$bytes = socket_recv($socket, $data, 4096, 0);
					if ($bytes !== 0) {
						// message is valid
						if ($client->isConnected()) {
							$this->handleData($data, $client);
						} else {
							if ($client->connect($data)) {
								$this->fireEvent(
									self::EVENT_CONNECT,
									$client,
									$this->_connectMessage
								);
							}
						}
					} else {
						// client disconnected
						$this->fireEvent(
							self::EVENT_DISCONNECT,
							$client,
							$this->_disconnectMessage
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

	/**
	 * Setup main socket to listen for new connections
	 */
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

	/**
	 * Take data, parse messages, and fire message event
	 * 
	 * @param string $data Message received
	 * @param object $client Client that sent the message
	 */
	protected function handleData($data, $client)
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

	/**
	 * Convenience function for firing events
	 * 
	 * @param string $event Event to fire
	 * @param object $client Client that initiated the event
	 * @param string $message Message received from client
	 */
	protected function fireEvent($event, $client, $message = null)
	{
		$this->_eventManager->fireEvent(
			$event,
			new $this->_message_class($message, $client, $this->_clients)
		);
	}
}
