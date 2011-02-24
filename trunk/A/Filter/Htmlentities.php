<?php
#include_once 'A/Filter/Abstract.php';
/**
 * Filter value with htmlentities() function with provided quote style and character set
 * 
 * @package A_Filter
 */

class A_Filter_Htmlentities extends A_Filter_Base {
public $character_set;
public $escape_quote_style;
	
	public function __construct($escape_quote_style=ENT_QUOTES, $character_set='UTF-8') {
		$this->escape_quote_style = $escape_quote_style;
		$this->character_set = $character_set;
	}
		
	public function filter () {
		return htmlentities($this->getValue(), $this->escape_quote_style, $this->character_set);
	}

}
