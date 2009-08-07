<?php
echo $this->partial('layout/header');
// partial loop with two parameters where the 1st is the name and the 2nd is an array of values to replace name each loop
echo $this->partialLoop('menu', 'menuitem', $this->menuitems);
?>
	<ol>
		<li><a href="?controller=example">Default controller, no action specified.</a></li>
		<li><a href="?controller=example&action=foo">Default controller, specific action, this->autoRender().</a></li>
		<li><a href="?controller=example&action=bar">Default controller, specific action, this->load->setResponse()->template().</a></li>
		<li><a href="?module=module1&controller=example">Module and controller, no action specified.</a></li>
		<li><a href="?module=module1&controller=example&action=bar">Module and controller, specific action specified.</a></li>
	</ol>
<?php
// partial loop with single parameter which is an array of assoc arrays
echo $this->partialLoop('colors', $this->colors);
echo $this->partial('layout/footer');
?>