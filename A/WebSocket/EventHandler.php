<?php

interface A_WebSocket_EventHandler
{
	public function onMessage($data, $client, $server);
}