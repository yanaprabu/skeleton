<?php
require_once 'config.php';
require_once 'A/Http/Request.php';
require_once 'A/Http/Pathinfo.php';

$map = array(
	'' => array(
		'controller',
		'action',
		'id',
		),
	'date' => array(
		'' => array(
			'controller',
			'year',
			'month',
			3 => 'day',
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
$Request = new A_Http_Request();

// initialize with $map_extra_param_pairs=false so we only set the params found in the routes
$Mapper = new A_Http_Pathinfo($map, false);
$Mapper->run($Request);

// set second map and process to map those routes
$Mapper->setMap($map2);
$Mapper->run($Request);

?>
<table border="1">
<tr>
<td valign="top">
<h2>Select URL:</h2>
<ul>
<li><a href="<?php echo $_SERVER["SCRIPT_NAME"]; ?>/sku/add/001/param1/param2/param3/param4/">/sku/add/001/param1/param2/param3/param4/</a></li>
<li><a href="<?php echo $_SERVER["SCRIPT_NAME"]; ?>/date/2006/January/1st/param1/param2/param3/">/date/2006/January/1st/param1/param2/param3/</a></li>
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
echo '<h2>First map:</h2><pre>' . print_r($map, true) . '</pre>';
echo '<h2>Second map:</h2><pre>' . print_r($map2, true) . '</pre>';
?>
</td>
</tr>
</table>
