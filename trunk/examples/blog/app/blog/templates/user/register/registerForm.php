
<h2>Register</h2>

<div id="errorfield">
	<?php 
	if(isset($messages) && !empty($messages)){ 
		echo '<p>Please correct the following errors:</p><ul>';
		foreach($messages as $v){ 
			echo '<li>'. $v .'</li>';
		}
		echo '</ul>';
	}
	?>
</div>
	
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
		<span class="secondary"> or <a href="#">cancel</a></span>
	</div>
</form>
	

	
