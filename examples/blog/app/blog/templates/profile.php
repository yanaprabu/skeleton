<div>

	<h1>Profile</h1>
	
	<p>Edit your profile below, <strong><?php echo $user->get('fname') . ' ' . $user->get('lname'); ?></strong>.</p>

	<span style="color:red"><?php echo $errmsg; ?></span>
	<form action="user/profile/" method="post">
		<p>
			<label>First name</label>
			<input type="text" name="fname" value="<?php echo $user->get('fname'); ?>" size="20"/>
		</p>
		<p>
			<label>Last name</label>
			<input type="lname" name="lname" value="<?php echo $user->get('lname'); ?>" size="20"/>
		</p>
		<p>
			<label>Email</label>
			<input type="lname" name="lname" value="" size="20"/>
		</p>
		<p> Etc. </p>
		
		<p><input type="submit" name="save" value="Save"/></p>
	</form>
	
</div>