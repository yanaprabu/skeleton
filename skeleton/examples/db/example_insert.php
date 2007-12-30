<?php
include 'config.php';
include 'A/Db/Sql/Insert.php';

#include 'A/Db/MySQL.php';
#$db = new A_Db_MySQL($configdata);

$insert = new A_Db_Sql_Insert();
$insert->table('mytable')->values(array('foo'=>'foo', 'bar'=>'bar', 'baz'=>'baz'));
echo "A_Db_Sql_Insert::execute=" . $insert->toSQL() . '<br/>';

dump($insert);