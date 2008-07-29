<?php
/**
 * Convert smart quotes to standard quotes
 * 
 * @package A_Filter 
 */

class A_Filter_Smartquotes {
	
	public function run ($value) {
		/*
		 * by Chris Shiflett (http://shiflett.org/)
		 */
		$search = array(chr(145), 
	                    chr(146), 
	                    chr(147), 
	                    chr(148), 
	                    chr(151)); 
	    $replace = array("'", 
	                     "'", 
	                     '"', 
	                     '"', 
	                     '-'); 
	    return str_replace($search, $replace, $value); 
	}

}
