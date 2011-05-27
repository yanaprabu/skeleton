<?php

/**
 * @package A_Pager
 * @deprecated replaced by A_Pagination package
 * @see A_Pagination
 */
interface A_Pager_IDataContainer	{
	
	public function getNumRows();
	public function getRows($begin, $end);

}