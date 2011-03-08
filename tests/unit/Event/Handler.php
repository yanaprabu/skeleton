<?php

class Handler implements A_Event_Listener
{
	public function onEvent($eventName, $eventData)
	{
		return 'listener1';
	}
}
?>
