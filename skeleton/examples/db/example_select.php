<?php

include 'config.php';
include 'A/Sql/Select.php';

$select = new A_Sql_Select();

$select->columns('foobar as foo')
		 ->from('foobar')
		 ->where(array('id >=' => 1, 
		 					'foo' => 'bar', 
							'foo NOT IN' => array(1,2,3,4,5,6))
			);
 
echo "A_Sql_Select::render=" . $select->render() . '<br/>';

dump($select);

?>