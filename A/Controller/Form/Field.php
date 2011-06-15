<?php
/**
 * Field.php
 *
 * @package  A_Controller
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Controller_Form_Field
 * 
 * Field class with type and rendering
 */
class A_Controller_Form_Field extends A_Controller_Input_Field
{

	public $type = '';
	public $addtype = '';
	public $default = '';
	public $source_name = '';
	public $save = true;
	
	public function __construct($name)
	{
		$this->name = $name;
	}
	
	public function setType($type, $addtype='')
	{
		$this->type = $type;
		$this->addtype = $addtype;
		return $this;
	}
	
	public function setDefault($value)
	{
		$this->default = $value;
		return $this;
	}
	
	public function setSourceName($value)
	{
		$this->source_name = $value;
		return $this;
	}
	
	public function setSave($value=true)
	{
		$this->save = $value;
		return $this;
	}

	public function render()
	{
		if ($this->addtype && $this->value == '') {
			$savetype = $this->type;
			$this->type = $this->addtype;
			$result = parent::render();
			$this->type = $savetype;
			return $result;
		} else {
			return parent::render();
		}
	}

}
