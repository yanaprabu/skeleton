<?php

require_once('HardcodedGateway.php');

class JoinPostMapper extends HardcodedGateway	{

	public function __construct($db)	{
		parent::__construct($db, 'Post','posts');
		$this->mapParams('id:key','title','body','users.first_name','users.last_name');
		$this->innerJoin('users')->on('id','author_id');
	}

}