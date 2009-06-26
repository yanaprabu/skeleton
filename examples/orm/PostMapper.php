<?php

require_once('SQLMapper.php');

class PostMapper extends I_Was_Told_To_Call_This_A_Table_Data_Gateway_But_The_Name_Really_Doesnt_Matter_Because_Its_Just_A_Placeholder_For_The_SQL_Functionality_Of_The_ORM	{

	public function __construct($db)	{
		parent::__construct($db, 'Post','posts');
		$this->map('id')->setKey();
		$this->map('title');
		$this->map('body');
	}

}