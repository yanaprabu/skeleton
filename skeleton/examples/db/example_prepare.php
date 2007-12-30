<?php
include 'config.php';
include 'A/Db/Prepare.php';

#include 'A/Db/MySQL.php';
#$db = new A_Db_MySQL($configdata);

$prepare = new A_Db_Prepare("INSERT mytable SET one=?, two='?', three=:three, three=?");
$prepare->bind('foo', array(':three'=>'faz'), 'bar', 'baz');
echo "A_Db_Prepare::execute=" . $prepare->toSQL() . '<br/>';

dump($prepare);