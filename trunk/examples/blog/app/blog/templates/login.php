<div>

<?php if ($user->isSignedIn()) { ?>
	<h1>Logout</h1>
	<div>
		<p>You are logged-in as <?php echo $user->get('fname') . ' ' . $user->get('lname'); ?></p>
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
				<label>User ID</label>
				<input type="text" name="userid" value="<?php echo $userid; ?>" size="20"/>
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