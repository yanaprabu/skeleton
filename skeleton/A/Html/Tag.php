<?php

class A_Html_Tag {

	public function setDefaults(&$attr, $default_attr) {
		foreach($default_attr as $key => $value) {
			if (! isset($attr[$key])) {
				$attr[$key] = $value;
			}
		}
	}
	
	/*
	 * $tag - name of tag
	 * $attr - array of attributes 
	 * $content - determines if the tag has a closing tag - defined yes, null no
	 *          - a string, an object with a render() method or an array containing any mix of those
	 * e.g. render('div', array('id'=>'foo'), 'bar') generates <div id="foo">bar</div>
	 * e.g. render('img', array('src'=>'foo.jpg', 'alt'=>'bar')) generates <img src="foo.jpg" alt="bar"/>
 	 */
	public function render($tag, $attr=array(), $content=null) {
		$str = "<$tag";
		foreach ($attr as $name=>$value) {
			$str .= " $name=\"$value\"";
		}
		if ($content === null) {
			$str .= '/>';
		} elseif (is_array($content)) {
			$str .= '>';
			foreach ($content as $c) {
				$str .= is_string($c) ? $c : $c->render();
			}
		} else {
			$str .= '>' . (is_string($content) ? $content : $content->render()) . "</$tag>";
		}
		return $str;
	}

}
