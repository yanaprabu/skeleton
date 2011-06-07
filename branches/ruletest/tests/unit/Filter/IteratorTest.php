<?php
	
class Filter_IteratorTest extends UnitTestCase
{
	
	function testFilter_IteratorTolowerSingle()
	{
		$filter = new A_Filter_Tolower('foo', '');
		$filterIterator = new A_Filter_Iterator($filter);
		
		$data = 'FoO';
		$result = $filterIterator->doFilter($data);
		
		$this->assertEqual('foo', $result);
	}
	
	function testFilter_IteratorTolowerMultiple()
	{
		$filter = new A_Filter_Tolower(null, '');
		$filterIterator = new A_Filter_Iterator($filter);
		
		$data = array('fOo', 'BAR', 'baZ');
		$result = $filterIterator->doFilter($data);
		$this->assertEqual(array('foo', 'bar', 'baz'), $result);
	}
}
