<div>
	
	<h1>Forgot your password?</h1>
	<p>Fill in your email address below and instructions to reset your password will be sent to you.</p>
	<form action="user/password/" method="post">
		
		<p>
			<label>Email address</label>
			<input type="text" name="email" value="" size="20"/> <span style="color:red"><?php if(isset($errmsg)) { echo htmlentities($errmsg, ENT_QUOTES, 'UTF-8'); } ?></span>
		</p>
		<p><input type="submit" name="login" value="Submit"/></p>
	</form>
	
	
</div>