<?php
include 'config.php';

$update = new A_Sql_Update();
$update->table('mytable')->set(array('foo'=>'foo', 'bar'=>'bar', 'baz'=>'baz'))->where('id', 1);
echo "A_Sql_Update::render=" . $update->render() . '<br/>';

dump($update);