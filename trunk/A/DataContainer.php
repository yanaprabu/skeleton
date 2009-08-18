<?php
/**
 * A_DataContainer
 *
 * standard container to contain scalars and arrays
 *
 * Usage:
 * <code><?php
 * // code snippet
 * ?></code>
 *
 * @author Christopher Thompson <arborint@sourceforge.net>
 * @library Skeleton
 * @category [color=red]what goes here?[/color]
 * @package A
 * @version @package_version@
 */
class A_DataContainer implements Iterator {
	protected $_data = array();

	/*
	 * __construct
	 * Description goes here about the function
	 * @access public
	 * @param array $source
	 */
	public function __construct($source=null) {
		if ($source) {
			$this->_expand($this, $source);
		}
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
			        	$obj->_data[$key] = new A_DataContainer;
			        }
					$this->_expand($obj->_data[$key], $value);
				} else {
		        	$obj->_data[$key] = $value;
				}
			}
		}
    }

	/*
	 * set
	 * Description goes here about the function
	 * @access protected
	 * @param string $key, mixed $value
	 * @return integer
	 */
    public function set($key, $value=null) {
        if ($key) {
			if ($value !== null) {
				$this->_data[$key] = $value;
			} else {
				unset($this->_data[$key]);
			}
        }
        return $this;
    }

	/*
	 * get
	 * Description goes here about the function
	 * @access protected
	 * @param string $key, string $default
	 * @return integer
	 */
    public function get($key, $default=null) {
        return isset($this->_data[$key]) ? ($this->_data[$key] instanceof A_DataContainer ? $this->_data[$key]->_data : $this->_data[$key]) : $default;
    }

	/*
	 * has
	 * Description goes here about the function
	 * @access protected
	 * @param string $key
	 * @return integer
	 */
    public function has($key) {
        return isset($this->_data[$key]);
    }

	/*
	 * __set
	 * Description goes here about the function
	 * @access protected
	 * @param string $key, mixed $value
	 * @return integer
	 */
    protected function __set($key, $value=null) {
        if ($key) {
	       	$this->_data[$key] = $value;
        }
    }

	/*
	 * __get
	 * Description goes here about the function
	 * @access protected
	 * @param string $key
	 * @return integer
	 */
    protected function __get($key) {
        return isset($this->_data[$key]) ? $this->_data[$key] : null;
    }

	/*
	 * rewind
	 * Description goes here about the function
	 * @access public
	 * @param 
	 * @return integer
	 */
    public function toArray() {
        return $this->_data;
    }

	/*
	 * rewind
	 * Description goes here about the function
	 * @access public
	 * @param 
	 * @return integer
	 */
    public function rewind() {
        reset($this->_data);
    }

	/*
	 * current
	 * Description goes here about the function
	 * @access public
	 * @param 
	 * @return integer
	 */
    public function current() {
        return current($this->_data);
    }

	/*
	 * key
	 * Description goes here about the function
	 * @access public
	 * @param 
	 * @return integer
	 */
    public function key() {
        return key($this->_data);
    }

	/*
	 * next
	 * Description goes here about the function
	 * @access public
	 * @param 
	 * @return integer
	 */
    public function next() {
        return next($this->_data);
    }

	/*
	 * valid
	 * Description goes here about the function
	 * @access public
	 * @param 
	 * @return integer
	 */
    public function valid() {
        return current($this->_data) !== false;
    }
}

