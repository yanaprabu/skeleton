<?php

include_once 'A/Db/Pdo.php';
$db = new A_Db_Pdo($config['db']) or die ('Error: could not connect to DB');

