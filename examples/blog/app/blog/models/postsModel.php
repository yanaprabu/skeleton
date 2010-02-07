<?php

class postsModel {
	
	public $date = '';
	public $permalink = '';
	public $title = '';
	public $excerpt = '';
	public $content = '';
	public $author = null;
	public $comments = null;
	
	protected $dbh = null;
	
	public function __construct($db){
		$this->dbh = $db;
	}
		
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
	
	function single($id){
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
	
	public function findById($id){}
	
	public function findBy(){}
	
	public function save(){}
	
}