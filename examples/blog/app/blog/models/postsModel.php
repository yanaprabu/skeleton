<?php

class postsModel extends A_Model {
		
	protected $dbh = null;
	
	public function __construct($db){
		$this->dbh = $db;
		$this->addField(new A_Model_Field('id'));
		$this->addField(new A_Model_Field('post_date'));	
		$this->addField(new A_Model_Field('permalink'));		
		$this->addField(new A_Model_Field('title'));
		$this->addField(new A_Model_Field('excerpt'));
		$this->addField(new A_Model_Field('content'));
		$this->addField(new A_Model_Field('comments_allowed'));
		$this->addField(new A_Model_Field('post_type'));		
		$this->addField(new A_Model_Field('users_id')); // FK to users id
		$this->addField(new A_Model_Field('active'));
		}

	public function save(){
		// if doesn't exist yet create
		if(!$this->get('id')){
			// insert new 
		} else {
			// update
		}
	}

	public function find($id){
		$sql = "SELECT 
					p.`id` as 'post_id', p.`post_date`, p.`permalink`, p.`title`, p.`excerpt`, p.`content`,
					COUNT(c.`id`) as 'nocomms',
					u.`username`
				FROM 
					`posts` p

				LEFT JOIN `users` u ON u.`id` = p.`users_id`
				LEFT JOIN `comments` c ON c.`posts_id` = p.`id` 
				WHERE
				 	p.`active` = 1
				AND
					p.`id` = $id
				GROUP BY p.`id`
				";

		$posts = $this->dbh->query($sql);
		$rows = array();
		while($row = $posts->fetchRow()){
			$rows[] = $row;
		}
		return $rows;
	}
	public function findBy($someArgs){}
	public function delete($id){}


	function listAll(){
		$sql = "SELECT 
					p.`id` as 'post_id', p.`post_date`, p.`permalink`, p.`title`, p.`excerpt`, p.`content`,
					COUNT(c.`id`) as 'nocomms',
					u.`username`
				FROM 
					`posts` p
				LEFT JOIN `users` u ON u.`id` = p.`users_id`
				LEFT JOIN `comments` c ON c.`posts_id` = p.`id` 
				WHERE
				 	p.`active` = 1
				GROUP BY p.`id`
				";
		$posts = $this->dbh->query($sql);
		$rows = array();
		while($row = $posts->fetchRow()){
			$rows[] = $row;
		}
		return $rows;
	}
	


	
}