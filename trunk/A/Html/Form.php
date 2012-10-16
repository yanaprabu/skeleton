<?php
/**
 * Form.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Html_Form
 *
 * Generate HTML form with fluent interface for form fields
 *
 * @package A_Html
 */
class A_Html_Form extends A_Html_Tag implements A_Renderer
{

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
	public function render($attr=array(), $content='')
	{
		if (isset($this)) {
			$content .= $this->partial($attr);
		}
		parent::mergeAttr($attr);
		return parent::render('form', $attr, $content);
	}

	public function partial($attr=array())
	{
		$out = '';
		foreach ($this->_elements as $name => $element) {
			$this->setValueFromModel($name, $element['renderer']);
			$str = '';
			if (isset($element['label'])) {
				$str .= is_object($element['label']) ? $element['label']->render() : $element['label'];
			}
			if (is_object($element['renderer'])) {
				$str .= $element['renderer']->render();
			}
			// if we wrap elements in a tag
			if (isset($element['wrapper']) && is_object($element['wrapper'])) {
				$str = $element['wrapper']->render($element['wrapperAttr'], $str);
			}
			$out .= $str;
		}
		return $out;
	}

	/**
	 * Get an attrubute of this HTML tag
	 *
	 * @param string $key Attribute key
	 * @return mixed
	 */
	public function get($key)
	{
		if (isset($this->_attr[$key])) {
			return $this->_attr[$key];
		}
	}

	/**
	 * Set an attribute to this tag
	 *
	 * @param string|array $key Either the key, or an associtive array of key/value pairs
	 * @param mixed $value Value of attribute, not applicable if first argument is array
	 * @return $this
	 */
	public function set($key, $value=null)
	{
		if ($key != '') {
			if (is_array($key)) {
				$this->import($key);
			} else {
				$this->_attr[$key] = $value;
			}
		}
		return $this;
	}

	/**
	 * Set this form's destination URL
	 *
	 * @param string $action
	 * @return $this
	 */
	public function setAction($action='')
	{
		$this->_attr['action'] = $action;
		return $this;
	}

	/**
	 * Set form method
	 *
	 * @param string $method Either POST or GET.  Default POST.
	 * @return $this
	 */
	public function setMethod($method='post')
	{
		$this->_attr['method'] = $method;
		return $this;
	}

	/**
	 * set data model that form will get/set values to/from.
	 *
	 * @param mixed $model
	 * @return $this
	 */
	public function setModel($model)
	{
		$this->model = $model;
		return $this;
	}

	public function setWrapper($obj, $attr=array())
	{
		if (is_string($obj)) {
			$this->_wrapper = new $obj();
		} else {
			$this->_wrapper = $obj;
		}
		$this->_wrapperAttr = $attr;
		return $this;
	}

	protected function getHelperClass($type)
	{
		return isset($this->_helpers[$type]) ? $this->_helpers[$type] : 'A_Html_Form_' . ucfirst($type);
	}

	protected function setHelperClass($type, $class)
	{
		$this->_helpers[$type] = $class;
		return $this;
	}

	protected function getHelper($type, $attr=array())
	{
		$class = $this->getHelperClass($type);
		if (class_exists($class)) {
			$element = new $class($attr);
			return $element;
		}
	}

	public function reset()
	{
		$this->_elements = array();
		return $this;
	}

	public function __call($type, $args)
	{
		$params = array();
		// allow (args), (name), (name, label), (name, args)
		if (is_array($args[0])) {
			$params = $args[0];				// all params in array
		} else {
			if (isset($args[1])) {
				if (is_array($args[1])) {
					$params = $args[1];		// array of params in 2nd arg
				} else {
					// fieldset is the exception that does not get a label
					if ($type == 'fieldset') {
						$params['value'] = $args[1];
					} else {
						$params['label'] = $args[1];
					}
				}
			}
			$params['name'] = $args[0];
		}

		if ($type == 'fieldset') {
			$this->_elements[] = $params['value'];
		} elseif (isset($params['name']) && $params['name']) {
			$element = $this->getHelper($type, $params);
			// if this field has a label then wrap in a label tag
			if (isset($params['label'])) {
				$str = $params['label'];
				unset($params['label']);
				$label = $this->getHelper('label', array('for' => $params['name'], 'value' => $str));
				$this->_elements[$params['name']]['label'] = $label;
			}
			// if we wrap elements in a tag
			if ($this->_wrapper) {
				$this->_elements[$params['name']]['wrapper'] = $this->_wrapper;
				$this->_elements[$params['name']]['wrapperAttr'] = $this->_wrapperAttr;
			}
			// set the value from the model if it is set
			$this->setValueFromModel($params['name'], $element);

			$this->_elements[$params['name']]['renderer'] = $element;
		}
		return $this;
	}

	protected function setValueFromModel($name, $element)
	{
		if (isset($this->model)) {
			// get value depending on if model is an array or object AND if value is set
			if (is_array($this->model) && isset($this->model[$name])) {
				$element->set('value', $this->model[$name]);
			} elseif (is_object($this->model) && $this->model->has($name)) {
				$element->set('value', $this->model->get($name));
			}
		}
	}

	public function import($data)
	{
		if (is_array($data)) {
			$this->_attr = array_merge($this->_attr, $data);
		}
		return $this;
	}

	public function __toString()
	{
		$this->render();
	}

}
