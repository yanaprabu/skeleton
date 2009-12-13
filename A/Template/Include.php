<?php
#include_once('A/Template.php');
/**
 * Template class that includes PHP templates. No block support.
 * 
 * @package A_Template 
 */

class A_Template_Include extends A_Template {

	public function partial($template) {
		return $this->render(dirname($this->filename) . "/$template.php");
	}
	
	public function partialLoop($template, $name, $data=null) {
		$template = dirname($this->filename) . "/$template.php";
		$str = '';
		if ($data) {
			// $name and $data set so each element in $data set to $name
			foreach ($data as $value) {
				$this->data[$name] = $value;
				$str .= $this->render($template);
			}
		} else {
			// $name but not $data, so $name contains $data. set() to $keys in each element array
			foreach ($name as $data) {
				if (is_array($data)) {
					foreach ($data as $key => $value) {
						$this->data[$key] = $value;
					}
				}
				$str .= $this->render($template);
			}
		}
		return $str;
	}
	
	public function render() {
	    extract($this->data);
		ob_start();
	    include(func_num_args() ? func_get_arg(0) : $this->filename);
	    return ob_get_clean();
	}

}