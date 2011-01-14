<?php

// listener classes to hold values that can be checked after firing events
class MyEventListener implements A_Event_Listener {
	public $name = '';
	public $eventName = '';
	public $eventObject = null;
	
	public function __construct($name) {
		$this->name = $name;
	}
	public function onEvent($name, $object) {
		$this->eventName = $name;
		$this->eventObject = $object;
#		echo 'single ' . $name . '<br />';
		print_r($object);
		return $this->eventName;
	}
	public function anotherEvent($name, $object) {
		$this->eventName = "anotherEvent:$name";
		$this->eventObject = $object;
#		echo 'single ' . $name . '<br />';
#		print_r($object);
		return $this->eventName;
	}
}
class MyEventMultiListener implements A_Event_Listener {
	public $name = '';
	public $eventName = '';
	public $eventObject = null;
		
	public function __construct($name) {
		$this->name = $name;
	}
	public function onEvent($name, $object) {
		$this->eventName = $name;
		$this->eventObject = $object;
		return $this->eventName;
	}
	public function getEvents() {
		return array('event2', 'event3');
	}
}

// class to pass to event handlers
class Event_ManagerTest_ValueObject {
	public $data = '';
}

class Event_ManagerTest extends UnitTestCase {

	public function testEvents() {
		$manager = new A_Event_Manager();
		
		$listener1 = new MyEventListener('listener1');
		$manager->addEventListener('event1', $listener1);		// single event
		$listener2 = new MyEventListener('listener2');
		$manager->addEventListener('event2', $listener2);		// single event
		
		$listener3 = new MyEventListener('listener3');
		$manager->addEventListener(array('event1', 'event3'), $listener3);		// multiple events
		
		$listener4 = new MyEventMultiListener('listener4');		// internally specifies listening for array('event2', 'event3')
		$manager->addEventListener($listener4);
		
		// intialized state
		$this->assertTrue($listener1->eventName == '');
		$this->assertTrue($listener2->eventName == '');
		$this->assertTrue($listener3->eventName == '');
		$this->assertTrue($listener4->eventName == '');
		
		$manager->fireEvent('event1');
		$this->assertTrue($listener1->eventName == 'event1');
		$this->assertTrue($listener2->eventName == '');
		$this->assertTrue($listener3->eventName == 'event1');
		$this->assertTrue($listener4->eventName == '');
		
		$manager->fireEvent('event2');
		$this->assertTrue($listener1->eventName == 'event1');
		$this->assertTrue($listener2->eventName == 'event2');
		$this->assertTrue($listener3->eventName == 'event1');
		$this->assertTrue($listener4->eventName == 'event2');
		
		$manager->fireEvent('event3');
		$this->assertTrue($listener1->eventName == 'event1');
		$this->assertTrue($listener2->eventName == 'event2');
		$this->assertTrue($listener3->eventName == 'event3');
		$this->assertTrue($listener4->eventName == 'event3');

	}

	public function testUserFuncParam() {
		$manager = new A_Event_Manager();
		
		$listener1 = new MyEventListener('listener1');
		$manager->addEventListener('event1', array($listener1, 'anotherEvent'));
		
		$this->assertTrue($listener1->eventName == '');
		
		$result = $manager->fireEvent('event1');
		$this->assertTrue($listener1->eventName == 'anotherEvent:event1');
		$this->assertTrue($result == array('anotherEvent:event1'));
	}

	public function testClosure() {
		$manager = new A_Event_Manager();
		
		// add a closure that sets an object's property
		$manager->addEventListener('event1', function($name, $object) { $object->data = $name; return $name; });
		
		// create object and pass to event
		$object = new Event_ManagerTest_ValueObject();
		$result = $manager->fireEvent('event1', $object);
		$this->assertTrue($object->data == 'event1');
		$this->assertTrue($result == array('event1'));
	}

	public function testEventsReturnValues() {
		$manager = new A_Event_Manager();
		
		$manager->addEventListener('event1', function($name, $object) { return 'listener1'; });
		$manager->addEventListener('event1', function($name, $object) { return 'listener2'; });
		
		$result = $manager->fireEvent('event1');
		$this->assertTrue($result == array('listener1', 'listener2'));
		
	}

	public function testEventsCancel() {
		$manager = new A_Event_Manager();
		
		$manager->addEventListener('event1', function($name, $object) { return 'listener1'; });
		$manager->addEventListener('event1', function($name, $object) { return false; });
		$manager->addEventListener('event1', function($name, $object) { return 'listener3'; });
		
		$result = $manager->fireEvent('event1');
		$this->assertTrue($result == array('listener1'));
		
	}

}
