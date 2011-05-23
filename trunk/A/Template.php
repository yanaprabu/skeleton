<?php
/**
 * Template Base
 *
 * @package  A
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Template
 *
 * Base Template class with template and get/set/has functionality
 *
 * @package A
 */

class A_Template
{
	protected $template = '';
	protected $data = array();
	protected $filename = '';
	
	/**
	 * __construct
	 *
	 * @param string $filename Name of the file (optional)
	 * @param array $data Data to pass (optional)
	 */
	public function __construct($filename='', $data=array())
	{
		$this->filename = $filename;
		if ($data) {
			$this->import($data);
		}
	}
	
	/**
	 * setTemplate
	 *
	 * @param mixed $template ???
	 * @return A_Template This object instance
	 */
	public function setTemplate($template)
	{
		$this->template = $template;
		return $this;
	}
	
	/**
	 * setFilename
	 *
	 * @param mixed $filename ???
	 * @return A_Template This object instance
	 */
	public function setFilename($filename)
	{
		$this->filename = $filename;
		return $this;
	}
	
	/**
	 * clear
	 *
	 * @return A_Template This object instance
	 */
	public function clear()
	{
		$this->data = array();
		return $this;
	}
	
	/**
	 * renderArray
	 *
	 * @param array $array ???
	 * @param string $block ??? (optional)
	 * @return string ???
	 */
	public function renderArray($array, $block='')
	{
	   	$str = '';
	   	foreach ($array as $key1 => $value1) {
	   		if (is_array($value1)) {
		   		foreach ($value1 as $key2 => $value2) {
			   		$this->set($key2, $value2);
			   	}
		   		$str .= $this->render($block);
	   		} else {
			   	$this->set($key1, $value1);
		   	}
	   	}
	   	if (! isset($key2)) {
		   	$str .= $this->render($block);
	   	}
	   	return $str;
	}
	
	/**
	 * get
	 *
	 * @param mixed $name ???
	 * @return mixed That key stored in $data if it exists
	 */
	public function get($name)
	{
		return (isset($this->data[$name]) ? $this->data[$name] : null);
	}
	
	/**
	 * set
	 *
	 * @param mixed $name Key to insert as
	 * @param mixed $value Value to insert
	 * @return A_Template This object instance
	 */
	public function set($name, $value)
	{
		if ($value !== null) {
			$this->data[$name] = $value;
		} else {
			unset($this->data[$name]);
		}
		return $this;
	}
	
	/**
	 * import
	 *
	 * @param mixed $data Data to import
	 * @return A_Template This object instance
	 */
	public function import($data)
	{
		$this->data = array_merge($this->data, $data);
		return $this;
	}
	
	/**
	 * has
	 *
	 * @param mixed $name Key to check for existance
	 * @return boolean True if it is set, otherwise false
	 */
	public function has($name)
	{
		return isset($this->data[$name]);
	}
	
	/**
	 * __toString
	 *
	 * @return string ???
	 */
	public function __toString()
	{
		return $this->render();
	}

}
