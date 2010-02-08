<?php

class commentsModel extends A_Model {
		
	protected $dbh = null;
	
	public function __construct($db){
		$this->dbh = $db;
		$this->addField(new A_Model_Field('id'));
		$this->addField(new A_Model_Field('author'));
		$this->addField(new A_Model_Field('author_email'));
		$this->addField(new A_Model_Field('author_url'));
		$this->addField(new A_Model_Field('users_id')); // FK to users id		
		$this->addField(new A_Model_Field('comment_date'));
		$this->addField(new A_Model_Field('comment'));
		$this->addField(new A_Model_Field('approved'));
		$this->addField(new A_Model_Field('posts_id')); // FK to posts id
	}

	public function save(){
		// if doesn't exist yet create
		if(!$this->get('id')){
			// insert new 
		} else {
			// update
		}
	}
	
	public function find($id){}
	public function findBy($someArgs){}
	public function delete($id){}
	
	
	function findByPost($id){

		$sql = "SELECT 
					c.`id` as 'comment_id', c.`comment_date`, c.`author`, c.`author_email`, c.`author_url`, c.`comment`
				FROM 
					`comments` c
				WHERE
					c.`posts_id` = $id
				";
		$posts = $this->dbh->query($sql);
		$rows = array();
		while($row = $posts->fetchRow()){
			$rows[] = $row;
		}
		return $rows;
	}
	
}