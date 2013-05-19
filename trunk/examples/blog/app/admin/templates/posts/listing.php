<h1>Manage Posts</h1>
<p>This is a list of blog posts.</p>
<?php
	// display the data
	echo '<table width="100%" border="0">';
	foreach ($rows as $row) {
	    echo '<tr>';
	    echo '<td>' . (int) $row['id'] . '.&nbsp;</td>';
	    echo '<td>' . $this->escape($row['title']) . '</td>';
	    echo '<td><a href="admin/posts/edit/?id=' . (int) $row['id'] . '">edit</a>&nbsp;</td>';
	    echo '</tr>';
	}
	echo '</table>';
	
	echo '<br/><div>Page: ' . (int) $links . '</div>';
?>		 
<div><a href="admin/">return to admin menu</a></div>
