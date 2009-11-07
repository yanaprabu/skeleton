<div>

<?php if ($user->isSignedIn()) { ?>

<h1>Register</h1>
<div>
	<p>You are already logged-in as <?php echo $user->get('fname') . ' ' . $user->get('lname'); ?>!</p>
</div>

<?php } else { ?>

<h1>Register</h1>
<div>
	<span style="color:red"><?php echo $errmsg; ?></span>
	<form action="user/register/" method="post">
		<p>
			<label>Username</label>
			<input type="text" name="username" value="<?php if(isset($username)) { echo $username; } ?>" size="20"/>
		</p>
		<p>
			<label>Email</label>
			<input type="email" name="email" value="" size="20"/>
		</p>

		<p><input type="submit" name="register" value="Register"/></p>
	</form>
</div>

<?php } ?>

</div>