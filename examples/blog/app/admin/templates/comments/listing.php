<h1>Manage comments</h1>
<p>This is a list of comments.</p>
<?php
	// display the data
	echo '<table width="100%" border="0">';
	foreach ($rows as $row) {
	    echo '<tr>';
	    echo '<td>' . (int) $row['id'] . '.&nbsp;</td>';
	    echo '<td>' . $this->escape($row['author']) . '</td>';
		echo '<td>' . $this->escape($row['comment']) . '</td>';
	    echo '<td><a href="admin/comments/edit/?id=' . (int) $row['id'] . '">edit</a>&nbsp;</td>';
	    echo '</tr>';
	}
	echo '</table>';
	
	echo '<br/><div>Page: ' . (int) $links . '</div>';
?>		 
<div><a href="admin/">return to admin menu</a></div>
