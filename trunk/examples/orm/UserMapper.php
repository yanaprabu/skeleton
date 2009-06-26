<?php

require_once('SQLMapper.php');

class UserMapper extends I_Was_Told_To_Call_This_A_Table_Data_Gateway_But_The_Name_Really_Doesnt_Matter_Because_Its_Just_A_Placeholder_For_The_SQL_Functionality_Of_The_ORM	{

	public function __construct($db)	{
		parent::__construct($db, 'User','users');
		$this->mapMethods('getId','setId')->toColumn('id')->setKey();
		$this->mapMethods('getName','setName')->toColumn('first_name'); // should we allow a way to concatenate columns? ie 'first_name lastname'
	}

}