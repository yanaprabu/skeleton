<?php
/**
 * Escapsulate a shopping cart item
 * 
 * @package A_Cart 
 */

class A_Cart_Item {protected $id;protected $productid;protected $unitprice;protected $quantity;protected $quantityfixed;protected $data;protected $isunique;protected $hastax;protected $hasshipping;

public function __construct($product, $quantity, $data=null, $isunique=false, $hastax=true, $hasshipping=true) {
    $this->productid = $product;
    $this->setQuantity($quantity);
    if ($data) {
    	$this->addData($data);
    }
    $this->isunique = $isunique;
    $this->hastax = $hastax;
    $this->hasshipping = $hasshipping;
}

public function getID() {
	return $this->id;
}

public function setID($id) {
	$this->id = $id;
}

public function getProductID() {
    return $this->productid;
}

public function getQuantity() {
    return $this->quantity;
}

public function getUnitPrice() {
    return $this->unitprice;
}

public function setUnitPrice($price) {
    $this->unitprice = $price;
	return $this;
}

public function getPrice() {
    return $this->unitprice * $this->quantity;
}

public function getData($name) {
    return $this->data[$name];
}

public function setData($name, $value) {
    if ($name) {
    	$this->data[$name] = $value;
    }
	return $this;
}

public function setUnique($isunique=true) {
    $this->isunique = $isunique;
	return $this;
}

public function isUnique() {
    return $this->isunique;
}

public function setQuantity($quantity) {
    if ($this->quantityfixed) {
    	$n = count($this->quantityfixed) - 1;
    	for ($i=$n; $i>=0; --$i) {
    		if ($this->quantityfixed[$i] <= $quantity) {
 			  	$quantity = $this->quantityfixed[$i];
    			break;
    		}
    	}
	}
   	$this->quantity = intval($quantity);
	return $this;
}

public function setQuantityFixed($quantityfixed) {
    if ($quantityfixed != '') {
	    if (is_array($quantityfixed)) {
	    	$this->quantityfixed = $quantityfixed;
	    } else {
	    	$this->quantityfixed[] = $quantityfixed;
		}
		$this->setQuantity($this->quantity);	// recalcualte the quantity
    }
	return $this;
}

public function sameAs($item) {
	return($this->productid  == $item->getProductID()) &&
		($this->unitprice == $item->getUnitPrice());
}

public function add($item) {
    $this->setQuantity($this->quantity + $item->getQuantity());
	return $this;
}

public function addData($data) {
	if (is_array($data)) {
	    $addcount = 0;
	    foreach ($data as $d) {
	    	$name = $d->getName();
		    if ($name) {
		    	$this->data[$name] = $d->getValue();
			    ++$addcount;
		    }
	    }
	} else {
    	$name = $data->getName();
	    if ($name) {
	    	$this->data[$name] = $data->getValue();
    		$addcount = 1;
	    }
	}
    return $addcount;
}

} // end class A_Cart_Item


class A_Cart_Itemdata {protected $name = '';protected $value = '';protected $options = null;

public function __construct($name, $value, $options=null) {
    $this->name = $name;
    $this->value = $value;
    $this->options = $options;
}

public function getName() {
    return($this->name);
}

public function getValue() {
    return($this->value);
}

public function getOptions() {
    return($this->options);
}

} // end class A_Cart_Itemdata
