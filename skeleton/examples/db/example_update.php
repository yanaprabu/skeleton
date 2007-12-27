<?php
include 'config.php';
include 'A/Db/Sql/Update.php';

#include 'A/Db/MySQL.php';
#$db = new A_Db_MySQL($configdata);

$update = new A_Db_Sql_Update();
$update->table('mytable')->set(array('foo'=>'foo', 'bar'=>'bar', 'baz'=>'baz'))->where('id', 1);
echo "A_Db_Sql_Update::execute=" . $update->execute() . '<br/>';

dump($update);