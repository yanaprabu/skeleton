<html>
<body>
	<h2>Action Controller: Page - Module1 Base Action Controller</h2>
	<ol>
		<li><a href="?controller=example">Default controller, no action specified.</a></li>
		<li><a href="?controller=example&action=foo">Default controller, specific action specified.</a></li>
		<li><a href="?controller=dispatch">Dispatch Action controller, no action specified.</a></li>
		<li><a href="?controller=dispatch&action=foo">Dispatch Action controller, specific action - foo.</a></li>
		<li><a href="?module=module1&controller=example">Module and controller, no action specified.</a></li>
		<li><a href="?module=module1&controller=example&action=bar">Module and controller, specific action specified.</a></li>
	</ol>
	<br/>
	<p><a href="../">Return to Examples</a></p>
<?php
		echo  '<br/>Model Object:<pre>' . print_r($model, 1) . '</pre>';
?>
</body>
</html>