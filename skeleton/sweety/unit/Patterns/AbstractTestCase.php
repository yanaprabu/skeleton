<?php

abstract class Patterns_AbstractTestCase extends UnitTestCase {
  private $_mockery;
  
  public function after($method) {
    parent::after($method);
    $this->_mockery()->assertIsSatisfied();
    $this->_mockery = null;
  }

  protected function _mockery() {
    if (!isset($this->_mockery)) {
      $this->_mockery = new Mockery();
    }
    return $this->_mockery;
  }
}
