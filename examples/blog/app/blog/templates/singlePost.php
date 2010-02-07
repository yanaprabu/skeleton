
	
	<h3>This is the post you selected</h3>
	
	<?php foreach($content as $article){
		echo '<div class="post">';
		echo '<h2 class="post_title"><a href="'. $article['permalink'] . '">' . $article['title'] . '</a></h2>';
		echo '<p class="post_meta">On ' .  $article['post_date'] . ' by ' . $article['username'] . '</p>';
		echo '<p>' .  $article['excerpt'] . '</p>';
		echo '<p>' .  $article['content'] . '</p>';
		echo '	</div>';
	} ?>

	<h3>Leave a reply</h3>
	<form id="comment_form" action="" method="post">
		<p>
			<label>Name</label>
			<input type="text" name="author" value="" >
		</p>
		<p>
			<label>Email</label>
			<input type="text" name="author_email" value="" >
		</p>
		<p>
			<label>Url</label>
			<input type="text" name="author_url" value="" >
		</p>
		<p>
			<label>Name</label>
			<textarea name="comment" rows="5" cols="20"></textarea>
		</p>
		<p>
			<input type="submit" name="submit" value="Send" >
		</p>	
		
	</form>
	
	<div class="comment_list">
	<h3>Comments for this post:</h3>
	<p>not yet ;)</p>
	</div>