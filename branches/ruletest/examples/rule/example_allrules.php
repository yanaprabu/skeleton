<?php
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', 1);
ini_set('log_errors', 'Off');
error_reporting(E_ALL);
require 'config.php';

$request = new A_Http_Request();
$validator = new A_Rule_Set();

// Alnum:
$validator->addRule('A_Rule_Alnum', 'alnum', 'Please fill in a valid alnum');
// Alpha:
$alpha = new A_Rule_Alpha('alpha', 'alpha is not alpha');
$validator->addRule($alpha, array('alpha'), array('alpha is not alpha'));
// Date:
$validator->addRule('A_Rule_Date', 'date', 'Please fill in a valid date');
// Digit:
$validator->addRule('A_Rule_Digit', 'digit', 'Please fill in a valid digit');
//Email:
$validator->addRule('A_Rule_Email', 'email', 'Please fill in a valid email');
// Inarray:
$validator->addRule('A_Rule_Inarray', array('cat','dog','fish'), 'inarray', 'Please pick one out of the 3 options'); 
// Iterator:
$alfa = new A_Rule_Alpha();
$validator->addRule(new A_Rule_Iterator($alfa, 'iterator', 'Please fill in a good iterator value'));
// length:
$validator->addRule('A_Rule_Length', 4, null, 'length', 'length must be {min} characters long');
// Match:
$validator->addRule('A_Rule_Match', 'match', 'match2', 'match and match2 must match');
// Notnull:
$validator->addRule('A_Rule_Notnull', 'notnull', 'Fill in something for notnull');
// Numeric:
$validator->addRule('A_Rule_Numeric', 'numeric', 'Fill in a number for numeric');
// Range:
$validator->addRule('A_Rule_Range', 10, 20, 'range', 'Please pick no between 10 and 20');
//Regexp:
$validator->addRule('A_Rule_Regexp', '/^[a-z0-9]+$/D', 'regexp', 'Fill in a correct format for regexp');


if ($validator->isValid($request)) {
	$errmsg = 'OK';
} else {
	$errmsg = print_r($validator->getErrorMsg(), 1);
}

?>
<html>
<head>
<title>Validator Example</title>
</head>
<body>
<h2>Validator Example</h2>

<form action="" method="post">
Status: <span style="color:red"><pre><?php echo $errmsg; ?></pre></span><br/>
alnum: <input type="text" name="alnum" value="<?php echo isset($_POST['alnum']) ? $_POST['alnum'] : null; ?>"/><br/>
alpha: <input type="text" name="alpha" value="<?php echo isset($_POST['alpha']) ? $_POST['alpha'] : null; ?>"/><br/>

date: <input type="text" name="date" value="<?php echo isset($_POST['date']) ? $_POST['date'] : null; ?>"/><br/>
digit: <input type="text" name="digit" value="<?php echo isset($_POST['digit']) ? $_POST['digit'] : null; ?>"/><br/>
email: <input type="text" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : null; ?>"/><br/>
inarray, pick "car", "dog" or "fish": <input type="text" name="inarray" value="<?php echo isset($_POST['inarray']) ? $_POST['inarray'] : null; ?>"/><br/>
iterator: (must be alpha)<input type="text" name="iterator[]" value="<?php echo isset($_POST['iterator'][0]) ? $_POST['iterator'][0] : null; ?>"/><br/>
iterator 2: (must be alpha)<input type="text" name="iterator[2]" value="<?php echo isset($_POST['iterator'][2]) ? $_POST['iterator'][2] : null; ?>"/><br/>
length, must be >4 characters: <input type="text" name="length" value="<?php echo isset($_POST['length']) ? $_POST['length'] : null; ?>"/><br/>
match: <input type="text" name="match" value="<?php echo isset($_POST['match']) ? $_POST['match'] : null; ?>"/><br/>
match2: <input type="text" name="match2" value="<?php echo isset($_POST['match2']) ? $_POST['match2'] : null; ?>"/><br/>
notnull: <input type="text" name="notnull" value="<?php echo isset($_POST['notnull']) ? $_POST['notnull'] : null; ?>"/><br/>
numeric: <input type="text" name="numeric" value="<?php echo isset($_POST['numeric']) ? $_POST['numeric'] : null; ?>"/><br/>
range: Number between 10 and 20: <input type="text" name="range" value="<?php echo isset($_POST['range']) ? $_POST['range'] : null; ?>"/><br/>
regexp: /^[a-z0-9]+$/ <input type="text" name="regexp" value="<?php echo isset($_POST['regexp']) ? $_POST['regexp'] : null; ?>"/><br/>
<input type="submit"/><br/>
</body>