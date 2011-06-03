<?php

?>
<html>
<body>
	<h2>Front Controller: Default Page - Base Action Controller</h2>
	<ol>
		<li><a href="?controller=example">Default controller, no action specified.</a></li>
		<li><a href="?controller=example&action=foo">Default controller, specific action - foo.</a></li>
		<li><a href="?controller=example&action=bar">Default controller, specific action - bar.</a></li>
		<li><a href="?controller=dispatch">Dispatch Action controller, no action specified.</a></li>
		<li><a href="?controller=dispatch&action=foo">Dispatch Action controller, specific action - foo.</a></li>
		<li><a href="?controller=dispatch&action=bar">Dispatch Action controller, specific action - bar.</a></li>
		<li><a href="?module=module1&controller=example">Module and controller, no action specified.</a></li>
		<li><a href="?module=module1&controller=example&action=bar">Module and controller, specific action specified.</a></li>
	</ol>
	<br/>Flash var foo: <?php echo isset($foo) ? $foo : 'NULL'; ?><br/>
	<br/>Helper bar: <?php echo isset($bar) ? $bar : 'NULL'; ?><br/>

	<br/>
	<p><a href="../">Return to Examples</a></p>
</body>
</html>
