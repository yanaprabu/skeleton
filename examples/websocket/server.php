<?php
/**
 * This is a simple socket server setup for WebSockets
 */
$ConfigArray = array(
	'PATH' => dirname(__FILE__) . '/',
	'APP' => dirname(__FILE__) . '/app',
	'SOCKET' => array(
		'host' => 'localhost',
		'port' => '9091',
		'client-class' => 'A_Socket_Client_WebSocket',
		'message-class' => 'A_Socket_Message_Json',
		'parser-class' => 'A_Socket_Parser_WebSocket'
	),
	'DEFAULT_ACTION' => array('', 'main', 'main'),
	'ERROR_ACTION' => array('', 'main', 'main')
);

include $ConfigArray['PATH'] . '../../A/Locator.php';
$Locator = new A_Locator();
$Locator->autoload();

$Config = new A_Config_Php();
$Config->import($ConfigArray);

$Locator->set('Config', $Config);

$Server = new A_Socket_Server(new A_Socket_EventListener_FrontController($Locator));
$Server->run($Config->get('SOCKET'));
