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
		
		$this->addRule(new A_Rule_Length(2, 25, 'author', 'The author name must be between 2 to 25 characters'), 'author'); 
		$this->addRule(new A_Rule_Email('author_email', 'This is not a valid email adress'), 'author_email');
		$this->addRule(new A_Rule_Url('author_url', 'This is not a valid URL'), 'author_url');
		$this->addRule(new A_Rule_Length(2, 500, 'comment', 'The comment must be between 2 to 500 characters'), 'comment'); 
	}

	public function save($data){ 
		// if doesn't exist yet create
		if(!$this->get('id')){
			$sql = "INSERT INTO
					`blog_comments`
					(
						`author`,
						`author_email`,
						`author_url`,
						`users_id`,
						`comment_date`,
						`comment`,
						`approved`,
						`posts_id`
					) VALUES (
						:author,
						:author_email,
						:author_url,
						:users_id,
						:comment_date,
						:comment,
						:approved,
						:posts_id
					)
					";
			$result = $this->dbh->query($sql, array(
				':author' => $data['author'],
				':author_email' => $data['author_email'],
				':author_url' => $data['author_url'],
				':users_id' => $data['users_id'],
				':comment_date' => $data['comment_date'],
				':comment' => $data['comment'],
				':approved' => $data['approved'],
				':posts_id' => $data['posts_id']
			));
			return $result;
		} else {
			// update
			$sql = "UPDATE `blog_comments`
					SET
						`author` = :author,
						`author_email` = :author_email,
						`author_url` = :author_url,
						`users_id` = :users_id,
						`comment_date` = :comment_date,
						`comment` = :comment,
						`approved` = :approved,
						`posts_id` = :posts_id
					WHERE
						`id` = :id";
			$result = $this->dbh->query($sql, array(
				':author' => $data['author'],
				':author_email' => $data['author_email'],
				':author_url' => $data['author_url'],
				':users_id' => $data['users_id'],
				':comment_date' => $data['comment_date'],
				':comment' => $data['comment'],
				':approved' => $data['approved'],
				':posts_id' => $data['posts_id'],
				':id' => $data['id']
			));
			return $result;
		} 
	}
	
	public function find($id){
		$sql = "SELECT 
					`id`, 
					`author`, 
					`author_email`, 
					`author_url`, 
					`users_id`, 
					`comment_date`,
					`comment`,
					`approved`,
					`posts_id`
				FROM 
					`blog_comments`
				WHERE
		
					`id` = $id
	
				";

		$comments = $this->dbh->query($sql);
		if(!$comments->isError()){
			$rows = array();
			while($row = $comments->fetchRow()){
				$rows[] = $row;
			}
			return $rows;
		} else {
			$this->setErrorMsg(1, $comments->getErrorMsg());
		}
	}
	
	public function findBy($someArgs){}
	public function delete($id){}
	
	
	function findByPost($id){

		$sql = "SELECT 
					c.`id` as 'comment_id', c.`comment_date`, c.`author`, c.`author_email`, c.`author_url`, c.`comment`
				FROM 
					`blog_comments` c
				WHERE
					c.`posts_id` = $id
				AND
					c.`approved` = '1'
				";
		$posts = $this->dbh->query($sql);
		$rows = array();
		while($row = $posts->fetchRow()){
			$rows[] = $row;
		}
		return $rows;
	}
	
}