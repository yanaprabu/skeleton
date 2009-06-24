<?php

require_once('SQLMapper.php');

class PostMapper extends SQLMapper	{

	public function __construct($db)	{
		parent::__construct($db, 'Post','posts');
		$this->mapGeneric('id')->toColumn('id')->setKey();
		$this->mapGeneric('title')->toColumn('title');
		$this->mapProperty('body')->toColumn('body');
	}

}