<?php

require_once('A/Orm/DataMapper.php');

class JoinPostMapper extends A_Orm_Datamapper	{

	public function __construct($db)	{
		parent::__construct($db, 'Post','posts');
		$this->map('id:key','title AS title','posts.body AS body');
		//$this->map('author_first_name')->toColumn('users.first_name');
		//$this->map('author_last_name')->toColumn('users.last_name');
		//$this->innerJoin('users')->on('id','author_id');
		$this->join('users ON (users.id = posts.author_id');
	}

}