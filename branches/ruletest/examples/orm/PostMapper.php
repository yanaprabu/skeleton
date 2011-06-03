<?php

#require_once('A/Orm/DataMapper.php');

class PostMapper extends A_Orm_DataMapper	{

	public function __construct($db)	{
		parent::__construct($db, 'Post','posts');
		$this->map('id')->setKey();
		$this->map('title');
		$this->map('content');
	}

}