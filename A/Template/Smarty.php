<?php
require_once 'Smarty.class.php';
/**
 * Template class that Adapts Smarty to be used as Skeleton renderer.
 * 
 * @package A_Template 
 */

class A_Template_Smarty extends Smarty {
	protected $filename;
	
	public function __construct ($filename='') {
		parent::Smarty();
		$this->filename = $filename;
	}
	
	public function set($key, $value) {
		parent::assign($key, $value);
	}
	
	public function render($template=null, $cache_id=null, $compile_id=null) {
		return parent::fetch($template ? $template : $this->template, $cache_id, $compile_id);
	}
	
}