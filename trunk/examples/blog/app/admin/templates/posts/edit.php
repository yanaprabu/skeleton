<h1>Edit Post</h1>
<p>Edit the values of this blog post.</p>
<?php if(isset($errorMsg) && !empty($errorMsg)) { ?>
<p class="error"><?php echo $this->escape($errorMsg); ?></p>
<?php } ?>
<form action="admin/posts/edit/?id=<?php echo (int) $id; ?>" method="post">

	<input type="hidden" name="id" value="<?php echo (int) $id; ?>"/>		 
	<div>
		<label>Title</label>
		<input type="text" name="title" value="<?php echo $this->escape($title); ?>" size="50"/>
	</div>
	<div>
		<label>Slug</label>
		<input type="text" name="permalink" value="<?php echo $this->escape($permalink); ?>" size="50"/>
	</div>
	<div>
		<label>Post date</label>
		<input type="text" name="post_date" value="<?php echo $this->escape($post_date); ?>" size="50"/>
	</div>

	<div>
		<label>Excerpt</label>
		<textarea name="excerpt" cols="50" rows="3"/><?php echo $this->escape($excerpt); ?></textarea>
	</div>
	<div>
		<label>Content</label>
		<textarea name="content" cols="50" rows="10"/><?php echo $this->escape($content); ?></textarea>
	</div>
	<div>
		<label>Comments allowed?</label>
		<label class="checkbox" ><input type="checkbox" class="checkbox" name="comments_allowed" value="1" 
		<?php 
		if(isset($comments_allowed) && $comments_allowed == 1) { echo 'checked="checked"';}  
		?> />Allow comments on this post</label>
	</div>
	<div>
		<label>Active</label>
		<label class="checkbox" ><input type="checkbox" class="checkbox" name="active" value="1" <?php 
		if(isset($active) && $active == '1'){ echo 'checked="checked"';}
		?>/>Post is active</label>
	</div>
	<div>
		<label>Author</label>
		<select name="users_id">
		<?php foreach($authorlist as $author) {
			if($author['id'] === $users_id){
				echo '<option value="' . (int) $author['id'] . ' "selected="selected" >' . $this->escape($author['username']) . '</option>';
			} else {
				echo '<option value="' . (int) $author['id'] . '">' . $this->escape($author['username']) . '</option>';
			}
		}?>
		</select>
	</div>
	<div>
	     <input type="submit" name="submit" class="submit" value="Save">
	</div>
</form>

<div><a href="admin/posts/listing/">return to listing</a></div>
