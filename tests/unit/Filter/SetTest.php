<?php
require_once('A/Filter/Set.php');
require_once('A/Filter/Tolower.php');

class Filter_SetTest extends UnitTestCase {
	
    protected $data = array(
        'name' => 'John Smith',
        'phone' => '555-123-1234',
        'fax' => '(530) 123-1234',
        'favorite_color' => 'Purple',
        'comments' => '',
    );
    
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testValidatorFilterObject() {
  		$filters = new A_Filter_Set();

		$filters->addFilter(new A_Filter_Tolower(), 'name');
 		$this->data = $filters->doFilter($this->data);
		$this->assertEqual($this->data['name'], 'john smith');
	}

}
