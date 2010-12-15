<?php
/**
 * This is a simple socket server setup for WebSockets
 */
$ConfigArray = array(
	'PATH' => dirname(__FILE__) . '/',
	'APP' => dirname(__FILE__) . '/app',
	'WEBSOCKET' => array(
		'host' => 'localhost',
		'port' => '9091'
	)
);

include $ConfigArray['PATH'] . '../../A/Locator.php';
$Locator = new A_Locator();
$Locator->autoload();

$config = new A_Config_Php();
$config->import($ConfigArray);

$Server = new A_WebSocket_Server($config);
$Server->run($Locator);
