<?php
include_once dirname(__FILE__) . '/../UserMock.php';

class User_Rule_IslevelTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testUser_Rule_Islevel() {
  		$level = 5;
  		$forward = array('foo');
  		$field = 'level';
  		$rule = new A_User_Rule_Islevel($level, $forward, $field);
		
		$user = new UserMock();
		
		// all level checks will fail if not logged in
		$user->setLoggedIn(false);
		
		// level not set, not logged in
		$this->assertFalse($rule->isValid($user));
		$this->assertFalse($rule->setUser($user)->isValid());
		$this->assertEqual($rule->getErrorMsg(), $forward);
		
		// level less than, not logged in
		$user->set($field, 4);
		$this->assertFalse($rule->isValid($user));
		$this->assertFalse($rule->setUser($user)->isValid());
		$this->assertEqual($rule->getErrorMsg(), $forward);
		
		// level greater than, not logged in
		$user->set($field, 6);
		$this->assertFalse($rule->isValid($user));
		$this->assertFalse($rule->setUser($user)->isValid());
		$this->assertEqual($rule->getErrorMsg(), $forward);

		$user->setLoggedIn(true);
	
		// level less than, not logged in
		$user->set($field, 4);
		$this->assertFalse($rule->isValid($user));
		$this->assertFalse($rule->setUser($user)->isValid());
		$this->assertEqual($rule->getErrorMsg(), $forward);
		
		// level equal to
		$user->set($field, 5);
		$this->assertTrue($rule->isValid($user));
		$this->assertTrue($rule->setUser($user)->isValid());
		$this->assertEqual($rule->getErrorMsg(), '');
		
		// level greater than
		$user->set($field, 5);
		$this->assertTrue($rule->isValid($user));
		$this->assertTrue($rule->setUser($user)->isValid());
		$this->assertEqual($rule->getErrorMsg(), '');

	}

}
