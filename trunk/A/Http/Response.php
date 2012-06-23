<?php
/**
 * Response.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Http_Response
 *
 * HTTP response. Encapsulates headers, redirects, character encoding, quoting, escaping, and content.
 *
 * @package A_Http
 */
class A_Http_Response extends A_Http_View
{

	public function render($template='', $scope='')
	{
		if ($this->headers) {
			foreach ($this->headers as $name => $values) {
				header("$name: " . implode(',', $values));
			}
		}
		if ($this->cookies) {
			foreach ($this->cookies as $args) {
				call_user_func_array('setcookie', $args);
			}
		}
		if ($this->redirect) {
			$base = $_SERVER['SERVER_NAME'] . dirname($_SERVER['SCRIPT_NAME']);
			if (! preg_match('/^https?\:\/\//i', $this->redirect) && (strpos($this->redirect, $base) === false)) {
				if (isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == 'on')) {
					$protocol = 'https://';
				} else {
					$protocol = 'http://';
				}
				if (substr($base, -1, 1) != '/') {
					$base .= '/';
				}
				$this->redirect = $protocol . $base . preg_replace('/^[\/\.]*/', '', $this->redirect);
			}
# astions Google Chrome caching redirects fix
# header("Cache-Control: max-age=0, no-cache, no-store, must-revalidate"); // HTTP/1.1
# header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
# header("Location: $url", true, 302);
# or just
# header("Location: $url", true, 303);
			header("Cache-Control: max-age=0, no-cache, no-store, must-revalidate"); // HTTP/1.1
			header('Location: ' . $this->redirect, true, 303);
		} else {
			parent::render($template, $scope);
			foreach ($this->headers as $field => $params) {
				if (!is_null($params)) {
					header($field . ': ' . implode(', ', $params));
				}
			}
			return $this->content;
		}
	}

	public function out()
	{
		echo $this->render();
	}

}
