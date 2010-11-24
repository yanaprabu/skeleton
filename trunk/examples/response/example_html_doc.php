<?php
include 'config.php';
include '../../A/Locator.php';

$Locator = new A_Locator();
$Locator->autoload();

$Response = new A_Http_Response();

$view1 = new A_Http_View();
$view1->set('title', 'Block One');
$view1->set('content', 'This is the content for block one. ');
$view1->setTemplate('block1.php');		// MVC objects assume a templates/ directory

$view2 = new A_Http_View('block2');
$template2 = new A_Template_Strreplace('templates/block2.html');
$template2->set('title', 'Block Two');
$template2->set('content', 'This is the content for block two. ');
$view2->setRenderer($template2);

$layout = new A_Http_View('layout');
$layout->set('block1', $view1);
$layout->set('block2', $view2);
$layout->set('content', 'This is the content for the layout. ');
$layout->setRenderer(new A_Template_Strreplace('templates/layout.html'));

#$doc = new A_Html_Doc(array('doctype'=>'HTML_5'));
$doc = new A_Html_Doc();
$doc->setDoctype(A_Html_Doctype::HTML_5);
$Response->set('layout', $layout);
$Response->set('title', 'Response Example');
$Response->set('BASE', 'http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['SCRIPT_NAME']));
$Response->setRenderer($doc);

$Response->set('body', '<h1>This content goes in the body.</h1>');

echo $Response->render();
