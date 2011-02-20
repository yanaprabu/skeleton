<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of connect
 *
 * @author jonah
 */
class connect extends A_Controller_Action
{
	public function __construct($locator=null)
	{
		parent::__construct($locator);
	}

	public function user($locator = null)
	{
		$message = $locator->get('Request')->getData();

		/*$client = new client();

		$message->setSession(new A_Collection);

		$message->getSession()->set('client', $client);*/

		$message->reply(array(
				'command' => 'new:user',
				'data' => 'hello'//$client->serialize()
			),
			A_Socket_Message::OTHERS
		);
		echo 'Connected!' . PHP_EOL;
	}
}