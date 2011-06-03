<?php

include '../A/Locator.php';

class Model_Helper_LoadTest extends UnitTestCase {
	
	private $locator;
	
	public function setUp() {
		$this->locator = new A_Locator();
		$this->locator->autoload();
	}
	
	private function createLoader()
	{
		$loader = new A_Model_Helper_Load($this->locator);
		
	}
	
}
