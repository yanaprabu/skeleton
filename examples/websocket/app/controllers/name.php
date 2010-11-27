<?php

class name extends A_Controller_Action {

	public function __construct($locator=null){
		parent::__construct($locator);
	}
	
	public function set_name($locator=null) {
		$request = $locator->get('Request');
		
		$server = $request->getServer();
		$client = $request->getClient();
		$otherClients = $server->getClients();
		
		$client->session()->set('name', $request->getMessage());
		
		$names = array();
		
		foreach ($otherClients as $otherClient) {
			$names[] = $otherClient->session()->get('name');
		}
		
		foreach ($otherClients as $otherClient) {
			$otherClient->send(array(
				'command' => 'user_list',
				'data' => $names
			));
		}
	}

}
