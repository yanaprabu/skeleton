<?php
/**
 * Itemdata.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Cart_Itemdata
 *
 * @package A_Cart
 */
class A_Cart_Itemdata
{

	protected $name = '';
	protected $value = '';
	protected $options = null;

	/**
	 * Constructor
	 *
	 * @param string $name
	 * @param mixed $value
	 * @param mixed $options
	 */
	public function __construct($name, $value, $options=null)
	{
		$this->name = $name;
		$this->value = $value;
		$this->options = $options;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return($this->name);
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return($this->value);
	}

	/**
	 * @return mixed
	 */
	public function getOptions()
	{
		return($this->options);
	}

}
