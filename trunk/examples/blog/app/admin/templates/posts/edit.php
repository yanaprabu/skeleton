<h1>Edit Post</h1>
<p>Edit the values of this blog post.</p>
<span style="error"><?php echo $errorMsg; ?></span>

<form action="blog/post/edit/" method="post">

	<input type="hidden" name="id" value="<?php echo $id; ?>"/>		 
	
	<div style="form_label">permalink</div>
	<div style="form_field"><input type="text" name="permalink" value="<?php echo $permalink; ?>" size="50"/></div>
	
	<div style="form_label">title</div>
	<div style="form_field"><input type="text" name="title" value="<?php echo $title; ?>" size="50"/></div>
	
	<div style="form_label">content</div>
	<div style="form_field"><textarea name="content" cols="50" rows="10"/><?php echo $content; ?></textarea></div>
	
	<div style="form_label">comments_allowed</div>
	<div style="form_field"><?php echo $comments_allowed; ?></div>
	
	<div style="form_label">post_type</div>
	<div style="form_field"><input type="text" name="post_type" value="<?php echo $post_type; ?>" size="50"/></div>
	
	<div style="form_label">active</div>
	<div style="form_field"><?php echo $active; ?></div>
	
	<div style="form_label">excerpt</div>
	<div style="form_field"><textarea name="content" cols="50" rows="3"/><?php echo $excerpt; ?></textarea></div>

</form>

<div><a href="admin/posts/listing/">return to listing</a></div>
