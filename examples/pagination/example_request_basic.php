<html>
<head>
<title>Skeleton - Pagination example - basic</title>
</head>
<body>
<?php
include 'config.php';
include 'Datasource.php';
include 'A/Pagination/Request.php';

// initialize an array for testing
for ($i=0; $i<=75; ++$i) {
	$myarray[$i] = 'This is row ' . $i;
}
#$myarray = null;
// create a data object that has the interface needed by the Pager object
$datasource = new Datasource($myarray);

// create a request processor to set pager from GET parameters
$pager = new A_Pagination_Request($datasource);
$pager->process();
	
$rows = $pager->pager()->getItems();

// display the data
echo '<table border="1">';
$n = 1;
foreach ($rows as $value) {
	echo '<tr>';
	echo '<td>' . $n++ . '.</td><td>' . $value . '</td>';
	echo '</tr>';
}
echo '</table>';

echo '<div>';
// display the paging links
$current_page = $pager->pager()->getCurrentPage();
$links = array();
for ($n=-5; $n<=5; ++$n) {
	if ($pager->pager()->isPage($n)) {
		$page = $pager->pager()->getPage($n);
		if ($page != $current_page) {
			$links[] = "<a href=\"?page=$page\">$page</a>";
		} else {
			$links[] = $page;
		}
	}
}
echo implode(' ', $links);
echo '</div>';

#dump($pager);
?>
<p/>
<a href="../">Return to Examples</a>
</p>

</body>
</html>