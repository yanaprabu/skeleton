<?php
include_once('A/Template/File.php');

class A_Template_Strreplace extends A_Template_File {
	protected $tagprefix = '{';
	protected $tagsuffix = '}';
	
    public function set($field, $value) {
		if (substr($field, 0, 1) != $this->tagprefix) {
			$field = $this->tagprefix . $field;
		}
		if (substr($field, -1, 1) != $this->tagsuffix) {
			$field .= $this->tagsuffix;
		}
    	parent::set($field, $value);
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

