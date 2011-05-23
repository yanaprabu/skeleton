<?php
/**
 * Collection.php
 *
 * @package  A
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author   Cory Kaufman
 */

/**
 * A_Collection
 * 
 * Standard collection class that has get/set/has, iterator and array access
 */
class A_Collection implements Iterator, ArrayAccess
{
	protected $_data = array();
	
	/**
	 * @param array $data Initial Collection data
	 */
	public function __construct($data = array())
	{
		$this->_data = $data;
	}
	
	/**
	 * Recursively import data into this Collection, converting sub-arrays into sub-Collections
	 * 
	 * @param array $source
	 * @param string $node
	 * @return self
	 */
	public function import($source, $node='')
	{
		if ($source) {
			if ($node) {
				$source = array($node => $source);
			}
			$this->_expand($this, $source);
		}
		return $this;
	}
	
	protected function _expand($obj, $data)
	{
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
	
	/**
	 * Get value at specified index/key, or null if that key doesn't exist
	 * 
	 * @param mixed $key
	 */
	public function get($key)
	{
		return isset($this->_data[$key]) ? $this->_data[$key] : null;
	}
	
	/**
	 * Sets the specified key to a value, or a default if the value is null.  The key is deleted if default is null
	 * 
	 * @param mixed $key
	 * @param mixed $value
	 * @param mixed $default
	 * @return self
	 */
	public function set($key, $value, $default=null)
	{
		if ($value !== null) {
			$this->_data[$key] = $value;
		} elseif ($default !== null) {
			$this->_data[$key] = $default;
		} else {
			unset($this->_data[$key]);
		}
		return $this;
	}
	
	public function __get($key)
	{
		return $this->get($key);
	}
	
	public function __set($key, $value)
	{
		return $this->set($key, $value);
	}
	
	/**
	 * Sets the specified key to a value
	 * 
	 * @param mixed $key
	 * @param mixed $value
	 */
	public function add($key, $value)
	{
		return $this->set($key, $value);
	}
	
	/**
	 * Removed the specified key from this Collection
	 * 
	 * @param mixed $key
	 * @return self
	 */
	public function remove($key)
	{
		if($this->has($key))
			unset($this->_data[$key]);
		return $this;
	}
	
	/**
	 * Get the number of items in this Collection
	 * 
	 * @return int
	 */
	public function count()
	{
		return count($this->_data);
	}
	
	/**
	 * Extract a slice of this Collection into a new Collection
	 * 
	 * @param int $offset Offset of slice
	 * @param int $length Length of slice
	 * @return A_Collection
	 */
	public function slice($offset, $length)
	{
		return new A_Collection(array_slice($this->_data, $offset, $length, true));
	}
	
	/**
	 * Create a copy of this Collection, reversed
	 * 
	 * @return A_Collection
	 */
	public function reverse()
	{
		return new A_Collection(array_reverse($this->_data, true));
	}
	
	/**
	 * Check if this Collection contains the specified key
	 * 
	 * @param mixed $key
	 * @return boolean True if key exists
	 */
	public function has($key)
	{	
		return isset($this->_data[$key]);
	}
	
	/**
	 * @see Iterator::current()
	 */
	public function current()
	{
		return current($this->_data);
	}
	
	/**
	 * @see Iterator::key()
	 */
	public function key()
	{
		return key($this->_data);
	}
	
	/**
	 * @see Iterator::next()
	 */
	public function next()
	{
		next($this->_data);
	}
	
	/**
	 * @see Iterator::rewind()
	 */
	public function rewind()
	{
		reset($this->_data);
	}
	
	/**
	 * @see Iterator::valid()
	 */
	public function valid()
	{
		return current($this->_data) !== false;
	}
	
	/**
	 * Convert this Collection to a normal array
	 * 
	 * @return array
	 */
	public function toArray()
	{
		return $this->_data;
	}
	
	/**
	 * Put data into string separated by a delimiter
	 * 
	 * @param string $delimiter
	 * @return string
	 */
	public function join($glue)
	{
		return implode($delimiter, $this->_data);
	}
	
	/**
	 * Sorts internal data by the specified sorting object.  Sorting object must implement the compare() method, with arguments that reflect the requirements of usort()
	 * 
	 * @param object $sorter
	 * @return self
	 */
	public function order($sorter)
	{
		uasort($this->_data, array($sorter, 'compare'));
		return $this;
	}
	
/*
	public function orderBy($key, $order = 'asc')	{
		$this->order(new A_Collection_ArraySorter($key, $order), 'compare'); 
	}
*/
	
	/**
	 * Put data into string separated by a delimiter
	 * 
	 * @param string $glue
	 * @return string
	 */
	public function toString($glue)
	{
		return $this->join($glue);
	}
	
	public function __toString()
	{
		return $this->toString(',');
	}
	
	/**
	 * @see ArrayAccess::offsetExists()
	 */
	public function offsetExists($offset)
	{
		return $this->has($offset);
	}
	
	/**
	 * @see ArrayAccess::offsetGet()
	 */
	public function offsetGet($offset)
	{
		return $this->get($offset);
	}
	
	/**
	 * @see ArrayAccess::offsetSet()
	 */
	public function offsetSet($offset, $value)
	{
		return $this->add($offset, $value);
	}
	
	/**
	 * @see ArrayAccess::offsetUnset()
	 */
	public function offsetUnset($offset)
	{
		return $this->remove($offset);
	}
	
}
