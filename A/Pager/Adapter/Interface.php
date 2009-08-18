<?php
/**
 * @package A_Pager
 */
interface A_Pager_Adapter_Interface	{

	public function getItems ($start, $size);
	public function getNumItems();

}