<?php
require_once('A/DataContainer.php');
require_once('A/Pagination.php');

class DatasourceMock {
	protected $items = array(
							array('id'=>1, 'name'=>'One', 'color'=>'blue'),
							array('id'=>2, 'name'=>'Two', 'color'=>'red'),
							array('id'=>3, 'name'=>'Three', 'color'=>'green'),
							array('id'=>4, 'name'=>'Four', 'color'=>'blue'),
							array('id'=>5, 'name'=>'Five', 'color'=>'blue'),
							array('id'=>6, 'name'=>'Six', 'color'=>'black'),
							array('id'=>7, 'name'=>'Seven', 'color'=>'green'),
							array('id'=>8, 'name'=>'Eight', 'color'=>'blue'),
							);
							
	public function getItems($start, $size) {
		return slice($this->items, $start-1, $size);
	}
	
	public function getNumItems() {
		return count($this->items);
	}
}

class PaginationTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testPaginationFirstPage() {
 		$datasource = new DatasourceMock();
		$pager = new A_Pagination($datasource, 5);
		
		$result = true;
		$this->assertEqual($pager->currentPage(), 1);
		$this->assertEqual($pager->firstPage(), 1);
		$this->assertEqual($pager->lastPage(), 2);
		$this->assertEqual($pager->firstItem(), 1);
		$this->assertEqual($pager->lastItem(), 5);
		$this->assertEqual($pager->getNumItems(), $datasource->getNumItems());
	}
	
	function testPaginationSecondPage() {
 		$datasource = new DatasourceMock();
		$pager = new A_Pagination($datasource, 5);
		$pager->setCurrentPage(2);
		
		$result = true;
		$this->assertEqual($pager->currentPage(), 2);
		$this->assertEqual($pager->firstPage(), 1);
		$this->assertEqual($pager->lastPage(), 2);
		$this->assertEqual($pager->firstItem(), 6);
		$this->assertEqual($pager->lastItem(), 8);
		$this->assertEqual($pager->getNumItems(), $datasource->getNumItems());
	}
	
}
