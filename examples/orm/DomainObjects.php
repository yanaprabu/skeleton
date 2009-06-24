<?php

class Post	{

	public $id = '';
	public $title = '';
	public $body = '';
	
	public function get($name)	{
		return isset($this->$name) ? $this->$name : null;
	}

	public function set($name, $value)	{
		if (isset($this->$name)) {
			$this->$name = $value;
		}
	}

}

class User	{

	protected $id, $name;

	public function __construct($id=null, $name='')	{
		$this->id = $id;
		$this->name = $name;
	}

	public function getId()	{
		return $this->id;
	}

	public function setId($id)	{
		$this->id = $id;
	}

	public function getName()	{
		return $this->name;
	}

	public function setName($name)	{
		$this->name = $name;
	}

}