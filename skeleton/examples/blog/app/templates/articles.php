<?php

?>

	
	<h2>This is the articles view</h2>
	
	<h3>This is a list of articles</h3>
	<ul>
	<?php foreach($articles as $article){
		echo '<li>'.$article.'</li>';
	} ?>
	</ul>
	