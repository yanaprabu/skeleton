<div>

<?php if ($user->isLoggedIn()) { ?>
	<h1>Logout</h1>
	<div>
		<p>You are logged-in as <?php echo $user->get('username') . ' ' . $user->get('firstname') . ' ' . $user->get('lastname'); ?></p>
		<form action="user/logout/" method="post">
			<p><input type="submit" name="logout" value="Logout"/></p>
		</form>
	</div>
<?php } else { ?>
	<h1>Login</h1>
	<div>
		<span style="color:red"><?php echo $errmsg; ?></span>
		<form action="user/login/" method="post">
			<p>
				<label>Username</label>
				<input type="text" name="username" value="<?php echo $username; ?>" size="20"/>
			</p>
			<p>
				<label>Password</label>
				<input type="password" name="password" value="" size="20"/>
			</p>

			<p><input type="submit" name="login" value="Login"/></p>
		</form>
		<a href="user/password/">Forgot password?</a>
	</div>
<?php } ?>

</div>