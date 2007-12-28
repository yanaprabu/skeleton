<?php

include 'config.php';
include 'A/Db/Sql/Select.php';

$select = new A_Db_Sql_Select();
$select->columns('foo', 'fee', 'fan')->from('foobar')->where(array('id' => 1, 'foo' => 'bar'));
 
echo "A_Db_Sql_Select::execute=" . $select->execute() . '<br/>';

dump($select);

?>