<?php
/**
 * @package A_Pager
 * @deprecated replaced by A_Pagination package
 * @see A_Pagination
 */
interface A_Pager_Adapter_Interface	{

	public function getItems ($start, $size);
	public function getNumItems();

}