<?php

class A_CollectionTest extends UnitTestCase
{
	
	public function testDeepJoin()
	{
		$collection = $this->createDeepCollection();
		$joined = $collection->join('');
		
		$this->assertEqual($joined, 'foobarfoobarbarfooblah');
	}
	
	public function testShallowJoin()
	{
		$collection = $this->createShallowCollection();
		$joined = $collection->join('');
		
		$this->assertEqual($joined, 'foobarfoobar');
	}
	
	// NON-TEST UTILITY METHODS
	
	public function createDeepCollection()
	{
		return new A_Collection(array(
			'foo',
			'bar',
			array(
				'foobar',
				array(
					'barfoo'
				)
			),
			'blah'
		));
	}
	
	public function createShallowCollection()
	{
		return new A_Collection(array(
			'foo',
			'bar',
			'foobar'
		));
	}
}