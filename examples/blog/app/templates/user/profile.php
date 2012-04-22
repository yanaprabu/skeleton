<div>

	
	<h1>Profile</h1>
	
	<p>Edit your profile below, <strong><?php echo $data['firstname'] . ' ' . $data['lastname']; ?></strong>.</p>
	
	<?php 
		if(isset($messages)) { 
		echo '<ul>';
		foreach($messages as $msg){
			echo '<li>' . $msg . '</li>';
		}
		echo '</ul>';
		}
	?>
	
	<form action="user/profile/" method="post">
		<p>
			<label>First name</label>
			<input type="text" name="firstname" value="<?php echo $data['firstname'];//$user->get('firstname'); ?>" size="20"/>
		</p>
		<p>
			<label>Last name</label>
			<input type="text" name="lastname" value="<?php echo $data['lastname'];//$user->get('lastname'); ?>" size="20"/>
		</p>
		<p>
			<label>Email</label>
			<input type="email" name="email" value="<?php echo $data['email'];//$user->get('email'); ?>" size="20"/>
		</p>
		<p> Etc. </p>
		
		<p><input type="submit" name="save" value="Save"/></p>
	</form>
	
</div>