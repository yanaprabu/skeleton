<?php
include 'config.php';

$configdata = array(
	'APP' => '/path/to/app/',
	'BASE' => 'http://www.example.com',
);
$config = new A_Collection($configdata);
$config->import(simplexml_load_file("example.xml"), 'xml');
$config->import(parse_ini_file("example.ini", true), 'ini');

echo "\$config->xml->first_section->animal = {$config->xml->first_section->animal}<br/>";
echo "\$config->ini->first_section->animal = {$config->ini->first_section->animal}<br/>";
dump($config);