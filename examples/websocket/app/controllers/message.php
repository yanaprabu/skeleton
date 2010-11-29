<?php

class message extends A_Controller_Action {

	public function __construct($locator=null){
		parent::__construct($locator);
	}
	
	public function append_message($locator=null) {
		$request = $locator->get('Request');
		
		$server = $request->getServer();
		$client = $request->getClient();
		$otherClients = $server->getClients();
		
		foreach ($otherClients as $otherClient) {
			if ($otherClient != $client) {
				$otherClient->send(array(
					'command' => 'append_message',
					'data' => array(
						'sender' => $client->session()->get('name'),
						'message' => $request->getMessage()
					)
				));
			}
		}
	}
	
	public function finalize_message($locator=null) {
		$request = $locator->get('Request');
		
		$server = $request->getServer();
		$client = $request->getClient();
		$otherClients = $server->getClients();
		
		foreach ($otherClients as $otherClient) {
			$otherClient->send(array(
				'command' => 'finalize_message',
				'data' => array(
					'sender' => $client->session()->get('name')
				)
			));
		}
	}

}
