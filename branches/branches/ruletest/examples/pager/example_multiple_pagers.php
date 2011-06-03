<?php
include 'config.php';
include 'A/Pager.php';
include 'A/Pager/Array.php';

// initialize an array to testing
for ($i=43; $i<=75; ++$i) {
	$myarray[$i] = 'This is row ' . $i;
}
// create a data object that has the interface needed by the Pager object
$datasource = new A_Pager_Array($myarray);

// create pager using values from datasource and request params
$pager = new A_Pager($datasource);
$pager->setRangeSize(5);
$request = new A_Pager_Request($pager);
$request->process();
$writer = new A_Pager_HTMLWriter($pager);
$start_row = $pager->getStartRow();
$end_row = $pager->getEndRow();

$pager2 = new A_Pager($datasource);
$pager2->setPageSize(5);
$pager2->setRangeSize(3);
$pager2->setPageParameter('page2');
$pager2->setPageSizeParameter('page_size2');
$pager2->setLastRowParameter('last_row2');
$request->__construct($pager2);
$request->process();
$writer2 = new A_Pager_HTMLWriter($pager2);
$start_row2 = $pager2->getStartRow();
$end_row2 = $pager2->getEndRow();

$writer->setExtraParameters($writer2->getParameters($pager2->getCurrentPage()));
$writer2->setExtraParameters($writer->getParameters($pager->getCurrentPage()));

$rows = $datasource->getRows($start_row, $end_row);
$n = $start_row;
#echo '<pre>' . print_r($rows, 1) . '</pre>';
echo '<table border="2"><tr><td valign="top">';

echo '<table border="1">';
foreach ($rows as $value) {
	echo '<tr>';
	echo '<td>' . $n++ . '.</td><td>' . $value . '</td>';
	echo '</tr>';
}
echo '</table>';

echo $writer->getPrevLink() . ' | ' . implode(' | ', $writer->getRangeLinks()) . ' | ' . $writer->getNextLink() . '<p/>';

include 'A/Pager/HTMLWriterJump.php';
$jump = new A_Pager_HTMLWriterJump($pager);
$jump->setExtraParameters($writer2->getParameters($pager2->getCurrentPage()));
$jump->setCurrentPageTemplate('Now on page {current_page}!');
echo $jump->render();


echo '</td><td width="50">&nbsp;</td><td valign="top">';

$rows = $datasource->getRows($start_row2, $end_row2);
$n = $start_row2;
#echo '<pre>' . print_r($rows, 1) . '</pre>';
echo '<table border="1">';
foreach ($rows as $value) {
	echo '<tr>';
	echo '<td>' . $n++ . '.</td><td>' . $value . '</td>';
	echo '</tr>';
}
echo '</table>';

echo $writer2->getPrevLink() . ' | ' . implode(' | ', $writer2->getRangeLinks()) . ' | ' . $writer2->getNextLink() . '<p/>';

echo 'Rows per page: ' . $writer2->getPageSizeLink(5) . ' | ' . $writer2->getPageSizeLink(10) . '<p/>';

echo '</td></tr></table>';
?>
<p/>
<a href="../">Return to Examples</a>
