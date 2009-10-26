<?php

require_once('HardcodedGateway.php');

class JoinPostMapper extends HardcodedGateway	{

	public function __construct($db)	{
		parent::__construct($db, 'Post','posts');
		$this->map('id')->setKey();
		$this->map('title');
		$this->map('body');
		$this->join('users')->on('author_id','id');
		$this->map('author_first_name')->toColumn('first_name','users');
		$this->map('author_last_name')->toColumn('last_name','users');
	}

}