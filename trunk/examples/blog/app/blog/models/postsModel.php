<?php

class postsModel {
	public $permalink = '';
	public $title = '';
	public $date = '';
	public $content = '';
	
	function listAll(){
		return array(
			0 => array(
				'permalink' => '/examples/blog/posts/1/',
				'title' => 'First title here',
				'date' => '01-01-08',
				'content' => 'Hello world this is your first post',
				'excerpt' => 'Hello world this is the summery odf the first article',
				),
			1 => array(
				'permalink' => '/examples/blog/posts/2/',
				'title' => 'The second title',
				'date' => '02-01-08',
				'content' => 'Hello world this is your second post',
				'excerpt' => 'Hello world this is the summery of the second article',
				),
			);
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
	
}