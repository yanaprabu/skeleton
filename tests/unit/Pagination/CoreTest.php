<?php

class Pagination_Core_DatasourceMock implements A_Pagination_Adapter_Interface {
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

	public function setOrderBy($field, $descending=false) {
	}

}

class Pagination_CoreTest extends UnitTestCase {

	function setUp() {
	}

	function TearDown() {
	}

	function testPaginationgetFirstPage() {
 		$datasource = new Pagination_Core_DatasourceMock();
		$pager = new A_Pagination($datasource, 5);

		$result = true;
		$this->assertEqual($pager->getCurrentPage(), 1);
		$this->assertEqual($pager->getFirstPage(), 1);
		$this->assertEqual($pager->getLastPage(), 2);
		$this->assertEqual($pager->getFirstItem(), 1);
		$this->assertEqual($pager->getLastItem(), 5);
		$this->assertEqual($pager->getNumItems(), $datasource->getNumItems());
	}

	function testPaginationSecondPage() {
 		$datasource = new Pagination_Core_DatasourceMock();
		$pager = new A_Pagination($datasource, 5);
		$pager->setCurrentPage(2);

		$result = true;
		$this->assertEqual($pager->getCurrentPage(), 2);
		$this->assertEqual($pager->getFirstPage(), 1);
		$this->assertEqual($pager->getLastPage(), 2);
		$this->assertEqual($pager->getFirstItem(), 6);
		$this->assertEqual($pager->getLastItem(), 8);
		$this->assertEqual($pager->getNumItems(), $datasource->getNumItems());
	}

	public function testGetParamNameSetsKeyIfNotSet()	{
		$datasource = new Pagination_Core_DatasourceMock();
		$pager = new A_Pagination($datasource, 5);
		$this->assertEqual ($pager->getParamName ('abc123'), 'abc123');
	}

	public function testSetParamName()	{
		$datasource = new Pagination_Core_DatasourceMock();
		$pager = new A_Pagination($datasource, 5);
		$pager->setParamName ('abc123', 'page');
		$this->assertEqual ($pager->getParamName ('abc123'), 'page');
	}

}
