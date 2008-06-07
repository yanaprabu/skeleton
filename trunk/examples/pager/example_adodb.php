<?php
include 'config.php';
#include 'adodb_lite/adodb.inc.php';
include 'adodb/adodb.inc.php';
include 'A/Pager/ADODB.php';

$username = '';
$password = '';
$database = '';
$db = ADONewConnection("mysql://$username:$password@localhost/$database");
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

// create a data object that has the interface needed by the Pager object
$datasource = new A_Pager_ADODB('SELECT id,title FROM faq', $db);

include 'example.php';

?>
