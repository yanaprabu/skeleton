<?php
require_once('A/Sql/Statement.php');

class MockDb {
}

class MockStatement extends A_Sql_Statement {
	public function callNotifyListeners() {
		return $this->notifyListeners();
	}
}

class Sql_StatementTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
    function testEmptyStringReturnsEmpty() {
        $statement = new MockStatement();
        $db = new MockDb();
        $statement->setDb($db);
        $this->assertEqual($statement->callNotifyListeners(), null);
    }
 
}
