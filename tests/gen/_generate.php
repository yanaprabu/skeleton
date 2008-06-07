<?php
$targetdir = ''; //dirname(__FILE__) . '/'; 
$sourcedir = '../../A/';
$template = file_get_contents('../test_template.php');
$dirs = array(
	'.',
	'Cart',
	'Cart/Creditcard',
	'Cart/Payment',
	'Cart/Shipping',
	'Config',
	'Controller',
	'Controller/Action',
	'Controller/Form',
	'Controller/Front',
	'Db',
	'Db/Datamapper',
	'Email',
	'Filter',
	'Html',
	'Html/Form',
	'Http',
	'Logger',
	'Pager',
	'Rule',
	'Sql',
	'Template',
	'User',
	'User/Rule',
);
foreach($dirs as $dir) {
	$classbase = $dir != '.' ? str_replace('/', '_', $dir) . '_' : '';
	foreach(glob($sourcedir . $dir . '/*.php') as $classfile) {
		$class = $classbase . str_replace('.php', '', basename($classfile));
		$filename = $targetdir . $class . '_test.php';
		if (file_exists($filename)) {
			unlink($filename);
		}
		echo "Reading <strong>$classfile</strong> class <strong>A_$class</strong> writing <strong>$filename</strong><br/>\n";
		file_put_contents($filename, str_replace(array('xxx','yyy'), array($class,str_replace('_','/',$class)), $template));
	}
}