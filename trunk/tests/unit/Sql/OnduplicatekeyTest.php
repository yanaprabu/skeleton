<?php

class Sql_OnduplicatekeyTest extends UnitTestCase {

	public function testSql_OnduplicatekeySameValue() {

		$Sql_Onduplicatekey = new A_Sql_Onduplicatekey(array('foo', 'bar', 'baz'));
		$query = $Sql_Onduplicatekey->render();

		$this->assertEqual($query, 'ON DUPLICATE KEY UPDATE `foo` = VALUES(`foo`), `bar` = VALUES(`bar`), `baz` = VALUES(`baz`)');

	}

	public function testSql_OnduplicatekeyDifferentValue() {

		$Sql_Onduplicatekey = new A_Sql_Onduplicatekey(array(
			'foo' => 'This is a value',
			'bar' => 'This value has a \' in it',
		));
		$query = $Sql_Onduplicatekey->render();

		$this->assertEqual($query, 'ON DUPLICATE KEY UPDATE `foo` = \'This is a value\', `bar` = \'This value has a \\\' in it\'');

	}

}
