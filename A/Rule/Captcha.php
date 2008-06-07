<?php
include_once 'A/Rule/Abstract.php';

/*
 * class acts as both a Rule for Validaror and Renderer for View
 */
class A_Rule_Captcha extends A_Rule_Abstract {
	protected $field;
	protected $errorMsg;
	protected $renderer;
	protected $session;
	protected $sessionkey;
	protected $charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
	protected $length = 5;
	protected $base_path = '';
	protected $script_name = 'captcha_image.php';
	
	public function __construct($field, $errorMsg, $renderer, $session, $sessionkey='A_Rule_Captcha') {
		$this->field = $field;
		$this->errorMsg = $errorMsg;
		$this->renderer = $renderer;
		$this->session = $session;
		$this->sessionkey = $sessionkey;
	}
	
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

    function isValid($request) {
		return $request->get($this->field) == $this->getCode() ? true : false;
	}
	
    public function getParameter() {
		return $this->field;
    }

	public function generateCode($length=0) {
		if ($length > 0) {
			$this->length = $length;
		}
		$this->session->set($this->sessionkey,  substr(str_shuffle($this->charset), 0, $this->length));
		return $this;
	}
	
	public function getCode(){
		$code = $this->session->get($this->sessionkey);
		if ($code == '') {
			$this->generateCode();
		}
		return $this->session->get($this->sessionkey);
	}
	
    public function render() {
		if ($this->renderer) {
			$this->renderer->set('url', $this->base_path . $this->script_name);
			return $this->renderer->render();
		} else {
			return "<img src=\"{$this->base_path}{$this->script_name}\"/>";
    	}
    }

}

class A_Rule_Captcha_Image {
	protected $captcha;
	protected $length;

	public function __construct(&$captcha) {
		$this->captcha =& $captcha;
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
