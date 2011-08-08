<?php
/**
 * Field.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Model_Form_Field
 *
 * An extension of A_Model_Field.  It's purpose is to act as a field object for a A_Model_Form.  This class provides the ability to attach a renderer to it.
 * 
 * @package A_Model
 */
class A_Model_Form_Field extends A_Model_Field
{
	// from Input Controller
	public $renderer = null;
	// from Form Controller
	public $type = '';
	public $addtype = '';
	
	public function setType($type, $addtype='')
	{
		$this->type = $type;
		$this->addtype = $addtype;
		return $this;
	}
	
	public function setRenderer($renderer)
	{
		$this->renderer = $renderer;
		return $this;
	}
	
	public function render()
	{
		// if no value and addtype set then use it
		if ($this->addtype && $this->value == '') {
			$savetype = $this->type;
			$this->type = $this->addtype;
		} else {
			$savetype = null;
		}
		if (isset($this->type['renderer'])) {
			if (!isset($this->renderer)) {
				$this->renderer = $this->type['renderer'];
				unset($this->type['renderer']);
			}
		}
		// string is name of class with underscores in loadable convention
		if (is_string($this->renderer)) {
			// load locator if not loaded
			if (A_Locator::loadClass($this->renderer)) {
				// instantiate render passing the array of field
				$this->renderer = new $this->renderer();
			}
		}
		if (isset($this->renderer) && method_exists($this->renderer, 'render')) {
			// set name and value in array passed to renderer
			$this->type['name'] = $this->name;
			$this->type['value'] = $this->value;
			$this->renderer->import($this->type);
			return $this->renderer->render();
		}
		
		// restore type
		if ($savetype) {
			$this->type = $savetype;
		}
		
		return $this->value;
	}
	
	public function __toString()
	{
		return $this->render();
	}

}
