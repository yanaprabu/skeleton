<?php

class Event_ManagerTest extends UnitTestCase {
	public function testMain() {
		$manager = new A_Event_Manager();
		
		$manager->addEventListener(new MyEventListener(), 'event1');
		$manager->addEventListener(new MyEventListener(), 'event1');
		
		$manager->addEventListener(new MyEventListener(), 'event2');
		
		$manager->addEventListener(new MyEventMultiListener());
		
		ob_start();
		$manager->fireEvent('event3');
		$manager->fireEvent('event1');
		$manager->fireEvent('event2');
		$output = ob_get_contents();
		ob_end_clean();
		
		$this->assertTrue($output == 'multi event3<br />single event1<br />single event1<br />single event2<br />multi event2<br />');
	}
}

class MyEventListener implements A_Event_Listener {
	public function onEvent($name, $object) {
		echo 'single ' . $name . '<br />';
		print_r($object);
	}
}
class MyEventMultiListener implements A_Event_MultiListener {
	public function onEvent($name, $object) {
		echo 'multi ' . $name . '<br />';
		print_r($object);
	}
	public function getEvents() {
		return array('event2', 'event3');
	}
}