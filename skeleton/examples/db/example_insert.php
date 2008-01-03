<?php
include 'config.php';
include 'A/Sql/Insert.php';

#include 'A/Db/MySQL.php';
#$db = new A_Db_MySQL($configdata);

$insert = new A_Sql_Insert();
$insert->table('mytable')->values(array('foo'=>'Boo', 'bar'=>'Ca\'r', 'baz'=>'Caz'));
echo "A_Sql_Insert::execute=" . $insert->render() . '<br/>';

dump($insert);