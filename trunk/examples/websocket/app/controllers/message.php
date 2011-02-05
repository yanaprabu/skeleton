<?php

class message extends A_Controller_Action {

	public function __construct($locator=null){
		parent::__construct($locator);
	}
	
	public function append_message($locator=null) {
		$request = $locator->get('Request')->getData();
		
		$request->reply(
			array(
				'command' => 'append_message',
				'data' => array(
					'sender' => $request->getSession()->get('name'),
					'message' => $request->getMessage()->data
				)
			),
			A_Socket_Message::OTHERS
		);
	}
	
	public function finalize_message($locator=null) {
		$request = $locator->get('Request')->getData();
		
		$request->reply(
			array(
				'command' => 'finalize_message',
				'data' => array(
					'sender' => $request->getSession()->get('name')
				)
			),
			A_Socket_Message::ALL
		);
	}

}
