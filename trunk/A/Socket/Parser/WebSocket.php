<?php

/**
 * Extract messages from WebSocket connection
 */
class A_Socket_Parser_WebSocket implements A_Socket_Parser
{

	/**
	 * Parse messages
	 * @param string $data
	 * @return array
	 */
	public function parseMessages($data)
	{
		$blocks = array();
		
		$firstChar = substr($data, 0, 1);
		$endIndex = strpos($data, chr(255));
		
		while ($firstChar == chr(0) && $endIndex !== false) {
			// get block and remove from data
			$block = substr($data, 1, $endIndex - 1);
			$data = substr($data, $endIndex);
			
			// store the block
			$blocks[] = $block;
			
			// get ready for next loop
			$firstChar = substr($data, 0, 1);
			$index = strpos($data, chr(255));
		}
		
		return $blocks;
	}
	
}
