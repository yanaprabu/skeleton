<?php
include 'config.php';

$delete = new A_Sql_Delete();
$delete->table('mytable')->where('id =', 1);
echo "A_Sql_Delete::render=" . $delete->render() . '<br/>';

dump($delete);