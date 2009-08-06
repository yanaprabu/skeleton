<?php
echo $this->partial('layout/header');
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
echo $this->partial('layout/footer');
?>