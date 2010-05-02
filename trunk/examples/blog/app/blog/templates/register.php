<div>

<?php if ($user->isLoggedIn()) { ?>

	<h1>Register</h1>
	<div>
		<p>You are already logged-in as <?php echo $user->get('firstname') . ' ' . $user->get('lastname'); ?>!</p>
	</div>

<?php } else { ?>
	
	<?php
	/*
	Registration pages

	    * S0 - Show Registration form
		* E1 - Registration form submitted; missing fields or unvalid values
		* E2 - Registration form submitted; user already has another account with the same email address
		* E3 - Registration form submitted; username not available
		* E4 - Registration form submitted; account created; activation email sent
		* E5 - Registration form submitted; username/email combination already exists, but with different password
		* E6 - Registration form submitted; username/email combination already exists; password is correct
		* E7 - Registration form submitted; account already exists but is not yet activated
	
	*/
	
	// S0 Default status: no errors. Show registration form
	if($errorstatus === 'S0') { ?>
		<h3>Register</h3>
		<form action="user/register/" method="post">
			<div>
				<label for="username">Username</label>
				<input type="text" name="username" id="username" value="<?php if(isset($_POST['username'])) { echo htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); } ?>" size="20"/>
			</div>
			<div>
				<label for="email">Email adress</label>
				<input type="email" name="email" id="email" value="<?php if(isset($_POST['email'])) { echo htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8'); } ?>" size="20"/>
			</div>
			<div>
				<label for="password">Password</label>
				<input id="password" name="password" value="<?php if(isset($_POST['password'])) { echo htmlentities($_POST['password'], ENT_QUOTES, 'UTF-8'); } ?>" type="password" />
			</div>
			<div>
				<label for="passwordagain">Repeat password</label>
				<input id="passwordagain" name="passwordagain" value="<?php if(isset($_POST['passwordagain'])) { echo htmlentities($_POST['passwordagain'], ENT_QUOTES, 'UTF-8'); } ?>" type="password" />
			</div>
			<div>
				<input type="checkbox" id="tos" name="tos" value="agree" class="checkbox" <?php if(isset($_POST['tos']) && $_POST['tos'] == 'agree') { echo 'checked="checked"'; } ?> >
				<label for="tos" class="checkbox">I agree with the </label><a href="#">Terms of Service</a>
			</div>
			<div>
				<input type="submit" value="Create my account" class="button" />
				<span class="secondary"> or 
				<a href="#">cancel</a></span>
			</div>
		</form>
	<?php } ?>
	
	<?php
	// E1 Default status: no errors. Show registration form
	if($errorstatus === 'E1') { ?>
		<h3>Register</h3>
		<p>Please correct the following errors: </p><?php 
		if(is_array($errmsg)){
			echo '<ul class="warning">';
			foreach($errmsg as $v){
				foreach($v as $value){
					echo '<li>' . $value . '</li>';
				}
			}
			echo '</ul>';
		}

		?>
		<form action="user/register/" method="post">
			<div>
				<label for="username">Username</label>
				<input type="text" name="username" id="username" value="<?php echo htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); ?>" size="20"/>
			</div>
			<div>
				<label for="email">Email adress</label>
				<input type="email" name="email" id="email" value="<?php echo htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8'); ?>" size="20"/>
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
				<input type="checkbox" id="tos" name="tos" value="agree" class="checkbox" <?php if(isset($_POST['tos']) && $_POST['tos'] == 'agree') { echo 'checked="checked"'; } ?>>
				<label for="tos" class="checkbox">I agree with the </label><a href="#">Terms of Service</a>
			</div>
			<div>
				<input type="submit" value="Create my account" class="button" />
				<span class="secondary"> or 
				<a href="#">cancel</a></span>
			</div>
		</form>
	<?php } ?>
	
	<?php
	// If the registered email already has an account
	if($errorstatus === 'E2'){ ?>
		<div class="info">
			<h2>Did you know you already have an account?</h2>
			<p>Just to let you know: you already have an account with this email address, with username <strong><?php echo $user->get('username'); ?></strong>.</p>
			<p>If you are trying to create another account, please <strong>submit the form below</strong> with a <strong>different email adress</strong><br />
			or<br />
			<strong><a href="user/login/">Sign In to your <?php echo $user->get('username'); ?> account</a></strong>.</p>
			<p class="footnote">Forgot the password for <?php echo $user->get('username'); ?>'s account? 
				We can <a href="user/sendnewpassword/?user=<?php echo $user->get('username'); ?>">send you a new password</a>.</p>
		</div>
		<!--  show form with values prefilled -->
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
				<input type="checkbox" id="tos" name="tos" value="agree" class="checkbox" <?php if(isset($_POST['tos']) && $_POST['tos'] == 'agree') { echo 'checked="checked"'; } ?>>
				<label for="tos">I agree with the </label><a href="#">Terms of Service</a>
			</div>
			<div>
				<input type="submit" value="Create my other account" /><span class="secondary"> or 
					<a href="app2-random-app-page-signed-in.php?c=3">cancel</a></span>
			</div>
			<div class="footnote">
				Already got an account? <a href="user/login/">Please Sign In!</a>
			</div>
		</form>
		
	<?php
	// If username not available/allowed/already taken
	} else if ($errorstatus === 'E3') { ?>
		<div class="fail">
			<h2>Registration failed: Username not available</h2>
			<p>We're sorry, but the username "<?php echo $user->get('username'); ?>" has already been chosen by another user.<br />Please choose a different username.</p>
		</div>
		<!-- Show form with error on username -->
		<form action="user/register/" method="post">
			<div>
				<label for="username">Username</label>
				<input id="username" name="username" value="<?php echo $user->get('username'); ?>" type="text" class="error" />
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
				<input type="checkbox" id="tos" name="tos" value="agree" class="checkbox" <?php if(isset($_POST['tos']) && $_POST['tos'] == 'agree') { echo 'checked="checked"'; } ?>>
				<label for="tos">I agree with the </label><a href="#">Terms of Service</a>
			</div>
			<div>
				<input type="submit" value="Create my account" /><span class="secondary"> or <a href="#">cancel</a></span>
			</div>
			<div class="footnote">
				Already got an account? <a href="user/login/">Please Sign In!</a>
			</div>
		</form>

	<?php	
	// Account created; activation email sent
	} else if($errorstatus === 'E4') {  ?>
		<div class="info">
			<h2>Account created; please check your email</h2>
			<p>Your account has been created, but you'll have to activate it before you can use it.</p>
			<p>We've just sent an activation email to <?php echo $user->get('email'); ?>. Please <strong>check your email</strong>, 
				and click on the <strong>activation link</strong> in the email we sent you.</p>
		</div>

	<?php
	// Username/email combination already exists, but with different password	
	} else if($errorstatus === 'E5') { ?>
		<div class="info">
			<h2>Account already exists with different password</h2>
			<p>You already have an account with this username and emailaddress, but with a different password.<br />
			Please sign in below with the correct password.</p>
			<p class="footnote">Forgot your password? We can <a href="#">send you a new password</a>.</p>
		</div>
		<!-- Show form with error on password-->
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

	<?php
	// Username/email combination already exists; password is correct
	} else if ($errorstatus === 'E6') { ?>
		<div class="info">
			<h2>You already have this account; you are now Signed In</h2>
			<p>You tried to register a new account, but you've already created it before (with this exact same information). You are 
				now signed in.</p>
		</div>

	<?php
	// Account already exists but is not yet activated	
	} else if ($errorstatus === 'E7') { ?>
		<div class="info">
			<h2>You already created this account. Please activate it.</h2>
			<p>You have already created this account before, but you have not yet activated it. You need to activate 
				your account before you can use it.</p>
			<p>Please <strong>activate your account</strong> by clicking on the link in the activation email (we sent you 
			that when you first created your account).</p>
			<p class="footnote">Lost your activation email? We can <a href="user/resendactivationemail/">send you a new activation email</a>.</p>
		</div>
	<?php
	}
	?>
	
	

<?php } ?>

</div>