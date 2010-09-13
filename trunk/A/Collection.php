<?php
/**
 * A_Collection
 * 
 * Standard _data class that has get/set/has, iterator and array access
 * 
 * @author Cory Kaufman
 * @package A 
 */
class A_Collection implements Iterator, ArrayAccess	{
	
	protected $_data;
	
	public function __construct($_data = array())	{
		$this->_data = $_data;
	}
	
	/*
	 * import
	 * Description goes here about the function
	 * @access public
	 * @param array $source, string $node
	 * @return integer
	 */
    public function import($source, $node='') {
		if ($source) {
			if ($node) {
				$source = array($node => $source);
			}
			$this->_expand($this, $source);
		}
    }

	/*
	 * _expand
	 * Description goes here about the function
	 * @access protected
	 * @param object $obj, array $data
	 * @return integer
	 */
    protected function _expand($obj, $data) {
		if (isset($data)) {
			foreach ($data as $key => $value) {
				if (is_array($value)) {
			        if (! isset($obj->_data[$key])) {
			        	$obj->_data[$key] = new A_Collection();
			        }
					$this->_expand($obj->_data[$key], $value);
				} else {
		        	$obj->_data[$key] = $value;
				}
			}
		}
    }

	public function get($key)	{
		return isset($this->_data[$key]) ? $this->_data[$key] : null;
	}
	
	public function set($key, $value, $default=null) {
		if ($value !== null) {
			$this->_data[$key] = $value;
		} elseif ($default !== null) {
			$this->_data[$key] = $default;
		} else {
			unset($this->_data[$key]);
		}
		return $this;
	}
	
	public function __get($key)	{
		return $this->get($key);
	}
	
	public function __set($key, $value)	{
		return $this->set($key, $value);
	}
	
	public function add($key, $value)	{
		return $this->set($key, $value);
	}
	
	public function remove($key)	{
		if($this->has($key)) unset($this->_data[$key]);
	}
	
	public function count()	{
		return count($this->_data);
	}
	
	public function slice($offset, $length)	{
		return new A_Collection(array_slice($this->_data, $offset, $length, true));
	}
	
	public function reverse()	{
		return new A_Collection(array_reverse($this->_data, true));
	}
	
	public function has($key)	{	
		return isset($this->_data[$key]);
	}
	
	public function current()	{
		return current($this->_data);
	}
	
	public function key()	{
		return key($this->_data);
	}
	
	public function next()	{
		next($this->_data);
	}
	
	public function rewind()	{
		reset($this->_data);
	}
	
	public function valid()	{
		return current($this->_data) !== false;
	}
	
	public function toArray()	{
		return $this->_data;
	}
	
	/*
	 * Implementation-specific methods
	 * 
	 */	 
	
	public function join($glue)	{
		return join($glue, $this->_data);
	}
	
	public function order($sorter)	{
		uasort($this->_data, array($sorter, 'compare'));
	}
	
/*
	public function orderBy($key, $order = 'asc')	{
		$this->order(new A_Collection_ArraySorter($key, $order), 'compare'); 
	}
*/
	
	public function toString($glue)	{
		return $this->join($glue);
	}
	
	public function __toString()	{
		return $this->toString(',');
	}
	
	public function offsetExists($offset)	{
		return $this->has($offset);
	}
	
	public function offsetGet($offset)	{
		return $this->get($offset);
	}
	
	public function offsetSet($offset, $value)	{
		return $this->add($offset, $value);
	}
	
	public function offsetUnset($offset)	{
		return $this->remove($offset);
	}
	
}
