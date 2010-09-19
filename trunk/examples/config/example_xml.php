<?php
include 'config.php';
include '../../A/autoload.php';

$config = new A_Config_Xml('example1.xml');
$config->loadFile();

dump($config);