<?php
include 'config.php';

$select = new A_Sql_Select();
$select->columns('foobar as bleh, foo.bar')
		 ->from(array('foobar', 'foo'))
		 ->where(array('id >=' => 1, 'foo NOT IN' => array(1,2,3,4,5,6)))
		 ->orWhere(array('foo > ' => 'bar'))
		 ->orWhere(array('foo' => 'cheetah'))
		 ->where('1=1');
echo "<br>" . $select->render() . '<br/>';

$select = new A_Sql_Select();
$select->columns('foo, baz')
         ->from(array('foobar'))
         ->where(Array("foo" => "bar"))
         ->where(Array("baz" => "qux"));
echo "<br>" . $select->render() . '<br/>';
 
$select = new A_Sql_Select();
$select->columns('foo, baz')
         ->from(array('foobar'))
         ->where(Array("foo" => "'bar'"))
         ->where(Array("baz" => "qux"));
echo "<br>" . $select->render() . '<br/>';
 
$select = new A_Sql_Select();
$select->columns('foo, baz')
         ->from(array('foobar'))
         ->where(Array("foo" => "'bar'", "time=NOW()", "foo>"=>42))
         ->where(Array("baz" => " AND 0) UNION SELECT ALL username, password FROM login /*"));
echo "<br>" . $select->render() . '<br/>';

$select = new A_Sql_Select();
$select->columns('foo, bar, baz')
		 ->from('foobar')
		 ->where(array('id >=' => 1))
		 ->orderBy(array('foo', 'bar'))
		 ->groupBy('baz');
echo "<br>" . $select->render() . '<br/>';

$select = new A_Sql_Select();
$select->columns('foo, bar, baz')
		 ->from('foobar')
		 ->where(array('id >=' => 1))
		 ->limit(5,10); //Select 5 rows with an offset of 10
echo "<br>" . $select->render() . '<br/>';