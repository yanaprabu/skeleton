<?php
/**
 * Null.php
 *
 * @package  A_Pagination
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Pagination_Adapter_Null
 * 
 * Null datasource access class for pager.
 */
class A_Pagination_Adapter_Null implements A_Pagination_Adapter
{

	protected $numItems = 0;
	
	public function __construct($numItems)
	{
		$this->numItems = $numItems;
	}
	
	public function getNumItems()
	{
		return $this->numItems;
	}
	
	public function getItems()
	{
		return array();
	}
	
	public function setOrderBy($offset, $length)
	{
		return null;
	}

}
