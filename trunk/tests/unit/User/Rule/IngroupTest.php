<?php
include dirname(__FILE__) . '/../UserMock.php';

class User_Rule_IngroupTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testUser_Rule_Ingroup() {
		$groups_str = 'foo|bar';
		$groups_array = array('foo', 'bar');
		$forward = array('x');
		$field = 'access';
		$delimiter='|';
		
		$rule = new A_User_Rule_Ingroup('box|fox', $forward);
		
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
		$user->set($field, $groups_array);

		$rule->setGroups('baz|bat');
		$this->assertFalse($rule->isValid($user));
		$this->assertFalse($rule->setUser($user)->isValid());
		$this->assertEqual($rule->getErrorMsg(), $forward);
		
		$rule->setGroups('foo');
		$this->assertTrue($rule->isValid($user));
		$this->assertTrue($rule->setUser($user)->isValid());
		$this->assertEqual($rule->getErrorMsg(), array());
				
		$rule->setGroups('bar');
		$this->assertTrue($rule->isValid($user));
		$this->assertTrue($rule->setUser($user)->isValid());
		$this->assertEqual($rule->getErrorMsg(), array());

	}
	
}
