<?php
include 'config.php';
include 'A/Session.php';
include 'A/Rule/Captcha.php';

$session = new A_Session();
$session->start();

$captcha = new A_Rule_Captcha('captcha', 'CAPTCHA', null, $session, null);
$captcha->generateCode(5);

$captcha = new A_Rule_Captcha_Image($captcha);
$captcha->out();