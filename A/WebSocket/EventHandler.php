<?php

interface A_WebSocket_EventHandler
{
	public function onOpen($event);
	public function onMessage($event);
	public function onClose($event);
}