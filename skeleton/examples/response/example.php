<?php
include '../../A/Http/Response.php';
include '../../A/Template.php';

$response = new A_Http_Response();

$template = new A_Template_Strreplace('templates/layout.html');
$template->set('title', 'Response Example');
$template->set('content', 'This is the content for the response example. ');
$response->setRenderer($template);

$response1 = new A_Http_ResponseChild('block1');
$template1 = new A_Template_Strreplace('templates/block1.html');
$template1->set('title', 'Block One');
$template1->set('content', 'This is the content for block one. ');
$response1->setRenderer($template1);

$response2 = new A_Http_ResponseChild('block2');
$template2 = new A_Template_Strreplace('templates/block2.html');
$template2->set('title', 'Block Two');
$template2->set('content', 'This is the content for block two. ');
$response2->setRenderer($template2);

$response->addChild($response1);
$response->addChild($response2);

$response->run($Locator);
$template->setTemplate($response->render());
$template->clear();
$template->set('BASE', dirname($_SERVER['SCRIPT_FILENAME']));
echo $template->render();
