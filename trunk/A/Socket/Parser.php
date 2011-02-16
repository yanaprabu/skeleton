<?php

/**
 * A_Socket_Parser
 *
 * Interface for message parser.
 */
interface A_Socket_Parser
{
	/**
	 * Extract message from data stream.  Must return an array of message(s).
	 *
	 * @param string $data
	 * @return array
	 */
	public function parseMessages($data);
}
