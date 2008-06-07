<?php

class page4 {
	var $_response = null;

	function forceError($locator) {
		echo "FORCE ERROR\n";
		return false;
	}
	
	function run($locator) {
		if ($this->_response) {
			$message = 'Response object set as property by setter injection. ';
		} else {
			$message = 'Response object created locally, no setter injection. ';
			$this->_response = $locator->get('Response');
		}
		$content = "
<html>
<body>
	<h2>Front Controller: Page 4 - Action</h2>
	<p>$message</p>
	<a href=\"?controller=home\">Return Home</a>
</body>
</html>
";
		$this->_response->setContent($content);
	}

}

?>