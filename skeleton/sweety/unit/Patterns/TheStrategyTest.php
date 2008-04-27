<?php

require_once 'Patterns/AbstractTestCase.php';
require_once 'Patterns/TheStrategy/StrategyClient.php';
require_once 'Patterns/TheStrategy/Strategy.php';

class Patterns_TheStrategyTest extends Patterns_AbstractTestCase {
  private $_client;
  
  public function setUp() {
    $this->_client = new Patterns_TheStrategy_StrategyClient();
  }

  public function testStrategiesAreInvoked() {
    $strategy = $this->_createStrategy();
    $this->_mockery()->checking(Expectations::create()
      -> one($strategy)->transform('foo')
      );
    $this->_client->addStrategy($strategy);
    $this->_client->transform('foo');
  }

  public function testReturnValueIsChainedAlongStrategies() {
    $strategy1 = $this->_createStrategy();
    $strategy2 = $this->_createStrategy();
    $this->_mockery()->checking(Expectations::create()
      -> one($strategy1)->transform('foo') -> returns('<b>foo</b>')
      -> one($strategy2)->transform('<b>foo</b>') -> returns('<u><b>foo</b></u>')
      );
    $this->_client->addStrategy($strategy1);
    $this->_client->addStrategy($strategy2);
    $this->assertEqual('<u><b>foo</b></u>', $this->_client->transform('foo'));
  }
  
  private function _createStrategy() {
    return $this->_mockery()->mock('Patterns_TheStrategy_Strategy');
  }
}
