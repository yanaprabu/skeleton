<?php
include_once 'A/Http/View.php';

class exampleView extends A_Http_View {
	public function __construct($locator) {
		parent::__construct($locator);
		$this->setTemplate('example');
		$this->set('menuitems', array('menu item 1', 'menu item 2', 'menu item 3', 'menu item 4'));
	}
}