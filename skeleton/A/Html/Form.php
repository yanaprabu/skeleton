<?php
include_once 'A/Html/Tag.php';

class A_Html_Form {
	protected $_attr = array(
					'action' => '',
					'method' => 'post',
					); 
	protected $_elements = array(); 
	protected $_helpers = array(
					); 
					
	/*
	 * name=string, value=string or renderer
	 */
	public function render($attr=array(), $content=null) {
		if (isset($this)) {
			$attr = array_merge($this->_attr, $attr);

			$content = '';
			foreach ($this->_elements as $param) {
				$class = 'A_Html_Form_' . ucfirst($param['type']);
				if (! class_exists($class)) {
					include str_replace('_', '/', $class) . '.php';
				}
				if (class_exists($class)) {
					unset($param['type']);
					$element = new $class();
					$content .= $element->render($param);
				}
			}
		}
		
		A_Html_Tag::setDefaults($attr, array('method'=>'post', 'action'=>'', ));
		return A_Html_Tag::render('form', $attr, $content);
	}

	// Set the method. Is there a setter for the action?
	public function setAction($action='') {
		$this->attr['action'] = $action;
		return $this;
	}
                             // Optional method to set the Model
	public function setMethod($method='post') {
		$this->attr['action'] = $action;
		return $this;
	}
                             // Optional method to set the Model
	public function setModel($model) {
		$this->model = $model;
		return $this;
	}
	
	public function __call($type, $args) {
		if(is_array($args[0])) {
			$params = $args[0];
		} else {
			$params['name'] = $args[0];
			$params['label'] = $args[1];
		}
		if (isset($params['name']) && $params['name']) {
			$params['type'] = $type;
			$this->_elements[$params['name']] = $params;
		}
		return $this;
	}

}