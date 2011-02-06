<?php

class name extends A_Controller_Action {

	public function __construct($locator=null){
		parent::__construct($locator);
	}
	
	public function set_name($locator=null) {
		$request = $locator->get('Request')->getData();
		
		$request->getSession()->set('name', $request->getMessage()->data);
		
		$names = array();
		foreach ($request->getAllSessions() as $session) {
			$names[] = $session->get('name');
		}
		
		$request->reply(array(
				'command' => 'user_list',
				'data' => $names
			),
			A_Socket_Message_Abstract::ALL
		);
	}

}
