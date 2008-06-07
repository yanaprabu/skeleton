<?php
error_reporting(E_ALL);
require_once('config.php');
require_once('A/Http/Request.php');
require_once('A/Rule.php');
require_once('A/Rule/Match.php');

$request = new A_Http_Request();

$rule = new A_Rule_Match('one', 'two', 'Fields do not match');

if ($rule->isValid($request)) {
	$errmsg = 'OK';
} else {
	$errmsg = $rule->getErrorMsg();
}

?>
<html>
<head>
<title>Rule Example</title>
</head>
<body>
<h2>Rule Example</h2>
The rule will match if the two values are equal.<br/>
<form action="" method="post">
Status: <span style="color:red"><?php echo $errmsg; ?></span><br/>
<input type="text" name="one" value="<?php echo isset($_POST['one']) ? $_POST['one'] : null; ?>"/><br/>
<input type="text" name="two" value="<?php echo isset($_POST['two']) ? $_POST['two'] : null; ?>"/><br/>
<input type="submit"/><br/>
</body>