<?php
$xmlstr = <<<XML
<?xml version='1.0' standalone='yes'?>
<map>
 <route name=''>
  <param>command</param>
  <param>action</param>
  <param>id</param>
 </route>
 <route name='date'>
  <param>command</param>
  <param>year</param>
  <param>month</param>
  <param>day</param>
 </route>
</map>
XML;

$map = array(
	'' => array(
		0 => 'command',
		1 => 'action',
		2 => 'id',
		),
	'date' => array(
		'' => array(
			0 => 'command',
			1 => 'year',
			2 => 'month',
			3 => 'day',
			),
		),
	'country' => array(
		'' => array(
			0 => 'action',
			1 => 'country',
			2 => 'currency',
			),
		'DK' => array(
			'' => array(
				0 => 'action',
				1 => 'country',
				2 => 'mark',
				),
			),
		'UK' => array(
			'' => array(
				0 => 'action',
				1 => 'country',
				2 => 'pound',
				),
			),
		'US' => array(
			'' => array(
				0 => 'action',
				1 => 'country',
				2 => 'dollar',
				),
			),
		),
	);

$xml = simplexml_load_string($xmlstr);

echo '<pre>' . print_r($xml, 1) . '</pre>';
foreach ($xml as $key => $value) {
		echo gettype($value) . '<br/>';
		if (is_object($value)) {
			foreach ($value as $key => $value) {
					echo "$key=$value<br/>";
			}
		} else {
			echo "$key=$value<br/>";
		}
}
echo '<pre>' . print_r($map, 1) . '</pre>';
?>