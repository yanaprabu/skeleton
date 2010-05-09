<?php

class Post	{

	public $id = '';
	public $title = '';
	public $content = '';
	public $author_first_name;
	public $author_last_name;

	public function __construct($id = null, $title = '', $content = '', $author_first_name = '', $author_last_name = '')	{
		$this->id = $id;
		$this->title = $title;
		$this->content = $content;
		$this->author_first_name = $author_first_name;
		$this->author_last_name = $author_last_name;
	}
	
	public function getTitle()	{
		return $this->title;
	}

	public function setTitle($title)	{
		$this->title = $title;
	}
	
	public function getAuthor()	{
		return $this->author_first_name . ' ' . $this->author_last_name;
	}

}
