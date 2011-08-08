<?php
/**
 * Adapter.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Pagination_Adapter
 * 
 * Interface for pagination adapters
 * 
 * @package A_Pagination
 */
interface A_Pagination_Adapter
{

	public function getItems($offset, $length);
	public function getNumItems();
	public function setOrderBy($field, $descending=false);

}
