<?php
/**
 * Base.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Filter_Base
 * 
 * Abstract base class for filters
 * 
 * @package A_Filter 
 */
abstract class A_Filter_Base implements A_Filter_Filterer
{

	protected $container;
	/*
	 * $params array define the order and names of the constructor params
	 */
	protected $params = array(
		'field' => '', 
	);
	
	/**
	 * When creating children here, remember to call this function and 
	 * put params before $field 
	 * 
	 * @param string field this filter applies to
	 * @param boolean whether this filter returns true for null value
	 */
	public function __construct(/* $field='', $optional=false*/)
	{
		$params = func_get_args();
		if (count($params) == 1) {
			// first param is array of params
			$this->config($params[0]);
		} else {
			reset($this->params);
			foreach ($params as $value) {
				// set the values in params in order
				$this->params[key($this->params)] = $value;
				next($this->params);
			}
		}
	}
	
	/**
     * Set params property with assoc array
     * 
     * @param array $params
     * @return $this
     */
	public function config($params=array())
	{
		foreach ($params as $key => $value) {
			$this->params[$key] = $value;
		}
		return $this;
	}
	
	/**
     * Changes the field this filter applies to
     * 
     * @param string field this filter applies to
     * @return $this
     */
	public function setName($field)
	{
		$this->params['field'] = $field;
		return $this;
	}
	
    /**
     * Returns the field this filter applies to
     * 
     * @return string field this filter applies to
     */
	public function getName()
	{
		return $this->params['field'];
	}
	
	/**
     * Returns the value associated with this filter by default, but can return any value in
     * the data container that this filter is validating
     * 
     * @param string field you're trying to access
     * @return mixed whatever is inside the container array at key $name
     */
	public function getValue($name=null)
	{
		if (is_null($name)) {
			$name = $this->params['field'];
		}
		if (is_array($this->container)) {
			if (isset($this->container[$name])) {
				return $this->container[$name];
			}
		} elseif (is_object($this->container)) {
			return $this->container->get($name);
		} else {
			return $this->container;
		}
	}
	
	/**
     * Sets the value in the data container that this filter is validating
     * 
     * @param string name of field
     * @param mixed value to set
     * @return $this
     */
	public function setValue($name, $value=null)
	{
		if ($name && ($value !== null)) {
			if (is_array($this->container)) {
				$this->container[$name] = $value;
			} elseif (is_object($this->container)) {
				$this->container->set($name, $value);
			} else {
				$this->container = $value;
			}
		}
		return $this;
	}
	
	/**
	 * Filters data
	 *
	 * @param $container
	 * @return string Filtered data
	 */
	public function doFilter($container) {
	    $this->container = $container;
	    return $this->filter();
	}
	
    /**
     * Filter and return $this->getValue()
     * 
     * @return string
     */
	abstract protected function filter();

}
