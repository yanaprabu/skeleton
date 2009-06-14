<?php

require_once('SQLMapper.php');

class UserMapper extends SQLMapper	{

	public function __construct($db)	{
		parent::__construct($db, 'User','users');
		$this->mapMethods('getId','setId')->toColumn('id')->setKey();
		$this->mapMethods('getName','setName')->toColumn('first_name'); // should we allow a way to concatenate columns? ie 'first_name lastname'
	}

}