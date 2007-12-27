<?php
include 'config.php';
include 'A/Db/Sql/Select.php';

#include 'A/Db/MySQL.php';
#$db = new A_Db_MySQL($configdata);

$select = new A_Db_Sql_Select();
$select->columns(array('foo', 'bar', 'baz'))->from('mytable')->where('id', 1);
echo "A_Db_Sql_Select::execute=" . $select->execute() . '<br/>';

dump($select);