<?php

/**
 * Client
 */
class A_Socket_Client_WebSocket
{

	const SOCKET_EOL = "\r\n";
	
	const HANDSHAKE_RESPONSE = "HTTP/1.1 101 Web Socket Protocol Handshake\r\nUpgrade: WebSocket\r\nConnection: Upgrade\r\n%s\r\n%s";
	
	public function send($message)
	{
		$this->_send(chr(0) . $message . chr(255));
	}
	
	public function connect($data)
	{
		// Split up headers
		$requestLines = explode(self::SOCKET_EOL, $data);
		
		// Make sure request is valid
		$isValid = preg_match('/^GET (\S+) HTTP\/1.1$/', $requestLines[0], $matches);
		if (!$isValid) {
			//mylog('Invalid handshake attempt: ' . $requestLines[0]);
			socket_close($this->socket);
			return false;
		}
		
		// Path requested
		$path = $matches[1];
		
		// Grab header key/value sets
		$headers = array();
		foreach ($requestLines as $line) {
			$line = rtrim($line);
			if (preg_match('/^(\S+): (.*)$/', $line, $matches)) {
				$headers[$matches[1]] = $matches[2];
			}
		}
		
		// Security key
		$key3 = '';
		$isValid = preg_match("/\r\n(.*?)\$/", $data, $match);
		if ($isValid) {
			$key3 = $match[1];
		}
		
		// Extract headers for convenience
		$origin = $headers['Origin'];
		$host = $headers['Host'];
		
		// Create response headers
		$responseHeaders = array(
			'Sec-WebSocket-Origin' => $origin,
			'Sec-WebSocket-Location' => "ws://{$host}{$path}"
		);
		
		// Create response security hash
		$hash = $this->securityDigest($headers['Sec-WebSocket-Key1'], $headers['Sec-WebSocket-Key2'], $key3);
		
		// Build response headers string
		$responseString = '';
		foreach ($responseHeaders as $key => $value) {
			$responseString .= $key . ': ' . $value . self::SOCKET_EOL;
		}
		
		// Create response
		$responseString = sprintf(self::HANDSHAKE_RESPONSE, $responseString, $hash);
		
		// Write response to socket
		socket_write($this->socket, $responseString, strlen($responseString));
		$this->connected = true;
	}
	
	private function keyToBytes($key) {
		$matchNumbers = preg_match_all('/[0-9]/', $key, $number);
		$matchSpaces = preg_match_all('/ /', $key, $space);
		if ($matchNumbers && $matchSpaces) {
			return implode('', $number[0]) / count($space[0]);
		}
		return '';
	}
	
	// Pack the security keys for handshake response
	private function securityDigest($key1, $key2, $key3) {
		return md5(
			pack('N', $this->keyToBytes($key1)) .
			pack('N', $this->keyToBytes($key2)) .
			$key3, true);
	}
}
