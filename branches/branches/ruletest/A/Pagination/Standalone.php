<?php
/**
 * Standalone.php
 *
 * @package  A_Pagination
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author   Cory Kaufman, Christopher Thompson
 */

/**
 * A_Pagination_Standalone
 */
class A_Pagination_Standalone extends A_Pagination_View_Standard	{

	public function __construct ($adapter, $pageSize = false, $currentPage = false)	{
		parent::__construct (new A_Pagination_Request ($adapter, $pageSize, $currentPage));
	}

}