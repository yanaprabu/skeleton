<?php
error_reporting(E_ALL);

require_once('config.php');
require_once('A/Http/Request.php');
require_once('A/Filter/Set.php');
require_once('A/Filter/Alnum.php');
require_once('A/Filter/Alpha.php');
require_once('A/Filter/Digit.php');
require_once('A/Filter/Htmlentities.php');
require_once('A/Filter/Htmlspecialchars.php');
require_once('A/Filter/Length.php');
require_once('A/Filter/Regexp.php');
require_once('A/Filter/Smartquotes.php');
require_once('A/Filter/Substr.php');
require_once('A/Filter/Toupper.php');
require_once('A/Filter/Tolower.php');
require_once('A/Filter/Trim.php');

$request = new A_Http_Request();

$input = array('phone'=>' 123abc ', 'fax'=>'abc456', 'username' => 'Matthijs879', 'name'=>'mike');
echo '<h2>Input is</h2>';
dump($input);

$filter = new A_Filter_Set();
$filter->addFilter(new A_Filter_Trim()); // filters all
$filter->addFilter(new A_Filter_Digit(), array('phone','fax')); // gets only digits from phone and fax
$filter->addFilter(new A_Filter_Alpha(), array('username', 'name')); // gets only alphabetic characters from phone and fax
$filter->addFilter(new A_Filter_Length(5), array('username'));
$result = $filter->doFilter($input);

dump($result);

echo '<h2>A filter trim</h2>';
echo '<p>Whitespace is there:</p>';
echo '<span style="background:#ddd;">' . $input['phone'] . $input['fax'] .  '</span>';
echo '<p>Not anymore</p>';
echo '<span style="background:#ddd;">' . $result['phone'] . $result['fax'] . '</span>';

echo '<h2>A filter alnum</h2>';
$input = array('username' => 'Matt-=hijs!#879', 'name'=>'mike');
echo '<b>Before</b><br>';dump($input);
$filter = new A_Filter_Set();
$filter->addFilter(new A_Filter_Alnum(), array('username', 'name'));
$result = $filter->doFilter($input);
echo '<b>After</b><br>';dump($result);

echo '<h2>A filter alpha</h2>';
$input = array('username' => 'Matthijs879', 'name'=>'mike');
echo '<b>Before</b><br>';dump($input);
$filter = new A_Filter_Set();
$filter->addFilter(new A_Filter_Alpha(), array('username', 'name'));
$result = $filter->doFilter($input);
echo '<b>After</b><br>';dump($result);

echo '<h2>A filter digit</h2>';
$input = array('username' => 'Matthijs879', 'name'=>'478mi9ke');
echo '<b>Before</b><br>';dump($input);
$filter = new A_Filter_Set();
$filter->addFilter(new A_Filter_Digit(), array('username', 'name'));
$result = $filter->doFilter($input);
echo '<b>After</b><br>';dump($result);

echo '<h2>A filter htmlentities</h2>';
$input = array('name'=>' <b>mike</b>');
echo '<b>Before</b><br>';dump($input);
$filter = new A_Filter_Set();
$filter->addFilter(new A_Filter_Htmlentities(), array('name')); 
$result = $filter->doFilter($input);
echo '<b>After</b><br>';dump($result);

echo '<h2>A filter htmlspecialchars</h2>';
$input = array('name'=>' <b>mike> > </b>');
echo '<b>Before</b><br>';dump($input);
$filter = new A_Filter_Set();
$filter->addFilter(new A_Filter_Htmlspecialchars(), array('name')); 
$result = $filter->doFilter($input);
echo '<b>After</b><br>';dump($result);

echo '<h2>A filter length</h2>';
$input = array('username' => 'Matthijs879');
echo '<b>Before</b><br>';dump($input);
$filter = new A_Filter_Set();
$filter->addFilter(new A_Filter_Length(5), array('username'));
$result = $filter->doFilter($input);
echo '<b>After</b><br>';dump($result);

echo '<h2>A filter regex</h2>';
$input = array('username' => 'Matthijs879');
echo '<b>Before</b><br>';dump($input);
$filter = new A_Filter_Set();
$filter->addFilter(new A_Filter_Regexp('/[^a-z]+/'), array('username'));
$result = $filter->doFilter($input);
echo '<b>After</b><br>';dump($result);

echo '<h2>A filter substr</h2>';
$input = array('username' => 'Matthijs879', 'name'=>'mike');
echo '<b>Before</b><br>';dump($input);
$filter = new A_Filter_Set();
$filter->addFilter(new A_Filter_Substr(2,5), array('username', 'name'));
$result = $filter->doFilter($input);
echo '<b>After</b><br>';dump($result);

echo '<h2>A filter tolower and upper</h2>';
$input = array('firstname'=>'mikEY', 'lastname'=>'DAVIDson');
echo '<b>Before</b><br>';dump($input);
$filter = new A_Filter_Set();
$filter->addFilter(new A_Filter_Tolower(), array('firstname')); 
$filter->addFilter(new A_Filter_Toupper(), array('lastname')); 
$result = $filter->doFilter($input);
echo '<b>After</b><br>';dump($result);







