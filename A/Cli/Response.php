<?php
#include_once 'A/Http/View.php';
/**
 * CLI response. Encapsulates character encoding, quoting, escaping, and content. 
 * 
 * @package A_Cli 
 */

class A_Cli_Response extends A_Cli_View {

    public function out() {
    	echo $this->render();
    }
    
}
