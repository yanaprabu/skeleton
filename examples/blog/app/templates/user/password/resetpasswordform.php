
<h2>Please fill in a new password</h2>
<?php if(isset($message)){
	echo $message;
}?>
<form action="user/setpassword/" method="post">
	<input type="hidden" name="resetkey" value="<?php echo $resetkey; ?>" />
	<div>
		<label for="password">Password</label>
		<input id="password" name="password" value="" type="password" />
		<span 	class="errormsg">&laquo;Please use the correct password</span>	
	</div>
	<div>
		<label for="passwordagain">Password again</label>
		<input id="passwordagain" name="passwordagain" value="" type="password" />
	</div>
	<div>
		<input id="submit" name="submit" value="Send" type="submit" />	
	</div>
</form>