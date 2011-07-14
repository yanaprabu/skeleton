<?php
require_once 'config.php';
require_once 'A/Http/Request.php';
require_once 'A/Http/Pathinfo.php';

$map = array(
/*
	'' => array(
		0=>array('name'=>'controller','default'=>'foocon','replace'=>'barcon'),
		1=>array('name'=>'action','default'=>'fooact','replace'=>array('action'=>'foonew','id'=>'idnew',),'halt'=>'yes'),
		2=>array('name'=>'id','default'=>'fooid','replace'=>''),
		),
*/
'' => array(
	0=>array('name'=>'controller', 'default'=>'news'),
	1=>array('name'=>'action', 'default'=>'listing'),
	2=>array('name'=>'id', 'default'=>'today'),
	),
	'/^sport.*/' => array(
		'' => array(
			0=>array('name'=>'controller', 'replace'=>array('controller'=>'news', 'action'=>'listing', 'id'=>'sports')),
			),
		'swimming' => array(
			'' => array(
				0=>array('name'=>'controller', 'replace'=>'news'),
				1=>array('name'=>'action', 'replace'=>'listing'),
				2=>array('name'=>'id', 'replace'=>'swimming'),
				),
			),
		),
	'date' => array(
		'' => array(
			'controller',
			'year',
			'month',
			3 => 'day',
			),
		),
	'country' => array(
		'' => array(
			'controller',
			'country',
			'currency',
			),
		'DK' => array(
			'' => array(
				'controller',
				'country',
				'mark',
				),
			),
		'UK' => array(
			'' => array(
				'controller',
				'country',
				'pound',
				),
			),
		'US' => array(
			'' => array(
				'controller',
				'country',
				'dollar',
				),
			),
		),
	);

$map2 = array(
	'' => array(
		),
	'param1' => array(
		'' => array(
			'subcontroller',
			'subaction',
			'subparam',
			),
		),
	);
$map3 = array(
	'' => array(
		'controller',
		'action',
		'id',
		),
	'admin' => array(
		'' => array(
			'module',
			'controller',
			'action',
			),
		),
	);

$Request = new A_Http_Request();
$Router = new A_Http_Pathinfo($map3);
$Router->run($Request);

?>
<table border="1">
<tr>
<td valign="top">
<h2>Select URL:</h2>
<ul>
<li><a href="<?php echo $_SERVER["SCRIPT_NAME"]; ?>/controller/action/id/param1/value1/param2/value2/">/controller/action/id/param1/value1/param2/value2/</a></li>
<li><a href="<?php echo $_SERVER["SCRIPT_NAME"]; ?>/date/2006/January/1st/param1/value1/">/date/2006/January/1st/param1/value1/</a></li>
<li><a href="<?php echo $_SERVER["SCRIPT_NAME"]; ?>/country/DK/100/param1/">/country/DK/100/param1/</a></li>
<li><a href="<?php echo $_SERVER["SCRIPT_NAME"]; ?>/country/UK/200">/country/UK/200/</a></li>
<li><a href="<?php echo $_SERVER["SCRIPT_NAME"]; ?>/country/US/300">/country/US/300</a></li>
<li><a href="<?php echo $_SERVER["SCRIPT_NAME"]; ?>/country/JP/400">/country/JP/400</a></li>
<li><a href="<?php echo $_SERVER["SCRIPT_NAME"]; ?>/sports/">/sports/</a></li>
<li><a href="<?php echo $_SERVER["SCRIPT_NAME"]; ?>/sports/swimming/">/sports/swimming/</a></li>
</ul>
<a href="<?php echo dirname($_SERVER["SCRIPT_NAME"]); ?>/..">Return to Examples Menu</a>
</td>
<td width="40">&nbsp;</td>
<td valign="top">
<?php
echo '<h2>Request vars:</h2><pre>' . print_r($Request->data, true) . '</pre>';
?>
</td>
<td width="40">&nbsp;</td>
<td valign="top">
<?php
echo '<h2>Using this map:</h2><pre>' . print_r($map3, true) . '</pre>';
?>
</td>
</tr>
</table>
