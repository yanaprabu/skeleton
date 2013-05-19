<h1>Edit Post</h1>
<p>Edit the values of this comment.</p>
<?php if(isset($errorMsg) && !empty($errorMsg)) { ?>
<p class="error"><?php echo $this->escape($errorMsg); ?></p>
<?php } ?>
<form action="admin/comments/edit/?id=<?php echo (int) $id; ?>" method="post">
	<input type="hidden" name="id" value="<?php echo (int) $id; ?>"/>
	<input type="hidden" name="posts_id" value="<?php echo (int) $posts_id; ?>"/>		 
	<div>
		<label>Author</label>
		<input type="text" name="author" value="<?php echo $this->escape($author); ?>" size="50"/>
	</div>
	<div>
		<label>Author email</label>
		<input type="text" name="author_email" value="<?php echo $this->escape($author_email); ?>" size="50"/>
	</div>
	<div>
		<label>Author url</label>
		<input type="text" name="author_url" value="<?php echo $this->escape($author_url); ?>" size="50"/>
	</div>
	<div>
		<label>Users_id</label>
		<input type="text" name="users_id" value="<?php echo $this->escape($users_id); ?>" size="50"/>
	</div>
	<div>
		<label>Comment date</label>
		<input type="text" name="comment_date" value="<?php echo $this->escape($comment_date); ?>" size="50"/>
	</div>
	<div>
		<label>Comment</label>
		<textarea name="comment" cols="50" rows="10"/><?php echo $this->escape($comment); ?></textarea>
	</div>
	<div>
		<label class="checkbox" ><input type="checkbox" class="checkbox" name="approved" value="1" 
		<?php 
		if(isset($approved) && $approved == 1) { echo 'checked="checked"';}  
		?> />Approve comment</label>
	</div>
	<div>
	     <input type="submit" name="save" class="submit" value="Save">
	</div>
</form>

	<div><a href="admin/posts/listing/">return to listing</a></div>
