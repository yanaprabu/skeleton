<?php

class A_Socket_Parser_WebSocket extends A_Socket_Parser
{

	public function parseMessages()
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
