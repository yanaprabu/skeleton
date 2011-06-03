<?php

interface A_Renderable extends A_Renderer {
	public function set($key, $value);
	public function get($key);
	public function import($data);
}