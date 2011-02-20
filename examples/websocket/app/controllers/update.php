<?php

class update extends A_Controller_Action {

	public function __construct($locator=null)
	{
		parent::__construct($locator);
	}
	
	public function user($locator=null)
	{
		$request = $locator->get('Request')->getData();

		$client = $request->getSession()->get('client');
		
		$client->deserialize($request->getMessage()->data);
		
		$request->reply(array(
				'command' => 'update:user',
				'data' => $client->serialize
			),
			A_Socket_Message::OTHERS
		);
	}

}
