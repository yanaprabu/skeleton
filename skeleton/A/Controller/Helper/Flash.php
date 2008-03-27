<?php

class A_Controller_Helper_Flash {
	protected $locator;
	protected $session;
	
	public function __construct($locator, $args=null){
		$this->locator = $locator;
		if ($locator) {
			$this->session = $locator->get('Session', 'A_Session', __CLASS__);
		}
	}
	 
	public function set($name, $value){
		$this->session->set($name, $value, 1);
		return $this;
	}

	public function get($name){
		$value = $this->session->get($name);
		return $value;
	}

	public function now($name, $value){
		$this->session->set($name, $value, 0);
		return $this;
	}

	public function keep($name=null){
		$this->session->expire($name, 1);
		return $this;
	}

	public function discard($name=null){
		$this->session->expire($name, 0);
		return $this;
	}

}
