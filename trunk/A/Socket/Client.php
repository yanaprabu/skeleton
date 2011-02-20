<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Client
 *
 * @author jonah
 */
interface A_Socket_Client
{
	public function __construct($socket);

	public function send($message);

	public function receive($data);

	public function connect($data);

	public function isConnected();

	public function getSession();

	public function setSession($session);
}
?>
