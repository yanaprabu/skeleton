<?php
/**
 * Shopping cart management functionality
 * 
 * @package A_Cart 
 */

class A_Cart_Manager {protected $name = '';protected $itemid = 0;protected $items = array();protected $maxitems = 99;protected $othercosts = null;protected $currency = '$';

public function __construct($name) {
	$this->name = $name;
}

public function getName() {
	return $this->name;
}

public function getItemByID($id) {
	$item = $this->items[$id];
	if ($item) {
		if ($item->getID() != $id) {
			$item->setID($id);
		}
		return $item;
	}
	return null;
}

public function numItems() {
	return count($this->items);
}

public function getItems() {
	return $this->items;
}

public function addItem($newItem) {
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

public function deleteItemByID($id) {
	if (isset($this->items[$id])) {
		unset($this->items[$id]);
		return true;
	}
	return false;
}

public function getTotal() {
    $total = 0;
    if ($this->items) {
	    foreach ($this->items as $item) {
        	$total += $item->getPrice();
	    }
    }
    return $total;
}

public function getDataTotal($name) {
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

public function setOtherCost($name, $cost) {
	$this->othercosts[$name] = $cost;
	return $this;
}

public function getOtherCost($name) {
	return $this->othercosts[$name];
}

public function getGrandTotal() {
    $total = $this->getTotal();
    if ($this->othercosts) {
	    foreach ($this->othercosts as $cost) {
        	$total += $cost;
	    }
    }
    return $total;
}

public function setCurrency($currency) {
	$this->currency = $currency;
	return $this;
}

public function moneyFormat($value) {
    return $this->currency . number_format($value, 2);
}

} // end class A_Cart_Manager

