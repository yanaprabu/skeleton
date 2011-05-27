<?php
/**
 * A_Email_Smtp
 *
 * Connection using socket connection to SMTP server
 * 
 *
 * @package  A_Email
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

class A_Email_Smtp
{
	protected $config = array(
		'server' => 'localhost',
		'port' => 25,
		'username' => '',
		'password' => '',
		'connection_timeout' => 1,		// timeout for fsockconnect() in seconds
		'socket_timeout' => 0,			// timeout for fputs() in seconds ini_get("default_socket_timeout")
	);

	protected $connection; 

	protected $reply_codes = array(
		'211' => 'System status, or system help reply',
		'214' => 'Help message (Information on how to use the receiver or the meaning of a particular non-standard command; this reply is useful only to the human user)',
		'220' => '<domain> Service ready',
		'221' => '<domain> Service closing transmission channel',
		'250' => 'Requested mail action okay, completed',
		'251' => 'User not local; will forward to <forward-path> (See section 3.4)',
		'252' => 'Cannot VRFY user, but will accept message and attempt delivery (See section 3.5.3)',
		'354' => 'Start mail input; end with <CRLF>.<CRLF>',
		'421' => '<domain> Service not available, closing transmission channel (This may be a reply to any command if the service knows it must shut down)',
		'450' => 'Requested mail action not taken: mailbox unavailable (e.g., mailbox busy)',
		'451' => 'Requested action aborted: local error in processing',
		'452' => 'Requested action not taken: insufficient system storage',
		'500' => 'Syntax error, command unrecognized (This may include errors such as command line too long)',
		'501' => 'Syntax error in parameters or arguments',
		'502' => 'Command not implemented (see section 4.2.4)',
		'503' => 'Bad sequence of commands',
		'504' => 'Command parameter not implemented',
		'550' => 'Requested action not taken: mailbox unavailable (e.g., mailbox not found, no access, or command rejected for policy reasons)',
		'551' => 'User not local; please try <forward-path> (See section 3.4)',
		'552' => 'Requested mail action aborted: exceeded storage allocation',
		'553' => 'Requested action not taken: mailbox name not allowed (e.g., mailbox syntax incorrect)',
		'554' => 'Transaction failed (Or, in the case of a connection-opening response, "No SMTP service here")',
		);
		
	protected $errorMsg = array();
						
	/**
	 * 
	 */
	function __construct($server, $username='', $password='')
	{
		$this->config['server'] = $server;
		$this->config['username'] = $username;
		$this->config['password'] = $password;
	}
	
	/**
	 * 
	 */
	function setConnectionTimeout($seconds)
	{
		$this->config['connection_timeout'] = $seconds;
	}
	
	/**
	 * 
	 */
	function setSocketTimeout($seconds)
	{
		$this->config['socket_timeout'] = $seconds;
		if (isset($this->connection) && ($seconds > 0)) {
			stream_set_timeout($this->connection, $seconds);			
		}
	}
	
	/**
	 * 
	 */
	function connect()
	{
		$errmsg = '';
		
		$this->connection = fsockopen($this->config['server'], $this->config['port'], &$error, &$errmsg, $this->config['connection_timeout']);

		if ($this->connection) {
// Do we need meta data or blocking?
//			$this->metaData = stream_get_meta_data($this->connection);
//			stream_set_blocking($this->connection, $this->config['stream_blocking'])
			
			// set timeout if specified once connected
			if (isset($this->config['socket_timeout'])) {
				$this->setSocketTimeout($this->config['socket_timeout']);
			}
			
			$errmsg = $this->command('', '220', 'Connection failed. ');			// no command, just get reply
			
			if ($errmsg == '') {
				// start SMTP session
				$cmd = "HELO ".$this->config['server'];
				$errmsg = $this->command($cmd, '250', $cmd.' failed. ');

				// If credentials then attempt to authenticate
				if ($errmsg == '' && $this->config['username'] && $this->config['password']) {
					// request to authenticate
					$errmsg = $this->command('auth login', '334', 'Auth login failed. ');
					
					if ($errmsg == '') {					 
						// send username
						$errmsg = $this->command(base64_encode($this->config['username']), '334', 'Auth username failed. ');
						
						if ($errmsg == '') {						 
							// send password
							$errmsg = $this->command(base64_encode($this->config['password']), '235', 'Auth password failed. ');
						}
					}
				}
			}
		} else {
			$errmsg = "Socket connection failed to {$this->config['server']}:{$this->config['port']}. Error $error: $errmsg";
		}
		if ($errmsg != '') {
			$this->errorMsg[] = $errmsg;
		}
		return $errmsg == '';
	}
	
	/**
	 * 
	 */
	function send($to, $subject, $message, $headers)
	{
		$errmsg = '';
	
		// get From address
		if ( preg_match("/From:.*?[A-Za-z0-9\._%-]+\@[A-Za-z0-9\._%-]+.*/", $headers, $froms) ) {
			 preg_match("/[A-Za-z0-9\._%-]+\@[A-Za-z0-9\._%-]+/", $froms[0], $fromarr);
			 $from = $fromarr[0];
		}
	
		$cmd = "MAIL FROM: <$from>";
		$errmsg = $this->command($cmd, '250', $cmd . ' failed. ');
		
		if ($errmsg == '') {

			$cmd = "RCPT TO: <$to>";
			$errmsg = $this->command($cmd, '250', $cmd . ' failed. ');
			
			if ($errmsg == '') {
			
				$errmsg = $this->command('DATA', '354', 'DATA failed. ');
				
				if ($errmsg == '') {
				
					// All message fields plus line at the with a lone period
					$cmd = "To: $to\r\nFrom: $from\r\nSubject: $subject\r\n$headers\r\n\r\n$message\r\n.";
					$errmsg = $this->command($cmd, '250', $cmd . 'Message failed. ');
				}
			}
		}
		if ($errmsg != '') {
			$this->errorMsg[] = $errmsg;
		}
		
		return $errmsg == '';
	}

	/**
	 * 
	 */
	public function disconnect ()
	{
		$errmsg = $this->command('QUIT', '221', 'QUIT failed. ');
		
		return $errmsg == '';
	}

	/**
	 * returns collected error messages
	 */
	public function getErrorMsg($separator="\n")
	{
		return $separator ? implode($separator, $this->errorMsg) : $this->errorMsg;
	}

	/**
	 * 
	 */
	protected function command ($command, $success_code, $errmsg)
	{
		if ($command) {
			fputs($this->connection,"$command\r\n");
		}
		$reply = fgets($this->connection, 256);
		$code = substr($reply, 0, 3);
		if ($code == $success_code) {
			$errmsg = '';
		} else {
			$this->errorMsg[] = $reply . $errmsg;
		}
		return $errmsg;
	}

}
