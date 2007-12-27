<?php
include 'config.php';
include 'A/Db/Sql/Delete.php';

#include 'A/Db/MySQL.php';
#$db = new A_Db_MySQL($configdata);

$delete = new A_Db_Sql_Delete();
$delete->table('mytable')->where('id', 1);
echo "A_Db_Sql_Delete::execute=" . $delete->execute() . '<br/>';

dump($delete);