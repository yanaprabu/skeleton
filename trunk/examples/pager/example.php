<?php
include 'config.php';
include 'A/Pager.php';

// check if other example has included this file and created a datasource
if (! isset($datasource)) {
	include 'A/Pager/Array.php';
	
	// initialize an array for testing
	$first = 0;
	$last = 75;
	for ($i=$first; $i<=$last; ++$i) {
		$myarray[$i]['id'] = $i;
		$myarray[$i]['text'] = 'This is row ' . $i;
		$myarray[$i]['sort'] = $last - $i;
	}
	#$myarray = null;
	// create a data object that has the interface needed by the Pager object
	$datasource = new A_Pager_Array($myarray);
	$datasource->orderBy('text');
}

// create pager using values from datasource and request params
$pager = new A_Pager($datasource);
$pager->setRangeSize(5);
$pager->setOrderByFields(array('id', 'text', 'sort'), 'text');

// create a request processor to set pager from GET parameters
#$request = new PagerRequest($pager);
$request = new A_Pager_SessionRequest($pager);
$request->process();
	
if ($pager->getLastRow() > 0) {

	// create a HTML writer to output
	$writer = new A_Pager_HTMLWriter($pager);
	
	// get rows of data
	$start_row = $pager->getStartRow();
	$end_row = $pager->getEndRow();
	$rows = $datasource->getRows($start_row, $end_row);
	
	// display the data
	echo '<table border="1">';
		echo '<tr>';
		echo '<td>&nbsp;</td>';
		echo '<td>' . $writer->getOrderByLink('id', 'Key') . '</td>';
		echo '<td>' . $writer->getOrderByLink('text', 'Text') . '</td>';
		echo '<td>' . $writer->getOrderByLink('sort', 'Sort') . '</td>';
		echo '<td>&nbsp;</td>';
		echo '</tr>';
	$n = $start_row;
	foreach ($rows as $row) {
		echo '<tr>';
		echo '<td>' . $n++ . '.</td>';
		foreach ($row as $value) {
			echo '<td>' . $value . '</td>';
		}
		echo '<td><a href="example_sub_page.php?script=' . basename($_SERVER['SCRIPT_NAME']) . '">Sub Page</a></td>';
		echo '</tr>';
	}
	echo '</table>';
	
	// display the paging links
	echo $writer->getFirstLink() . ' | ' . $writer->getPrevLink() . ' | ' . implode(' | ', $writer->getRangeLinks()) . ' | ' . $writer->getNextLink() . ' | ' . $writer->getLastLink() . '<p/>';
	echo 'Page Size: ' . $writer->getPageSizeLink(5) . ' | ' . $writer->getPageSizeLink(10) . '<p/>';
	
} else {
	
	echo 'No records found.';
	
}

?>
<p/>
<a href="../">Return to Examples</a>
