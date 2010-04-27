<h1>Manage Posts</h1>
<p>This is a list of blog posts.</p>
<?php
	// display the data
	echo '<table border="0">';
	foreach ($rows as $row) {
	    echo '<tr>';
	    echo '<td>' . $row['id'] . '.&nbsp;</td><td>' . $row['title'] . '</td>';
	    echo '</tr>';
	}
	echo '</table>';
	
	echo '<br/><div>Page: ' . $links . '</div>';
?>		 
