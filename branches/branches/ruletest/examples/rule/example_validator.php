<?php
error_reporting(E_ALL);
require 'config.php';

$request = new A_Http_Request();

$validator = new A_Rule_Set();
$alpha = new A_Rule_Alpha('one', 'One is not alpha');
$length = new A_Rule_Length('two', 5, null, 'Two must be 5 characters long');
$match = new A_Rule_Match('one', 'two', 'Fields do not match');
$numeric = new A_Rule_Numeric('two', 'Two is not numeric');
$regexp = new A_Rule_Regexp('/^[A-Za-z0-9]+$/D', 'three', 'Three must match ^[A-Za-z0-9]+$');

$validator->addRule($alpha);
$validator->addRule($match);
#$validator->addRule($length);
$validator->addRule('A_Rule_Length', 'two', 5, null, 'Two must be 5 characters long');
$validator->addRule($regexp);	

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
Three: <input type="text" name="three" value="<?php echo isset($_POST['three']) ? $_POST['three'] : null; ?>"/><br/>
<input type="submit"/><br/>
</body>