<?php
include 'config.php';
include '../../A/autoload.php';
?>
<html>
<head>
</head>
<body>
    <form action="" method="post">
<?php
$session = new A_Session();
$session->start();
$request = new A_Http_Request();

$captcha = new A_Rule_Captcha('captcha', 'Captcha error.', null, $session);

# was there a reCAPTCHA response?
if (! $captcha->isValid($request)) {
	echo $captcha->getErrorMsg();
} else {
	echo "Captcha is valid.";
}
echo $captcha->render();
?>
    Type the code:
    <input type="text" name="captcha" value="" size="10"/>
    <br/>
    <input type="submit" value="submit" />
    </form>
</body>
</html>
<?php
dump($session, 'SESSSION: ');
?>