<?php

require_once('SQLMapper.php');

class PostMapper extends SQLMapper	{

	public function __construct($db)	{
		parent::__construct($db, 'Post','posts');
		$this->map('id')->setKey();
		$this->map('title');
		$this->map('body');
	}

}