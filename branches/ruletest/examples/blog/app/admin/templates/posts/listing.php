<h1>Manage Posts</h1>
<p>This is a list of blog posts.</p>
<?php
	// display the data
	echo '<table width="100%" border="0">';
	foreach ($rows as $row) {
	    echo '<tr>';
	    echo '<td>' . $row['id'] . '.&nbsp;</td>';
	    echo '<td>' . $row['title'] . '</td>';
	    echo '<td><a href="admin/posts/edit/?id=' . $row['id'] . '">edit</a>&nbsp;</td>';
	    echo '</tr>';
	}
	echo '</table>';
	
	echo '<br/><div>Page: ' . $links . '</div>';
?>		 
<div><a href="admin/">return to admin menu</a></div>
