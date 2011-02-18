<?php
/**
 * Interface.php
 *
 * @package  A_Socket_Parser
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author   Jonah Dahlquist <jonah@nucleussystems.com>
 */

/**
 * A_Socket_Parser
 *
 * Interface for message parser.
 */
interface A_Socket_Parser_Interface
{
	/**
	 * Extract message from data stream.  Must return an array of message(s).
	 *
	 * @param string $data
	 * @return array
	 */
	public function parseMessages($data);
}
