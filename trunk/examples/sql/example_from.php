<?php
include 'config.php';

$from = new A_Sql_From();
echo $from
	->table('table1')
	->join('table2')
	->on('column5', 'column6')
	->render();
echo "\n<br/>\n";
dump($from);

$from = new A_Sql_From();
echo $from
	->table('table1')
	->join('table2', 'RIGHT')
	->on('column5', 'column6')
	->render();
echo "\n<br/>\n";
dump($from);

$from = new A_Sql_From();
echo $from
	->table('table1')
	->join('table2', 'table1', 'LEFT')
	->on('column5', 'column6')
	->render();
echo "\n<br/>\n";
dump($from);

$from = new A_Sql_From();
echo $from
	->table('foo0')
	->join('foo0', 'bar0', 'LEFT')
	->on('column1', 'column2')
	->join('bar0', 'baz0', 'OUTER')
	->on('column3', 'column4')
#	->leftjoin('foo', 'bar')->on('foo1.column1', 'column2')->on('OR', 'column3', 'column4')
#	->innerjoin('foo2', 'bar2')->on('column5', 'column6')
#	->innerjoin('foo3', 'bar3')->on(array('column7' => 'column8', 'column9' => 'column10'))
	->render();
echo "\n<br/>\n";
dump($from);

