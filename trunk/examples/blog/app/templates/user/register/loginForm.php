	
<h2>Account already exists with different password</h2>
	
<div id="errorfield">
	<p>You already have an account with this username and emailaddress, but with a different password.<br />
	Please sign in below with the correct password.</p>
	<p class="footnote">Forgot your password? We can <a href="#">send you a new password</a>.</p>
</div>

<form action="user/login/" method="post">
	<div>
		<label for="username">Username</label>
		<input id="username" name="username" value="<?php echo htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); ?>" type="text" />
	</div>
	<div>
		<label for="password">Password</label>
		<input id="password" name="password" value="" type="password" class="error" />
		<span 	class="errormsg">&laquo;Please use the correct password</span>	
	</div>
</form>

