<html>
<body>
	<h2>Sign out</h2>
	<?php if(isset($message)) { echo $message; } ?>
	<p>To log-out please <a href="<?php if(isset($BASE)) { echo $BASE; } ?>login/?op=logout">click here</a>.</p>
</body>
</html>