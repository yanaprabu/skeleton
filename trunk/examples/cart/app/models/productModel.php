<?php

class productModel
{
	protected $data = array();
	
	public function __construct($locator=null)
	{
		$this->sizes = array('x-small', 'small', 'medium', 'large', 'x-large');
		$this->colors = array('red', 'orange', 'yellow', 'green', 'blue');
		for ($i=0; $i<=4; ++$i) {
			$id = $i + 1;
			$this->data[$id] = array(
				'id' => $id,
				'sku' => "A$id",
				'category' => 'One',
				'name' => "Item $id",
				'price' => 10.0 + $i,
				'color' => $this->colors[$i],
				'size' => '',
			);

			$id = $i + 6;
			$this->data[$id] = array(
				'id' => $id,
				'sku' => "B$id",
				'category' => 'Two',
				'name' => "Item $id",
				'price' => 10.0 * $id,
				'color' => '',
				'size' => $this->sizes[$i],
			);
		}
	}

	public function findCategories()
	{
		$data = array();
		foreach ($this->data as $row) {
			$data[$row['category']] = 1;
		}
		return array_keys($data);
	}
	
	public function findAllProducts()
	{
		return $this->data;
	}

	public function findProductsSkus($skus=array())
	{
		$data = array();
		if ($skus) {
			foreach ($this->data as $id => $row) {
				foreach ($skus as $sku) {
					if ($row['sku'] == $sku) {
						$data[$id] = $row;
						break;
					}
				}
			}
		}
		return $data;
	}
	
	public function findProductsInCategory($category)
	{
		$data = array();
		if ($category) {
			foreach ($this->data as $id => $row) {
				if ($row['category'] == $category) {
					$data[$id] = $row;
				}
			}
		}
		return $data;
	}
}