<?php
include 'config.php';
$ConfigArray['PATH'] = dirname(__FILE__) . '/';

include $ConfigArray['PATH'] . '../../A/Locator.php';
$Locator = new A_Locator();
$Locator->autoload();

$db = new A_Db_Mysqli($ConfigArray['DBDSN']);
$db->connect();
if ($db->isError()) die('ERROR: ' . $db->getMessage());

#dump($project->sql);
#dump($project->toArray());
