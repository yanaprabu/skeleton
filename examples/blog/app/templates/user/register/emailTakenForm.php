
<h2>Did you know you already have an account?</h2>

<div class="info">
	<p>Just to let you know: you already have an account with this email address, with username <strong><?php echo htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); ?></strong>.</p>
	<p>If you are trying to create another account, please <strong>submit the form below</strong> with a <strong>different email adress</strong><br />
	or<br />
	<strong><a href="user/login/">Sign In to your <?php echo htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); ?> account</a></strong>.</p>
	<p class="footnote">Forgot the password for <?php echo htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); ?>'s account? 
		We can <a href="user/sendnewpassword/?user=<?php echo htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); ?>">send you a new password</a>.</p>
</div>

<form action="user/register/" method="post">
	<div>
		<label for="username">Username</label>
		<input id="username" name="username" value="<?php echo htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); ?>" type="text" />
	</div>
	<div>
		<label for="email">Email address</label>
		<input id="email" name="email" value="<?php echo htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8'); ?>" type="text" />
	</div>
	<div>
		<label for="password">Password</label>
		<input id="password" name="password" value="<?php echo htmlentities($_POST['password'], ENT_QUOTES, 'UTF-8'); ?>" type="password" />
	</div>
	<div>
		<label for="passwordagain">Repeat password</label>
		<input id="passwordagain" name="passwordagain" value="<?php echo htmlentities($_POST['passwordagain'], ENT_QUOTES, 'UTF-8'); ?>" type="password" />
	</div>
	<div>
		<input type="checkbox" id="tos" name="tos" value="agree" class="checkbox" <?php if(isset($_POST['tos']) && $_POST['tos'] == 'agree') { echo 'checked="checked"'; } ?> >
		<label for="tos" class="checkbox">I agree with the </label><a href="#">Terms of Service</a>
	</div>
	<div>
		<input type="submit" value="Create my other account" /><span class="secondary"> or <a href="app2-random-app-page-signed-in.php?c=3">cancel</a></span>
	</div>
	<div class="footnote">
		Already got an account? <a href="user/login/">Please Sign In!</a>
	</div>
</form>
