<?php
/**
 * This is a simple socket server setup for WebSockets
 */
$ConfigArray['PATH'] = dirname(__FILE__) . '/';

include $ConfigArray['PATH'] . '../../A/Locator.php';
$Locator = new A_Locator();
$Locator->autoload();

$Server = new A_WebSocket_Server($locator);
$Server->run();