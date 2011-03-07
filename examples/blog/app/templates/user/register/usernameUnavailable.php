
<div class="fail">
	<h2>Registration failed: Username not available</h2>
	<p>We're sorry, but the username "<?php echo htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); ?>" has already been chosen by another user.<br />Please choose a different username.</p>
</div>

<form action="user/register/" method="post">
	<div>
		<label for="username">Username</label>
		<input id="username" name="username" value="<?php echo htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); ?>" type="text" class="error" />
		<span 	class="errormsg">&laquo;Please choose a different username</span>	
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
		<input type="submit" value="Create my account" /><span class="secondary"> or <a href="#">cancel</a></span>
	</div>
	<div class="footnote">
		Already got an account? <a href="user/login/">Please Sign In!</a>
	</div>
</form>

