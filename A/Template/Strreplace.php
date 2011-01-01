<?php
#include_once('A/Template/File.php');
/**
 * Template class that loads HTML templates and uses str_replace-ment. Templates can have blocks.
 * 
 * @package A_Template
 */

class A_Template_Strreplace extends A_Template_File {
	protected $tagprefix = '{';
	protected $tagsuffix = '}';
	
	public function set($field, $value) {
		// field required and value must be a string or an object with __toString()
		if ($field && (is_string($value) || (is_object($value) && method_exists($value, '__toString')))) {
			// check that suffix/prefix are on tag and add if necessary
			if (substr($field, 0, 1) != $this->tagprefix) {
				$field = $this->tagprefix . $field;
			}
			if (substr($field, -1, 1) != $this->tagsuffix) {
				$field .= $this->tagsuffix;
			}
			parent::set($field, $value);
		}
		return $this;
	}
	
	public function import($data) {
		foreach ($data as $key => $value) {
			$this->set($key, $value);
		}
		return $this;
	}

	public function render($block='') {
	   	if ($this->auto_blocks) {
	   		$this->makeBlocks();
	   	} else {
	   		$this->loadTemplate();
	   	}
   		if (isset($this->blocks[$block])) {
	   		if (is_array($this->data)) {
	   			return str_replace(array_keys($this->data), $this->data, $this->blocks[$block]);
	   		} else {
	   			return $this->blocks[$block];
	   		}
   		}
   		return '';
	}

}

