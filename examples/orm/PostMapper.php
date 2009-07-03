<?php

require_once('HardcodedGateway.php');

class PostMapper extends HardcodedGateway	{

	public function __construct($db)	{
		parent::__construct($db, 'Post','posts');
		$this->map('id')->setKey();
		$this->map('title');
		$this->map('body');
	}

}