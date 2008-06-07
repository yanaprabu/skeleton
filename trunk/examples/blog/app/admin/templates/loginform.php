<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<base href="http://skeleton/examples/blog/" />
	<!-- TODO: Fix this BASE config mess -->
	
</head>
<body>
<div class="error">
<?php echo isset($msg) ? $msg : NULL; ?>
</div>

<form action="admin/login/" method="post" >
	<label>Username </label><input type="text" name="username" value="<?php echo isset($username) ? $username : NULL; ?>" /><br>
	<label>Password </label><input type="password" name="password" value="" /><br>
	<input type="submit" name="submit" value="submit" />
</form>

</body>
</html>