<div>
	
	<h1>Forgot your password?</h1>
	<p>Send this form and your password is sent to the email address you registered with.</p>
	<form action="user/password/" method="post">
		
		<p>
			<label>Username</label>
			<input type="text" name="username" value="" size="20"/> <span style="color:red"><?php echo $errmsg; ?></span>
		</p>
		<p><input type="submit" name="login" value="Submit"/></p>
	</form>
	
	
</div>