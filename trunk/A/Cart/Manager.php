<?php
/**
 * Manager.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Cart_Manager
 *
 * Shopping cart management functionality
 *
 * @package A_Cart
 */
class A_Cart_Manager
{

	protected $name = '';
	protected $itemid = 0;
	protected $items = array();
	protected $maxitems = 99;
	protected $othercosts = null;
	protected $currency = '$';
	protected $data = array();

	/**
	 * Constructor
	 *
	 * @param mixed $name
	 */
	public function __construct($name)
	{
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param mixed $id
	 * @return A_Cart_Item|null
	 */
	public function getItemByID($id)
	{
		$item = $this->items[$id];
		if ($item) {
			if ($item->getID() != $id) {
				$item->setID($id);
			}
			return $item;
		}
		return null;
	}

	/**
	 * @return int
	 */
	public function numItems()
	{
		return count($this->items);
	}

	/**
	 * @return array
	 */
	public function getItems()
	{
		return $this->items;
	}

	/**
	 * @param bool $newItem True if item successfully added
	 */
	public function addItem($newItem)
	{
		if ($newItem) {
			if ($newItem->getQuantity() > 0) {
				if ($newItem->isUnique()) {
					foreach ($this->items as $item) {
						if ($item->sameAs($newItem)) {
							$item->add($newItem);
							return true;
						}
					}
				}
				if (count($this->items) < $this->maxitems) {
					++$this->itemid;
					$newItem->setID($this->itemid);
					$this->items[$this->itemid] = $newItem;
					return true;
				}
			}
		}
		return false;
	}

	/**
	 *
	 * @param mixed $id
	 * @return bool True if item existed and was removed
	 */
	public function deleteItemByID($id)
	{
		if (isset($this->items[$id])) {
			unset($this->items[$id]);
			return true;
		}
		return false;
	}

	/**
	 * @return int
	 */
	public function getTotal()
	{
		$total = 0;
		if ($this->items) {
			foreach ($this->items as $item) {
				$total += $item->getPrice();
			}
		}
		return $total;
	}

	/**
	 * @param mixed $name
	 * @return int
	 */
	public function getDataTotal($name)
	{
		$total = 0;
		if ($this->items) {
			foreach ($this->items as $item) {
				$n = $item->getData($name);
				if ($n && is_numeric($n)) {
					$total += $item->getQuantity() * $n;
				}
			}
		}
		return $total;
	}

	/**
	 * @param mixed $name
	 * @param int $cost
	 * @return $this
	 */
	public function setOtherCost($name, $cost)
	{
		$this->othercosts[$name] = $cost;
		return $this;
	}

	/**
	 * @param mixed $name
	 * @return mixed
	 */
	public function getOtherCost($name)
	{
		return $this->othercosts[$name];
	}

	/**
	 * @return int
	 */
	public function getGrandTotal()
	{
		$total = $this->getTotal();
		if ($this->othercosts) {
			foreach ($this->othercosts as $cost) {
				$total += $cost;
			}
		}
		return $total;
	}

	/**
	 * @param string $currency
	 */
	public function setCurrency($currency)
	{
		$this->currency = $currency;
		return $this;
	}

	/**
	 * @param int $value
	 * @return string
	 */
	public function moneyFormat($value)
	{
		return $this->currency . number_format($value, 2);
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return mixed
	 */
	function setData($name, $value)
	{
		if ($name) {
			$this->data[$name] = $value;
		}
		return $this;
	}

	/**
	 * @param string $name
	 * @return mixed
	 */
	function getData($name)
	{
		return isset($this->data[$name]) ? $this->data[$name] : '';
	}

}
