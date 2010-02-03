<html>
<head>
<title>Skeleton - Pagination example - basic</title>
</head>
<body>
<?php
include 'config.php';
include dirname(__FILE__) . '/../../A/autoload.php';
#include 'Datasource.php';
#include 'A/Pagination/Request.php';

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
$pager->setRangeSize(3)->process();

$url = new A_Pagination_Helper_Url();
$url->set('page', $pager->getCurrentPage());
$url->set('order_by', $pager->getOrderBy());

$rows = $pager->getItems();

// display the paging links ... should this go in a template?
$links = array();
if ($pager->isPage(-1)) $links[] = "<a href=\"" . $url->render(false, array ('page' => $pager->getPage(-1))) . "\">Previous</a>";
if (!$pager->inPageRange($pager->getFirstPage())) $links[] = "<a href=\"" . $url->render(false, array ('page' => $pager->getFirstPage())) . "\">1</a> ... ";
//if ($pager->isIntervalPage(-10)) $links[] = "<a href=\"" . $url->render(false, array ('page' => $pager->getPage(-10))) . "\">" . $pager->getPage(-10) . "</a> ...";
foreach ($pager->getPageRange() as $page) {
	if ($page != $pager->getCurrentPage()) {
		$links[] = "<a href=\"" . $url->render(false, array ('page' => $page)) . "\">$page</a>";
	} else {
		$links[] = $page;
	}
}
//if ($pager->isIntervalPage(+10)) $links[] = " ... <a href=\"" . $url->render(false, array ('page' => $pager->getPage(+10))) . "\">" . $pager->getPage(+10) . "</a>";
if (!$pager->inPageRange($pager->getLastPage())) $links[] = " ... <a href=\"" . $url->render(false, array ('page' => $pager->getLastPage())) . "\">" . $pager->getLastPage() . "</a>";
if ($pager->isPage(+1)) $links[] = "<a href=\"" . $url->render(false, array ('page' => $pager->getPage (+1))) . "\">Next</a>";

echo '<div>';
echo implode(' ', $links); // eventually $pager->render() -- or $links->render()?
echo '</div>';

// display the data
echo '<table border="1">';
echo '<tr><th><a href="' . $url->render (false, array ('order_by' => '')) . '">Row</a></th><th><a href="' . $url->render (false, array ('order_by' => 'title')) . '">Title</a></th><th><a href="' . $url->render (false, array ('order_by' => 'month')) . '">Month</a></th></tr>';
$n = 1;
foreach ($rows as $value) {
	echo '<tr>';
	echo '<td>' . $n++ . '.</td><td>' . $value['title'] . '</td><td>' . $value['month'] . '</td>';
	echo '</tr>';
}
echo '</table>';

echo '<div>';
echo implode(' ', $links);
echo '</div>';

#dump($pager);
?>
<p/>
<a href="../">Return to Examples</a>
</p>

</body>
</html>