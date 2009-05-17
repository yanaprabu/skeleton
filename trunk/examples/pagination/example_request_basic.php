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
for ($i=0; $i<=750; ++$i) {
	$myarray[$i]['title'] = 'This is row ' . $i;
	$myarray[$i]['month'] = date ('F', time() + ($i * 60 * 60 * 24 * 30));
}
#$myarray = null;
// create a data object that has the interface needed by the Pager object
$datasource = new Datasource($myarray);

// create a request processor to set pager from GET parameters
$pager = new A_Pagination_Request($datasource);
$pager->process();

$url = new A_Pagination_Url();
$url->set ('page', $pager->getCurrentPage());
$url->set ('order_by', $pager->getOrderBy());

$rows = $pager->getItems();

// display the data
echo '<table border="1">';
echo '<tr><th>Row</th><th><a href="' . $url->render (false, array ('order_by' => 'title')) . '">Title</a></th><th><a href="' . $url->render (false, array ('order_by' => 'month')) . '">Month</a></th></tr>';
$n = 1;
foreach ($rows as $value) {
	echo '<tr>';
	echo '<td>' . $n++ . '.</td><td>' . $value['title'] . '</td><td>' . $value['month'] . '</td>';
	echo '</tr>';
}
echo '</table>';

echo '<div>';
// display the paging links
$links = array();
if ($pager->isPage(-2)) $links[] = "<a href=\"" . $url->render(false, array ('page' => $pager->getFirstPage())) . "\">First</a>";
if ($pager->isPage(-1)) $links[] = "<a href=\"" . $url->render(false, array ('page' => $pager->getPage(-1))) . "\">Previous</a>";
if ($pager->isIntervalPage(-10)) $links[] = "<a href=\"" . $url->render(false, array ('page' => $pager->getPage(-10))) . "\">" . $pager->getPage(-10) . "</a> ...";
for ($n=-5; $n<=5; ++$n) {
	if ($pager->isPage($n)) {
		$page = $pager->getPage($n);
		if ($page != $pager->getCurrentPage()) {
			$links[] = "<a href=\"" . $url->render(false, array ('page' => $page)) . "\">$page</a>";
		} else {
			$links[] = $page;
		}
	}
}
if ($pager->isIntervalPage(+10)) $links[] = " ... <a href=\"" . $url->render(false, array ('page' => $pager->getPage(+10))) . "\">" . $pager->getPage(+10) . "</a>";
if ($pager->isPage(+1)) $links[] = "<a href=\"" . $url->render(false, array ('page' => $pager->getPage (+1))) . "\">Next</a>";
if ($pager->isPage(+2)) $links[] = "<a href=\"" . $url->render(false, array ('page' => $pager->getLastPage())) . "\">Last</a>";
echo implode(' ', $links);
echo '</div>';

#dump($pager);
?>
<p/>
<a href="../">Return to Examples</a>
</p>

</body>
</html>