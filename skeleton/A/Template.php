<?php

class A_Template {
	protected $template = '';
	protected $data = array();
	protected $filename = '';
	
	public function __construct($filename='', $data=array()) {
	    $this->filename = $filename;
	    if ($data) {
	    	$this->import($data);
	    }
	}
	
	public function setTemplate($template) {
	    $this->template = $template;
	}
	
	public function setFilename($filename) {
	    $this->filename = $filename;
	}
	
	public function clear() {
	    $this->data = array();
	}
	
	public function renderArray($array, $block='') {
	   	$str = '';
	   	foreach ($array as $key1 => $value1) {
	   		if (is_array($value1)) {
		   		foreach ($value1 as $key2 => $value2) {
			   		$this->set($key2, $value2);
			   	}
		   		$str .= $this->render($block);
	   		} else {
			   	$this->set($key1, $value1);
		   	}
	   	}
	   	if (! isset($key2)) {
		   	$str .= $this->render($block);
	   	}
	   	return $str;
	}

    public function get($name) {
    	return (isset($this->data[$name]) ? $this->data[$name] : null);
    }

    public function set($name, $value) {
    	$this->data[$name] = $value;
    	return $this;
    }

    public function import($data) {
    	$this->data = array_merge($this->data, $data);
    }

    public function has($name) {
    	return isset($this->data[$name]);
    }

}

class A_Template_Include extends A_Template {

	public function render() {
	    extract($this->data);
		ob_start();
	    include($this->filename);
	    $str = ob_get_clean();
	    return($str);
	}

}
	

class A_Template_File extends A_Template {
	protected $blocks = array();
	protected $blockprefix = '<!--{';
	protected $blocksuffix = '}-->';
	protected $auto_blocks;

	public function __construct($filename='', $data=array(), $auto_blocks=false) {
		parent::__construct($filename, $data);
		$this->auto_blocks = $auto_blocks;
	}

	public function readFile($filename) {
		return file_get_contents($filename);
	}

	public function setTemplate($template) {
	    $this->template = $template;
	    $this->blocks = array();
	}
	
	public function setFilename($filename) {
	    $this->filename = $filename;
	    $this->blocks = array();
	}
	
	public function loadTemplate() {
		if ($this->template) {
			$this->blocks[''] = $this->template;
		} elseif (! isset($this->blocks['']) || ! $this->blocks['']) {
			$this->blocks[''] = $this->readFile($this->filename);
		}
	}

	public function makeBlocks($prefix='', $suffix='') {
	   	$this->loadTemplate();
		if (! $prefix) {
			$prefix = $this->blockprefix;
		}
		if (strpos($this->blocks[''], $prefix) !== false) {
			if (! $suffix) {
				$suffix = $this->blocksuffix;
			}
			$arr = array();
			$blocks = explode($prefix, $this->blocks['']);
			foreach ($blocks as $str) {
				if ($str) {
					list($key, $val) = explode($suffix, $str);
					if ($key != '') {
						$this->blocks[$key] = $val;
					}
				}
			}
		}
	}

	public function hasBlock($block=null) {
	   	if ($block) {
			return isset($this->blocks[$block]);
	   	} else {
			return count($this->blocks['']) > 1;
	   	}
	}
}


class A_Template_Eval extends A_Template_File {
	protected $str = '';
	
	public function render($block='') {
	   	if ($this->auto_blocks) {
	   		$this->makeBlocks();
	   	} else {
	   		$this->loadTemplate();
	   	}
   		if (is_array($this->data) && isset($this->blocks[$block])) {
		    $this->str =& $this->blocks[$block];
		    return($this->evalStr());
   		} else {
   			return $this->blocks[$block];
   		}
	}

	public function evalStr($_template_eval_str) {
		extract($this->data);
	    eval('return "' . addslashes($this->str) . '";');
	}
}
	

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
    }
    
    public function import($data) {
    	foreach ($data as $key => $value) {
    		$this->set($key, $value);
    	}
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

