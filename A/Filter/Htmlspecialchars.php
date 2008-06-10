<?php

class A_Filter_Htmlspecialchars {
public $character_set;
public $escape_quote_style;
	
	public function __construct($escape_quote_style=ENT_QUOTES, $character_set='UTF-8') {
		$this->escape_quote_style = $escape_quote_style;
		$this->character_set = $character_set;
	}
		
	public function run ($value) {
		return htmlspecialchars($value, $this->escape_quote_style, $this->character_set);
	}

}
