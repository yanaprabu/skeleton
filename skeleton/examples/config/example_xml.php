<?php
include 'config.php';
include 'A/Config/Xml.php';

$config = new A_Config_Xml('example1.xml');
$data = $config->loadFile();

dump($data);