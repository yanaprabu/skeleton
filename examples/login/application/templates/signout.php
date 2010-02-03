<html>
<body>
	<h2>Sign out</h2>
	<?php if(isset($message)) { echo $message; } ?>
	<p>To sign out please <a href="<?php if(isset($BASE)) { echo $BASE; } ?>signin/?op=signout">click here</a>.</p>
</body>
</html>