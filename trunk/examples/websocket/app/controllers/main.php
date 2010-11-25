<?php

class main extends A_Controller_Action {

	public function __construct($locator=null){
		parent::__construct($locator);
	}
	
	public function main($locator=null) {
		$request = $locator->get('Request');
		
		$server = $request->getServer();
		$client = $request->getClient();
		$otherClients = $server->getClients();
		
		foreach ($otherClients as $otherClient) {
			if ($otherClient != $client) {
				$otherClient->send($request->get('data'));
			}
		}
	}

}
