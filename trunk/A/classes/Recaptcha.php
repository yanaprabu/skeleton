<?php
#require_once('recaptcha/recaptchalib.php');

/**
 * class acts as both a Rule for Validator and Renderer for View
 * 
 * @package Misc 
 */
class Recaptcha {
	protected $field = "recaptcha_response_field";
	protected $field_challenge = "recaptcha_challenge_field";
	protected $privatekey;
	protected $errorMsg;
	protected $is_valid;
	
	function __construct($errorMsg, $publickey, $privatekey) {
		$this->errorMsg = $errorMsg;
		$this->publickey = $publickey;
		$this->privatekey = $privatekey;
	}
	
	function isValid($request) {
		$this->is_valid = false;
		if ($request->get($this->field)) {
        	$resp = recaptcha_check_answer ($this->privatekey,
                                        $_SERVER["REMOTE_ADDR"],
                                        $request->get($this->field_challenge),
                                        $request->get($this->field));
			$this->is_valid = $resp->is_valid;
		}
                                        
        return $this->is_valid;
	}
	
    public function getParameter() {
		return $this->field;
    }

    public function getErrorMsg() {
		return $this->errorMsg;
    }

    function render() {
		return recaptcha_get_html($this->publickey, $this->errorMsg);
	}
}

/*
Example:

<html>
  <body>
    <form action="" method="post">
<?php
require_once('recaptcha/recaptchalib.php');
require_once('A/classses/Recaptcha.php');

// Get a key from http://recaptcha.net/api/getkey
$publickey = "xxx";
$privatekey = "xxx";

$recaptcha = new Recaptcha($publickey, $privatekey);

# was there a reCAPTCHA response?
if (! $recaptcha->isValid($_POST)) {
	echo $recaptcha->getErrorMsg();
} else {
	echo "Captcha is valid.";
}
echo $recaptcha->render();
?>
    <br/>
    <input type="submit" value="submit" />
    </form>
  </body>
</html>

*/