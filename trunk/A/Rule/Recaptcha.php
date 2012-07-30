<?php
/**
 * Recaptcha.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Rule_Recaptcha
 *
 * Rule to check reCAPTCHA API for valid captcha challenge/response.
 *
 * @package A_Rule
 */
class A_Rule_Recaptcha extends A_Rule_Base
{

	const ERROR = 'A_Rule_Recaptcha';
	const SUCCESS_STRING = 'true';

	protected $params = array(
		'private_key' => '',
		'field' => '',
		'errorMsg' => '',
		'challenge_field' => 'recaptcha_challenge_field',
		'timeout' => 10,
		'optional' => false,
		'api_url' => 'http://www.google.com/recaptcha/api/verify',
	);

	function validate()
	{
		$code = $this->getValue();

		$url = $this->params['api_url'];
		$fields = http_build_query(array(
			'privatekey' => $this->params['private_key'],
			'remoteip' => $_SERVER['REMOTE_ADDR'],
			'challenge' => $this->getValue($this->params['challenge_field']),
			'response' => $this->getValue(),
		));
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->params['timeout']);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
		$result = curl_exec($curl);
		curl_close($curl);

		return substr($result, 0, strlen(self::SUCCESS_STRING)) == self::SUCCESS_STRING;
	}

}

