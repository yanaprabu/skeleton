<?php
include 'config.php';
include 'A/Sql/Delete.php';

#include 'A/Db/MySQL.php';
#$db = new A_Db_MySQL($configdata);

$delete = new A_Sql_Delete();
$delete->table('mytable')->where('id =', 1);
echo "A_Sql_Delete::render=" . $delete->render() . '<br/>';

dump($delete);