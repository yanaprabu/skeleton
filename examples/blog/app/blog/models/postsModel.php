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
					`post_date`,`permalink`,`title`,`excerpt`,`content` 
				FROM 
					`posts`
				WHERE
				 	`active` = 1
				";
		$posts = $this->dbh->query($sql);
		$rows = array();
		while($row = $posts->fetchRow()){
			$rows[] = $row;
		}
		return $rows;
	}
	
	function single(){
		return array(
			0 => array(
				'permalink' => '/examples/blog/posts/1/',
				'title' => 'The first title',
				'date' => '01-01-08',
				'content' => 'Hello world this is your first post',
				'excerpt' => 'Hello world this is the summery of the first article',
				),	
			);
	}
	
	public function findById($id){}
	
	public function findBy(){}
	
	
}