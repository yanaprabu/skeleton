<html>
<body>
	<h2>Sign in</h2>
	<p>User admin/admin to log-in.</p>
	<?php if(isset($message)) { echo $message; } ?>
	<form action="<?php if(isset($BASE)) { echo $BASE; } ?>login/" method="post" id="loginform">
		<input type="hidden" name="action" value="login"/>
		<input type="hidden" name="op" value="login"/>
		<span style="color: red"><?php if(isset($errmsg)) { echo $errmsg; } ?></span>
		<fieldset>
			<div>
  				<label>Username</label>
				<input type="text" name="username" value="<?php if(isset($username)){echo htmlentities($username, ENT_QUOTES, 'UTF-8');} ?>" size="20" class="text" />
				
			</div>
			<div>
  				<label>Password</label>
				<input type="text" name="password" value="" size="20" class="text" />
				
			</div>
			<div>
  				<input type="submit" name="submit" value="Log in"/>
			</div>    
		</fieldset>
	</form>
</body>
</html>