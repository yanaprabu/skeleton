<?php
/**
 * Set.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Filter_Set
 *
 * Contains multiple filters that are all run.
 *
 * @package A_Filter
 */
class A_Filter_Set implements A_Filter_Filterer
{

    protected $chain = array();
    protected $errorMsg = array();
    protected $dir = 'A_Filter_';

	public function addFilter($filter, $fields=array())
	{
		// if filter is string then we load the class later
		if (is_string($filter)) {
			$filter = func_get_args();
			$fields = null;
		} elseif ($fields && ! is_array($fields)) {
			$fields = array($fields);
		}
		// if for specific fields only then create filter for each field
		if ($fields) {
			foreach ($fields as $field) {
				$filter->setName($field);
				$this->chain[] = $filter;
				$filter = clone $filter;
			}
		} else {
			$this->chain[] = $filter;
		}
		return $this;
	}

	/**
     * Sets the filterchain to an array of filters
     *
     * @param array $chain
     * @return $this
     */
	public function setChain($chain)
	{
		if (is_array($chain)) {
			$this->chain = $chain;
		}
		return $this;
	}

	/**
     * Adds an array of filters to the filterchain
     *
     * @param array $chain
     * @return $this
     */
	public function addChain($chain)
	{
		if (is_array($chain)) {
			$this->chain = array_merge($this->chain, $chain);
		}
		return $this;
	}

	/**
     * Returns array with filtered data
     *
     * @param  mixed    $container
     * @param  array    $chain
     * @return filtered array
     */
	public function doFilter($container, $chain=array())
	{
		$result = array();
		if ($chain) {
			$this->chain = is_array($chain) ? $chain : array($chain);
		}
		foreach ($this->chain as $key => $filter) {
		    // class names with params are added as arrays
		    if (is_array($filter)) {
				$name = array_shift($filter);
				// can use built-in rules and $this->dir will be used
				if (strstr($name, '_') === false) {
				    $name = $this->dir . ucfirst($name);
				}
				$ref = new ReflectionClass($name);
				$filter = $ref->newInstanceArgs($filter);
				$this->chain[$key] = $filter;
				unset($ref);
		    }
		    $value = $this->chain[$key]->doFilter($container);
		    $name = $this->chain[$key]->getName();
			if (is_array($container)) {
				$container[$name] = $value;
			} elseif (is_object($container)) {
				$container->set($name, $value);
			} else {
				$container = $value;
			}
		}
		return $container;
    }

}
