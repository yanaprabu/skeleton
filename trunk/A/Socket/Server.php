<?php
/**
 * Server.php
 *
 * @package  A_Socket
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author   Jonah Dahlquist <jonah@nucleussystems.com>
 */

/**
 * A_Socket_Server
 *
 * This handles connecting with Socket clients.  It also receives
 * JSON data from clients, maps to controller/action, and dispatches.
 */
class A_Socket_Server
{

	// client class interface to check
	const INTERFACE_CLIENT = 'A_Socket_Client';
	// message class interface to check
	const INTERFACE_MESSAGE = 'A_Socket_Message';

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
	protected $socket;

	/**
	 * Holds all connected sockets
	 * @var array
	 */
	protected $sockets = array();
	
	/**
	 * Holds all connected client objects
	 * @var array
	 */
	protected $clients = array();

	/**
	 * The host to listen on
	 * @var string
	 */
	protected $host;

	/**
	 * The port to listen on
	 * @var integer
	 */
	protected $port;

	/**
	 * Event manager.  Used to fire events on connect, disconnect, and message
	 * @var A_Event
	 */
	protected $eventManager;

	/**
	 * The class name to use when instantiating a new client
	 * @var string
	 */
	protected $client_class;

	/**
	 * The class name to use when instantiating a new message
	 * @var string
	 */
	protected $message_class;

	/**
	 * The message to send when a connection is made
	 * @var string
	 */
	protected $connectMessage;

	/**
	 * The message to send when a client disconnects
	 * @var string
	 */
	protected $disconnectMessage;
	
	/**
	 * Constructor.
	 *
	 * @param A_Socket_EventListener_Abstract $eventListener event listener object to handle events
	 * @param A_Socket_Parser $parser parser object to extract messages from read stream
	 */
	public function __construct(A_Socket_EventListener $eventListener)
	{
		$this->eventManager = new A_Event_Manager();
		
		$this->eventManager->addEventListener(self::EVENT_CONNECT, array($eventListener, 'onConnect'));
		$this->eventManager->addEventListener(self::EVENT_MESSAGE, array($eventListener, 'onMessage'));
		$this->eventManager->addEventListener(self::EVENT_DISCONNECT, array($eventListener, 'onDisconnect'));
	}

	/**
	 * Main server loop
	 *
	 * @param array $config Configuration array
	 */
	public function run($config = array())
	{
		$this->host = $config['host'];
		$this->port = $config['port'];
		$this->connectMessage = $config['message-connect'];
		$this->disconnectMessage = $config['message-disconnect'];

		$this->client_class = $config['class-client'];
		if (!in_array(self::INTERFACE_CLIENT, class_implements($this->client_class))) {
			throw new Exception('A_Socket_Server: the client class is invalid.');
		}

		$this->message_class = $config['class-message'];
		if (!in_array(self::INTERFACE_MESSAGE, class_implements($this->message_class))) {
			throw new Exception('A_Socket_Server: the message class is invalid.');
		}
		
		$this->initializeSocket();
		$stopLoop = false;
		
		while ($stopLoop == false) {
			$updated_sockets = $this->sockets;
			socket_select($updated_sockets, $write = NULL, $exceptions = NULL, NULL);
				
			foreach ($updated_sockets as $socket) {
				if ($socket == $this->socket) {
					// A new connection
					$resource = socket_accept($socket);
					if ($resource !== false) {
						$client = new $this->client_class($resource);
						$this->clients[$resource] = $client;
						$this->sockets[] = $resource;
					} else {
						// socket error
					}
				} else {
					// a new message
					$client = $this->clients[$socket];
					$bytes = socket_recv($socket, $data, 4096, 0);
					if ($bytes !== 0) {
						// message is valid
						if ($client->isConnected()) {
							$blocks = $client->receive($data);

							foreach ($blocks as $block) {
								$this->fireEvent(self::EVENT_MESSAGE, $client, $block);
							}
						} else {
							if ($client->connect($data)) {
								$this->fireEvent(self::EVENT_CONNECT, $client, $this->connectMessage);
							}
						}
					} else {
						// client disconnected
						$this->fireEvent(self::EVENT_DISCONNECT, $client, $this->disconnectMessage);
						unset($this->clients[$socket]);
						$index = array_search($socket, $this->sockets);
						unset($this->sockets[$index]);
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
		$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($this->socket === false) {
			throw new Exception('Could not create socket: ' . socket_strerror(socket_last_error()));
		}

		socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 1);

		$success = socket_bind($this->socket, $this->host, $this->port);
		if ($success === false) {
			throw new Exception('Could not bind socket: ' . socket_strerror(socket_last_error()));
		}

		$success = socket_listen($this->socket, 5);
		if ($success === false) {
			throw new Exception('Could not listen to socket: ' . socket_strerror(socket_last_error()));
		}

		$this->sockets[] = $this->socket;
	}

	/**
	 * Convenience function for firing events
	 * 
	 * @param string $event Event to fire
	 * @param object $client Client that initiated the event
	 * @param string $message Message received from client
	 */
	protected function fireEvent($event, $client, $message)
	{
		$this->eventManager->fireEvent(
			$event,
			new $this->message_class($message, $client, $this->clients)
		);
	}
}
