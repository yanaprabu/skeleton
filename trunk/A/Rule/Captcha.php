<?php
include_once 'A/Rule/Abstract.php';

/**
 * Rule to check for captcha value
 * class acts as both a Rule for Validator and Renderer for View
 * 
 * @package A_Rule
 */
class A_Rule_Captcha extends A_Rule_Abstract {
	const ERROR = 'A_Rule_Captcha';
	protected $field;
	protected $errorMsg;
	protected $charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
	protected $length = 5;
	protected $base_path = '';
	protected $script_name = 'captcha_image.php';
	protected $params = array(
							'field' => '', 
							'errorMsg' => '', 
							'renderer' => null, 
							'session' => null, 
							'sessionkey' => 'A_Rule_Captcha', 
							'optional' => false
							);
	
/*
	public function __construct($field, $errorMsg, $renderer, $session, $sessionkey='') {
*/
	
    public function setCharset($value) {
		return $this->charset = $value;
		return $this;
    }

    public function setLength($value) {
		return $this->length = $value;
		return $this;
    }

    public function setBasePath($value) {
		return $this->base_path = $value;
		return $this;
    }

    public function setScriptName($value) {
		return $this->script_name = $value;
		return $this;
    }

    function validate() {
		return $this->getValue($this->params['field']) == $this->getCode();
	}
	
    public function getParameter() {
		return $this->params['field'];
    }

	public function generateCode($length=0) {
		if ($length > 0) {
			$this->length = $length;
		}
		$this->params['session']->set($this->params['sessionkey'],  substr(str_shuffle($this->charset), 0, $this->length));
		return $this;
	}
	
	public function getCode(){
		$code = $this->params['session']->get($this->params['sessionkey']);
		if ($code == '') {
			$this->generateCode();
		}
		return $this->params['session']->get($this->params['sessionkey']);
	}
	
    public function render() {
		if ($this->params['renderer']) {
			$this->params['renderer']->set('url', $this->base_path . $this->script_name);
			return $this->params['renderer']->render();
		} else {
			return "<img src=\"{$this->base_path}{$this->script_name}\"/>";
    	}
    }

}

/**
 * Generate an image using GD for captcha
 * 
 * @package A_Rule
 */
class A_Rule_Captcha_Image {
	protected $captcha;
	protected $length;

	public function __construct($captcha) {
		$this->captcha = $captcha;
	}
	
	public function out(){
		header("Content-type: image/png");
		$im = imagecreate(75, 25);
		if ($im) {
			$bg_color = imagecolorallocate($im, 255, 255, 255);
			imagefill($im, 0, 0, $bg_color);
			$text_color = imagecolorallocate($im, 0, 0, 0);
			imagestring($im, 5, 12, 5,  $this->captcha->getCode(), $text_color);
			imagepng($im);
			imagedestroy($im);
		} else {
			return '';
		}
	}
}
