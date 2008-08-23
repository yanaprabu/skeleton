<?php
error_reporting(E_ALL);

require_once('config.php');
require_once('A/Http/Request.php');
require_once('A/Filter/Set.php');
require_once('A/Filter/Alpha.php');
require_once('A/Filter/Digit.php');
require_once('A/Filter/Trim.php');
require_once('A/Filter/Length.php');

$request = new A_Http_Request();

$input = array('phone'=>' 123abc ', 'fax'=>'abc456', 'username' => 'Matthijs879', 'name'=>'mike');
echo '<h3>Input is</h3>';
dump($input);
echo '<h3>Whitespace is there:</h3>';
echo '<span style="background:#ddd;">' . $input['phone'] . $input['fax'] .  '</span>';

$filter = new A_Filter_Set();
$filter->addFilter(new A_Filter_Trim()); // filters all
$filter->addFilter(new A_Filter_Digit(), array('phone','fax')); // gets only digits from phone and fax
$filter->addFilter(new A_Filter_Alpha(), array('username', 'name')); // gets only alphabetic characters from phone and fax
$filter->addFilter(new A_Filter_Length(5), array('username'));
$result = $filter->doFilter($input);

echo '<h3>Test filter</h3>';
dump($result);
echo '<h3>Whitespace has been trimmed:</h3>';
echo '<span style="background:#ddd;">' . $result['phone'] . $result['fax'] . '</span>';