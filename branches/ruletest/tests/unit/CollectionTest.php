<?php

class A_CollectionTest extends UnitTestCase
{
	public function testConstruct()
	{
		$collection = new A_Collection(array('foo' => 'bar', 'baz' => array('foobar' => 'barfoo')));
		
		$this->assertEqual($collection->get('foo'), 'bar');
		$this->assertEqual($collection->get('baz')->get('foobar'), 'barfoo');
		$this->assertEqual($collection->count(), 2);
		$this->assertEqual($collection->get('baz')->count(), 1);
	}
	
	public function testImport()
	{
		$collection = new A_Collection(array('foo' => 'bar', 'baz' => array('foobar' => 'barfoo'), 'bar' => 'baz'));
		$collection->import(array('bar' => 'foo', 'baz' => array('foo' => 'bar'), 'barfoo' => 'foo'));
		
		$this->assertEqual($collection->get('foo'), 'bar');
		$this->assertEqual($collection->get('baz')->get('foobar'), 'barfoo');
		$this->assertEqual($collection->get('baz')->get('foo'), 'bar');
		$this->assertEqual($collection->get('bar'), 'foo');
		$this->assertEqual($collection->get('barfoo'), 'foo');
		$this->assertEqual($collection->count(), 4);
		$this->assertEqual($collection->get('baz')->count(), 2);
	}
	
	public function testGet()
	{
		$collection = $this->createShallowCollection();
		$collection->set('foo', 'bar');
		
		$this->assertEqual($collection->get('foo'), 'bar');
		$this->assertEqual($collection->get('baz', 'default'), 'default');
		$this->assertTrue($collection->get('baz') === null);
	}
	
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
	
	public function testUserSort()
	{
		$items = array(
			array('name' => 'bob', 'age' => 40),
			array('name' => 'mike', 'age' => 23),
			array('name' => 'jim', 'age' => 37)
		);
		$collection = new A_Collection($items);
		
		$this->assertTrue($collection->get(0)->get('name') == $items[0]['name']);
		$this->assertTrue($collection->get(1)->get('name') == $items[1]['name']);
		$this->assertTrue($collection->get(2)->get('name') == $items[2]['name']);
		
		$return = $collection->userSort(function ($item1, $item2) {
			return $item1->get('age') - $item2->get('age');
		});
		// make sure userSort returns it's object
		$this->assertTrue($return == $collection);
		
		$this->assertTrue($collection->get(0)->get('name') == $items[1]['name']);
		$this->assertTrue($collection->get(1)->get('name') == $items[2]['name']);
		$this->assertTrue($collection->get(2)->get('name') == $items[0]['name']);
	}
	
	// NON-TEST UTILITY METHODS
	
	private function createDeepCollection()
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
	
	private function createShallowCollection()
	{
		return new A_Collection(array(
			'foo',
			'bar',
			'foobar'
		));
	}
}