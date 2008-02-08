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
/*
			foreach ($this->_elements as $params) {
				$class = 'A_Html_Form_' . ucfirst($params['type']);
				if (! class_exists($class)) {
					include str_replace('_', '/', $class) . '.php';
				}
				if (class_exists($class)) {
					if (isset($params['content'])) {
						$str = $params['content'];
						unset($params['content']);
					} else {
						$str = null;
					}
					unset($params['type']);
					$element = new $class();
					$content .= $element->render($params, $str);
				}
			}
*/
			$content = $this->partial($attr);
		}
		
		A_Html_Tag::setDefaults($attr, array('method'=>'post', 'action'=>'', ));
		return A_Html_Tag::render('form', $attr, $content);
	}

	public function partial($attr=array()) {
		$attr = array_merge($this->_attr, $attr);

		$content = '';
		foreach ($this->_elements as $params) {
			$class = 'A_Html_Form_' . ucfirst($params['type']);
			if (! class_exists($class)) {
				include str_replace('_', '/', $class) . '.php';
			}
			if (class_exists($class)) {
				if (isset($params['content'])) {
					$str = $params['content'];
					unset($params['content']);
				} else {
					$str = null;
				}
				unset($params['type']);
				$element = new $class();
				$content .= $element->render($params, $str);
			}
		}
		
		return $content;
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
		if (isset($params['label'])) {
			$this->_elements[] = array('type'=>'label', 'for'=>$params['name'], 'content'=>$params['label']);
			unset($params['label']);
		}
		if (isset($params['name']) && $params['name']) {
			$params['type'] = $type;
			$this->_elements[] = $params;
		}
		return $this;
	}

}