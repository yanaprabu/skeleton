<?php

include 'config.php';
include 'A/Sql/Select.php';
include 'A/Db/Mysql.php';

$select = new A_Sql_Select();
echo $select
	->columns()
	->from('tablefoo')
	->join('foo0', 'bar0', 'LEFT')
	->leftjoin('foo', 'bar')->on('foo1.column1', 'column2')->on('OR', 'column3', 'column4')
	->innerjoin('foo2', 'bar2')->on('column5', 'column6')
	->innerjoin('foo3', 'bar3')->on(array('column7' => 'column8', 'column9' => 'column10'))
	->where('foo', 'bar');
echo "\n<br/>\n";

$select = new A_Sql_Select();
echo $select
	->columns()
	->from('tablefoo')
	->join('foo0', 'bar0', 'LEFT')->on('column5', 'column6')
	->where('foo', 'bar');
echo "\n<br/>\n";
	