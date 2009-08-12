<?php
include_once 'A/Http/View.php';

class exampleView extends A_Http_View {
	public function __construct($locator) {
		parent::__construct($locator);
		$this->setTemplate('example');
		$this->set('menuitems', array(
			array('menu_link' => '?', 'menu_title'=>'start example'),
			array('menu_link' => '?controller=example&action=needsdifferenttemplate','menu_title'=>'this actions uses a different template'),
			array('menu_link' => '?controller=example&action=actionsetscontent','menu_title'=>'this action uses setContent()'),
			array('menu_link' => '?controller=example&action=actionsetsrenderer','menu_title'=>'this action uses actionsetsrenderer()'))
			);
	}
}