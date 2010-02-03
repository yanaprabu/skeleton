<?php

class Sql_Statement_MockDb {
}

class Sql_Statement_MockStatement extends A_Sql_Statement {
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
        $statement = new Sql_Statement_MockStatement();
        $db = new Sql_Statement_MockDb();
        $statement->setDb($db);
        $this->assertEqual($statement->callNotifyListeners(), null);
    }
 
}
