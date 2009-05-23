<?php
/**
 * A_Pagination_Standalone
 *
 *
 *
 * @author Cory Kaufman, Christopher Thompson
 * @package A_Pagination
 * @version @package_version@
 */

include ('A/Pagination/Request.php');
include ('A/Pagination/View/Standard.php');

class A_Pagination_Standalone extends A_Pagination_View_Standard	{

	public function __construct ($adapter, $pageSize = false, $currentPage = false)	{
		parent::__construct (new A_Pagination_Request ($adapter, $pageSize, $currentPage));
	}

}