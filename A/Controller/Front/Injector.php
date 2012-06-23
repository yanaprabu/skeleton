<?php
/**
 * Injector.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Controller_Front_Injector
 *
 * Create object of this class to pass to Front Controller preFilter() method to set one or more properties in all dispatched Action objects with a given value.
 *
 * @package A_Controller
 */
class A_Controller_Front_Injector
{

	protected $properties;

	/**
	 * Parameters can be either as an array in the first argument, or as separate arguments
	 *
	 * @param array|string $property Array of properties or property name
	 * @param string $value Value of $property (not applicable if $property is set as a string)
	 */
	public function __construct($property, $value=null)
	{
		if (is_array($property)) {
			$this->properties = $property;
		} else {
			$this->properties[$property] = $value;
		}
	}

	public function run($controller)
	{
		foreach ($this->properties as $property => $value) {
			$controller->$property = $value;
		}
	}

}
