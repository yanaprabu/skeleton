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
		'message-class' => 'A_Socket_Message_Json'
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

$EventListener = new A_Socket_EventListener_FrontController($Locator);

$ConnectMessage = new A_Socket_Message_Json('{"type":{"module":"","controller":"connect","action":"user"},"data":null}');
$DisconnectMessage = new A_Socket_Message_Json('{"type":{"module":"","controller":"disconnect","action":"user"},"data":null}');

$Parser = new A_Socket_Parser_WebSocket();

$Server = new A_Socket_Server($EventListener, $Parser, $ConnectMessage, $DisconnectMessage);
$Server->run($ConfigArray['SOCKET']);