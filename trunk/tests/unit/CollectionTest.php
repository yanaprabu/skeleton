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
		$return = $collection->import(array('bar' => 'foo', 'baz' => array('foo' => 'bar'), 'barfoo' => 'foo'));
		
		$this->assertEqual($collection->get('foo'), 'bar');
		$this->assertEqual($collection->get('baz')->get('foobar'), 'barfoo');
		$this->assertEqual($collection->get('baz')->get('foo'), 'bar');
		$this->assertEqual($collection->get('bar'), 'foo');
		$this->assertEqual($collection->get('barfoo'), 'foo');
		$this->assertEqual($collection->count(), 4);
		$this->assertEqual($collection->get('baz')->count(), 2);
		
		// make sure import() returns self
		$this->assertEqual($return, $collection);
	}
	
	public function testGet()
	{
		$collection = $this->createFlatCollection();
		$collection->set('foo', 'bar');
		
		$this->assertEqual($collection->get('foo'), 'bar');
		$this->assertEqual($collection->get('baz', 'default'), 'default');
		$this->assertTrue($collection->get('baz') === null);
	}
	
	public function testSet()
	{
		$collection = new A_Collection();
		
		$collection->set('foo', 'bar');
		$this->assertEqual($collection->get('foo'), 'bar');
		
		$collection->set('foo', 'baz', 'bar');
		$this->assertEqual($collection->get('foo'), 'baz');
		
		$collection->set('foo', null, 'bar');
		$this->assertEqual($collection->get('foo'), 'bar');
		
		$collection->set('foo', null, null);
		$this->assertFalse($collection->has('foo'));
		
		// make sure set() returns self
		$return = $collection->set(null, null, null);
		$this->assertEqual($return, $collection);
	}
	
	public function testAdd()
	{
		$collection = new A_Collection();
		
		$collection->add('foo');
		$this->assertEqual($collection->get(0), 'foo');
		
		$collection->add('bar', false);
		$this->assertEqual($collection->get(1), 'bar');
		
		$collection->add('baz', true);
		$this->assertEqual($collection->get(2), 'baz');
		
		$collection = new A_Collection();
		
		$collection->add(null);
		$this->assertTrue($collection->get(0, 'foo'), null);
		
		$collection->add(null, false);
		$this->assertTrue($collection->get(1, 'foo'), null);
		
		$collection->add(null, true);
		$this->assertFalse($collection->has(2));
		
		// make sure add() returns self
		$return = $collection->add(null);
		$this->assertEqual($return, $collection);
	}
	
	public function testRemove()
	{
		$collection = new A_Collection();
		
		$collection->set('foo', 'bar');
		$this->assertEqual($collection->get('foo'), 'bar');
		
		$collection->remove('foo');
		$this->assertFalse($collection->has('foo'));
		
		// make sure remove() returns self
		$return = $collection->remove(null);
		$this->assertEqual($return, $collection);
	}
	
	public function testCount()
	{
		$collection = new A_Collection();
		
		$this->assertEqual($collection->count(), 0);
		
		$collection->add('foo');
		$this->assertEqual($collection->count(), 1);
		
		$collection->set('bar', 'baz');
		$this->assertEqual($collection->count(), 2);
		
		$collection->set('baz', null);
		$this->assertEqual($collection->count(), 2);
		
		$collection->remove(0);
		$this->assertEqual($collection->count(), 1);
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
	
	public function testSlice()
	{
		$collection = new A_Collection(array('foo' => 'bar', 'baz' => 'foobar', 'barfoo' => 'foobarbaz', 'bazbarfoo' => 'foobazbar'));
		
		$slice = $collection->slice(1, 2);
		
		$this->assertEqual($slice->count(), 2);
		$this->assertEqual($slice->get('baz'), 'foobar');
		$this->assertEqual($slice->get('barfoo'), 'foobarbaz');
		
		$slice = $collection->slice(1);
		
		$this->assertEqual($slice->count(), 3);
		$this->assertEqual($slice->get('baz'), 'foobar');
		$this->assertEqual($slice->get('barfoo'), 'foobarbaz');
		$this->assertEqual($slice->get('bazbarfoo'), 'foobazbar');
	}
	
	public function testHas()
	{
		$collection = new A_Collection(array('foo' => 'bar'));
		
		$this->assertTrue($collection->has('foo'));
		$this->assertFalse($collection->has('baz'));
	}
	
	public function testToArray()
	{
		$collection = new A_Collection(array('foo' => 'bar', 'baz'));
		$array = $collection->toArray();
		
		$this->assertTrue(is_array($array));
		$this->assertEqual($array['foo'], 'bar');
		$this->assertEqual($array[0], 'baz');
	}
	
	public function testNestedJoin()
	{
		$collection = $this->createNestedCollection();
		$joined = $collection->join('');
		
		$this->assertEqual($joined, 'foobarfoobarbarfooblah');
	}
	
	public function testFlatJoin()
	{
		$collection = $this->createFlatCollection();
		$joined = $collection->join('');
		
		$this->assertEqual($joined, 'foobarfoobar');
	}
	
	public function testIterator()
	{
		$array = array('foo' => 'bar', 'baz' => 'foobar');
		$collection = new A_Collection($array);
		
		// do twice to make sure there are no problems with reset()
		for ($i = 0; $i < 2; $i++) {
			foreach ($collection as $key => $value) {
				$this->assertEqual($value, $array[$key]);
			}
		}
	}
	
	public function testArrayAccess()
	{
		$collection = new A_Collection();
		
		$this->assertFalse(isset($collection['foo']));
		
		$collection['foo'] = 'bar';
		$this->assertTrue(isset($collection['foo']));
		$this->assertEqual($collection['foo'], 'bar');
		
		unset($collection['foo']);
		$this->assertFalse(isset($collection['foo']));
	}
	
	public function test__ToString()
	{
		$collection = $this->createNestedCollection();
		
		$this->assertEqual(strval($collection), $collection->join(','));
	}
	
	public function test__Set()
	{
		$collection = new A_Collection();
		
		$collection->foo = 'bar';
		$this->assertEqual($collection->get('foo'), 'bar');
		
		$collection->foo = null;
		$this->assertFalse($collection->has('foo'));
	}
	
	public function test__Get()
	{
		$collection = new A_Collection(array('foo' => 'bar'));
		
		$this->assertEqual($collection->foo, 'bar');
	}
	
	// NON-TEST UTILITY METHODS
	
	private function createNestedCollection()
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
	
	private function createFlatCollection()
	{
		return new A_Collection(array(
			'foo',
			'bar',
			'foobar'
		));
	}
}