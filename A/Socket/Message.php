<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Message
 *
 * @author jonah
 */
interface A_Socket_Message
{

	// Only reply to sender
	const SENDER = 0;
	// Reply to all clients
	const ALL = 1;
	// Reply to all but the sender
	const OTHERS = 2;
	
	public function __construct($message, $client, $clients);

	public function reply($message, $recipient);

	public function getMessage();

	public function getSession();

	public function setSession($session);

	public function getAllSessions();
}
