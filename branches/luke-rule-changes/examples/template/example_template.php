<?php
error_reporting(E_ALL);
require_once('config.php');
require_once('A/Template/Include.php');
require_once('A/Template/Strreplace.php');

echo '<h3>Template is PHP file</h3>';
$template = new A_Template_Include('templates/example1.php');
$template->set('one', 'Hello 1');
$template->set('two', 'Happy 2');
$template->set('three', 'Lucky 3');
echo $template->render();

echo '<h3>Template is HTML file</h3>';
$template = new A_Template_Strreplace('templates/example1.html');
$template->set('{one}', 'Hello 1');
$template->set('{two}', 'Happy 2');
$template->set('{three}', 'Lucky 3');
echo $template->render();

echo '<h3>Template is HTML file with blocks</h3>';
$template->makeBlocks();
echo $template->render('one');
echo $template->render('two');
echo $template->render('three');

echo '<h3>Template is HTML file with blocks and array</h3>';
$data = array(
	0 => array(
		'one' => '0 one',
		'two' => '0 two',
		'three' => '0 three',
		),
	1 => array(
		'one' => '1 one',
		'two' => '1 two',
		'three' => '1 three',
		),
	2 => array(
		'one' => '2 one',
		'two' => '2 two',
		'three' => '2 three',
		),
	);
echo $template->renderArray($data);

?>