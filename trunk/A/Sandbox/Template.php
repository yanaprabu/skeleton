<?php

class Icebox_Template	{

	private $template;
	private $collection;
	private $callback;

	public function __construct ($template, $collection = array(), $callback = null)	{
		if (file_exists ($template)) $this->template = $template;
		else throw new Exception ("Template $template doesn't exist");
		if ($collection instanceof A_Collection) $this->collection = $collection;
		else $this->collection = new A_Collection ($collection);
		$this->callback = $callback;
	}

	public function set ($key, $value)	{
		$this->collection->add ($key, $value);
		return $this;
	}

	public function get ($key)	{
		return $this->collection->get ($key);
	}

	public function render()	{
		extract ($this->collection->toArray());
		ob_start();
		include ($this->template);
		return ob_get_clean();
		}

	public function setOverloadCallback ($callback)	{
		$this->callback = $callback;
	}

	public function __toString()	{
		return $this->render();
	}

	public function __get ($key)	{
		return $this->collection->get ($key);
	}

	public function __set ($key, $value)	{
		return $this->set ($key, $value);
	}

	public function __call ($method, $params)	{
		if ($this->callback) return call_user_func_array (array ($this->callback, $method), $params);
	}

}
