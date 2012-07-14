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
			$host = $_SERVER['SERVER_NAME'];
			$script = $host . dirname($_SERVER['SCRIPT_NAME']);
			if (!preg_match('/^https?\:\/\//i', $this->redirect) && (strpos($this->redirect, $script) === false)) {
				$protocol = 'http://';
				if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) {
					$protocol = 'https://';
				}
				if ($this->redirect[0] == '/') {
					$base = rtrim($host, '/') . '/';
				} else {
					$base = rtrim($script, '/') . '/';
				}
				$this->redirect = $protocol . $base . $this->redirect;
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
