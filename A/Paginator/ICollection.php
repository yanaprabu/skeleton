<?php

interface A_Paginator_ICollection	{

	public function count();
	public function slice ($offset, $length);
	
}