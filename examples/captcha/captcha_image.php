<?php
include 'config.php';
include dirname(__FILE__) . '/../../A/autoload.php';
#include 'A/Session.php';
#include 'A/Rule/Captcha.php';

$session = new A_Session();
$session->start();
#echo "session_id=" . session_id() . '<br/>';

$captcha = new A_Rule_Captcha('captcha', 'CAPTCHA', null, $session, null);
$captcha->generateCode(5);

#dump($captcha);
#dump($session);
#echo "<br/>Code=" . $captcha->getCode();
#exit;

$image = new A_Rule_Captcha_Image($captcha);
#dump($session);
#echo "<br/>Code=" . $captcha->getCode();
#exit;

$image->out();