<?php
include 'config.php';
include 'A/Pager/MySQL.php';

$username = '';
$password = '';
$database = '';
$link = mysql_connect('localhost', $username, $password);
$errmsg = mysql_error($link);
mysql_select_db($database, $link);

// create a data object that has the interface needed by the Pager object
$datasource = new A_Pager_MySQL('SELECT id,title FROM mytable', $link);

include 'example.php';

?>
