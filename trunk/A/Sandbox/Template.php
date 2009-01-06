<?php

class Template	{

private $template;
private $values;

function __construct ($template, $values = array())	{
	if (file_exists ($template)):
		$this->template = $template;
		$this->values = $values;
	else:
		throw new Exception ("Template $template doesn't exist");
	endif;

	return $this;
}

function set ($key, $value)	{
	$this->values[$key] = $value;
	return $this;
}

function render()	{
	extract ($this->values);
	ob_start();
	include ($this->template);
	return ob_get_clean();
	}

}

?>
