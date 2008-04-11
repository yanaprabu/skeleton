<?php
#include_once 'A/Html/Tag.php';

class A_Html_Form {
	protected $_attr = array(
					'action' => '',
					'method' => 'post',
					); 
	protected $_elements = array();
	protected $_helpers = array();
	protected $_wrapper = null;
	protected $_wrapperAttr = array();
	
	/*
	 * name=string, value=string or renderer
	 */
	public function render($attr=array(), $content=null) {
		if (isset($this)) {
			$content = $this->partial($attr);
		}
		
		A_Html_Tag::setDefaults($attr, array('method'=>'post', 'action'=>'', ));
		return A_Html_Tag::render('form', $attr, $content);
	}

	public function partial($attr=array()) {
		return implode("\n", $this->_elements);
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
		$this->model = is_object($model) ? $model->toArray() : $model;
		return $this;
	}

	public function setWrapper($obj, $attr=array()) {
		if (is_string($obj)) {
			include_once str_replace('_', '/', $obj) . '.php';
			$this->_wrapper = new $obj();
		} else {
			$this->_wrapper = $obj;
		}
		$this->_wrapperAttr = $attr;
		return $this;
	}

	protected function getHelperClass($type) {
		return isset($this->_helpers[$type]) ? $this->_helpers[$type] : 'A_Html_Form_' . ucfirst($type);
	}

	protected function setHelperClass($type, $class) {
		$this->_helpers[$type] = $class;
		return $this;
	}

	protected function getHelper($type) {
		$class = $this->getHelperClass($type);
		include_once str_replace('_', '/', $class) . '.php';
		if (class_exists($class)) {
			$element = new $class();
			return $element;
		}
	}

	public function reset() {
		$this->_elements = array(); 
		return $this;
	}
	
	public function __call($type, $args) {
		if(is_array($args[0])) {
			$params = $args[0];
		} else {
			$params['name'] = $args[0];
			if (isset($args[1])) {
				// fieldset is the exception that does not get a label
				if ($type == 'fieldset') {
					$params['content'] = $args[1];
				} else {
					$params['label'] = $args[1];
				}
			}
		}

		if ($type == 'fieldset') {
			$this->_elements[] = $params['content'];
		} elseif (isset($params['name']) && $params['name']) {
			$element = $this->getHelper($type);
			// set the value from the model if it is set
			if (isset($this->model[$params['name']])) {
				$params['value'] = $this->model[$params['name']];
			}
			// if this field has a label then wrap in a label tag
			if (isset($params['label'])) {
				$str = $params['label'];
				unset($params['label']);
				$label = $this->getHelper('label');
				$str = $label->render(array('type'=>'label', 'for'=>$params['name']), $str . $element->render($params));
			} else {
				$str = $element->render($params);
			}
			// if we wrap elements in a tag
			if ($this->_wrapper) {
				$str = $this->_wrapper->render($this->_wrapperAttr, $str);
			}
			$this->_elements[] = $str;
		}
		return $this;
	}

}