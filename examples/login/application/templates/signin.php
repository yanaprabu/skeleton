<html>
<body>
	<h2>Sign in</h2>
	<?php if(isset($message)) { echo $message; } ?>
	<form action="<?php if(isset($BASE)) { echo $BASE; } ?>signin/" method="post" id="loginform">
		<input type="hidden" name="action" value="signin"/>
		<input type="hidden" name="op" value="signin"/>
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