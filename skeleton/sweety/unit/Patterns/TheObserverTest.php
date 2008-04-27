<?php

require_once 'Patterns/AbstractTestCase.php';
require_once 'Patterns/TheObserver/Observable.php';
require_once 'Patterns/TheObserver/Observer.php';

class Patterns_TheObserverTest extends Patterns_AbstractTestCase {
  private $_observable;
  
  public function setUp() {
    $this->_observable = $this->_createObservable();
  }
  
  public function testAllObserversAreNotifedOfMessage() {
    $observer1 = $this->_createObserver();
    $observer2 = $this->_createObserver();
    $this->_mockery()->checking(Expectations::create()
      -> one($observer1)->notify('foo')
      -> one($observer2)->notify('foo')
      );

    $observable = $this->_createObservable();
    $observable->registerObserver($observer1);
    $observable->registerObserver($observer2);
    $observable->sendMessage('foo');
  }

  private function _createObservable() {
    return new Patterns_TheObserver_Observable();
  }

  private function _createObserver() {
    return $this->_mockery()->mock('Patterns_TheObserver_Observer');
  }
}
