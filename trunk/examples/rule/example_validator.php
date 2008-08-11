<?php
error_reporting(E_ALL);
require_once('config.php');
require_once('A/Http/Request.php');
require_once('A/Rule/Set.php');
require_once('A/Rule/Alpha.php');
require_once('A/Rule/Length.php');
require_once('A/Rule/Match.php');
require_once('A/Rule/Numeric.php');

$request = new A_Http_Request();

$validator = new A_Rule_Set();
$alpha = new A_Rule_Alpha('one', 'One is not alpha');
$length = new A_Rule_Length('two', 5, null, 'Two must be 5 characters long');
$match = new A_Rule_Match('one', 'two', 'Fields do not match');
$numeric = new A_Rule_Numeric('two', 'Two is not numeric');

$validator->addRule($alpha);
$validator->addRule($match);
#$validator->addRule($length);
$validator->addRule('A_Rule_Length', 'two', 5, null, 'Two must be 5 characters long');

if ($validator->isValid($request)) {
	$errmsg = 'OK';
} else {
	$errmsg = print_r($validator->getErrorMsg(), 1);
}

?>
<html>
<head>
<title>A_Rule_Set and Rules Example</title>
</head>
<body>
<h2>Validator Example</h2>
One must be Alpha. Two must be Numeric. The two values must match.<br/>
<form action="" method="post">
Status: <span style="color:red"><pre><?php echo $errmsg; ?></pre></span><br/>
One: <input type="text" name="one" value="<?php echo isset($_POST['one']) ? $_POST['one'] : null; ?>"/><br/>
Two: <input type="text" name="two" value="<?php echo isset($_POST['two']) ? $_POST['two'] : null; ?>"/><br/>
<input type="submit"/><br/>
</body>