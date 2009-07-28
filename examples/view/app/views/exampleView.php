<?php
include_once 'A/Http/View.php';

class exampleView extends A_Http_View {
	public function __construct($locator) {
		parent::__construct($locator);
		$this->setRender('menu', 'menu');
		$this->setTemplate('example');
	}
}