<?php

class commentsModel {
	
	public $author = '';
	public $author_email = '';
	public $author_url = '';
	public $comment_date = '';
	public $comment = '';
	
	protected $dbh = null;
	
	public function __construct($db){
		$this->dbh = $db;
	}
		
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