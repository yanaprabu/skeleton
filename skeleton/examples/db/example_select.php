<?php

include 'config.php';
include 'A/Sql/Select.php';

$select = new A_Sql_Select();
$select->columns('foobar as bleh, foo.bar')
		 ->from(array('foobar', 'foo'))
		 ->where(array('id >=' => 1, 'foo NOT IN' => array(1,2,3,4,5,6)))
		 ->orWhere(array('foo > ' => 'bar'))
		 ->orWhere(array('foo' => 'cheetah'))
		 ->where('1=1');
 
echo "A_Sql_Select::render=" . $select->render() . '<br/>';

dump($select);

?>