<?php
include 'config.php';
include 'A/Http/Response.php';
include 'A/Template.php';

$response = new A_Http_Response();

$view1 = new A_Http_View('block1');
$template1 = new A_Template_Strreplace('templates/block1.html');
$template1->set('title', 'Block One');
$template1->set('content', 'This is the content for block one. ');
$view1->setRenderer($template1);

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

$response->set('layout', $layout);
$response->set('title', 'Response Example');
$response->set('BASE', 'http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['SCRIPT_NAME']));
$response->setRenderer(new A_Template_Strreplace('templates/main.html'));

echo $response->render();
