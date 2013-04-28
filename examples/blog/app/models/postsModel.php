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

	public function save($data){ 
		// if doesn't exist yet create
		if(!$this->get('id')){
			// insert new 
			$sql = "INSERT INTO
					`blog_posts`
					(
						`post_date`,
						`permalink`,
						`title`,
						`excerpt`,
						`content`,
						`comments_allowed`,
						`post_type`,
						`users_id`,
						`active`
					) VALUES (
						:post_date,
						:permalink,
						:title,
						:excerpt,
						:content,
						:comments_allowed,
						:post_type,
						:users_id,
						:active
					)
					";
			$result = $this->dbh->query($sql, array(
				':post_date' => $data['post_date'],
				':permalink' => $data['permalink'],
				':title' => $data['title'],
				':excerpt' => $data['excerpt'],
				':content' => $data['content'],
				':comments_allowed' => $data['comments_allowed'],
				':post_type' => 'post',
				':users_id' => $data['users_id'],
				':active' => $data['active']
			));
			return $result;
		} else {
			// update
			$sql = "UPDATE `blog_posts`
					SET
						`post_date` = :post_date,
						`permalink` = :permalink,
						`title` = :title,
						`excerpt` = :excerpt,
						`content` = :content,
						`comments_allowed` = :comments_allowed,
						`post_type` = :post_type,
						`users_id` = :users_id,
						`active` = :active
					WHERE
						`id` = :id";
			$result = $this->dbh->query($sql, array(
				':post_date' => $data['post_date'],
				':permalink' => $data['permalink'],
				':title' => $data['title'],
				':excerpt' => $data['excerpt'],
				':content' => $data['content'],
				':comments_allowed' => $data['comments_allowed'],
				':post_type' => 'post',
				':users_id' => $data['users_id'],	
				':active' => $data['active'],
				':id' => $data['id']
			));
			return $result;
			
		}
	}

	public function find($id){/*as 'post_id'*/
		$sql = "SELECT 
					p.`id`, 
					p.`post_date`, 
					p.`permalink`, 
					p.`title`, 
					p.`excerpt`, 
					p.`content`,
					COUNT(c.`id`) as 'nocomms',
					p.`comments_allowed`,
					p.`users_id`,
					p.`active`,
					u.`username`
				FROM 
					`blog_posts` p
				LEFT JOIN `blog_users` u ON u.`id` = p.`users_id`
				LEFT JOIN `blog_comments` c ON c.`posts_id` = p.`id` 
				WHERE
				 	p.`active` = 1
				AND
					p.`id` = $id
				GROUP BY p.`id`
				";

		$posts = $this->dbh->query($sql);
		if(!$posts->isError()){
			$rows = array();
			while($row = $posts->fetchRow()){
				$rows[] = $row;
			}
			return $rows;
		} else {
			$this->setErrorMsg(1, $posts->getErrorMsg());
		}

	}
	public function findBy($someArgs){}
	public function delete($id){}


	public function listAll(){
		$sql = "SELECT 
					p.`id` as 'post_id', p.`post_date`, p.`permalink`, p.`title`, p.`excerpt`, p.`content`,
					COUNT(c.`id`) as 'nocomms',
					u.`username`
				FROM 
					`blog_posts` p
				LEFT JOIN `blog_users` u ON u.`id` = p.`users_id`
				LEFT JOIN `blog_comments` c ON c.`posts_id` = p.`id` 
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