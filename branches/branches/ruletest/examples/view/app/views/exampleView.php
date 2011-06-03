<?php

class exampleView extends A_Http_View {
	public function __construct($locator) {
		parent::__construct($locator);
		$this->setTemplate('example');
		$this->set('menuitems', array(
			array('menu_link' => '?', 'menu_title'=>'default action using setTemplate()'),
			array('menu_link' => '?controller=example&action=needsdifferenttemplate','menu_title'=>'this action uses setTemplate() with  a different template'),
			array('menu_link' => '?controller=example&action=actionsetscontent','menu_title'=>'this action uses setContent()'),
			array('menu_link' => '?controller=example&action=actionsetsrenderer','menu_title'=>'this action uses setsRenderer() and the A_Template_Strreplace class'))
			);
	}
}