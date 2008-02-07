<?php

class A_Html_Tag {

	public function setDefaults(&$attr, $default_attr) {
		foreach($default_attr as $key => $value) {
			if (! isset($attr[$key])) {
				$attr[$key] = $value;
			}
		}
	}
	
	public function render($tag, $attr=array(), $content=null) {
		$str = "<$tag";
		foreach ($attr as $name=>$value) {
			$str .= " $name=\"$value\"";
		}
		if ($content === null) {
			$str .= '/>';
		} else {
			$str .= '>' . (is_string($content) ? $content : $content->render()) . "</$tag>";
		}
		return $str;
	}

}
