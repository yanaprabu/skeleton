<?php
if (! class_exists('A_Cart_Url')) include 'A/Cart/Url.php';
if (! class_exists('A_Cart_Item')) include 'A/Cart/Item.php';

class A_Cart_Request extends A_Cart_Url
{protected $newitems = array();


public function __construct($cart)
{
	$this->setManager($cart);
}

public function numNewItems ()
{
	return count($this->newitems);
}

public function getNewItems ()
{
	return $this->newitems;
}

public function addNewItems (){
	if ($this->newitems) {
		foreach ($this->newitems as $item) {
			$this->cart->addItem($item);
		}
	}
}


//
// URL PARAMETER PROCESSING
//

/*
 * process commands passed via POST/GET to this page via _REQUEST
 * returns the name of the cart to which items were added. If different than current cart
 *
command formats
del:				op=1
addItem:			op_sku=quantity
					op_sku_data,value;data,value=value

deleteItem:			op_pos=1
item_data_set:	op_pos_data=value
item_data_del:	op_pos_data=1
 */
public function processRequest ($allrequest=null) {
	$op = '';
	$id = '';
	$pos = 0;
	$data = '';
	$value = '';
	$del = array ();
	$savename = '';
	
	if ($allrequest) {
		$allrequest =& $allrequest->data;
	} else {
		$allrequest =& $_REQUEST;
	}
	$request = array();
	$n = 0;
	foreach ($allrequest as $param => $value) {
		$param = preg_replace ('/[^a-zA-Z0-9\_\-\.\,\;\:]/', '', $param);
		$paramarray = explode ($this->cmd_separator, $param);
		if (count($paramarray) == 3) {
			$request[$n]['op'] = $paramarray[0];
			$request[$n]['id'] = $paramarray[1];
			$request[$n]['data'] = $paramarray[2];
			$request[$n]['value'] = preg_replace ('/[^a-zA-Z0-9\_\-\.]/', '', $value);
			++$n;
		}
	}
	if($n == 0) {
		return;
	}
	// 1. Find submit param to see if a name is specified
	foreach ($request as $param) {
		if (($param['op'] == 'submit') && ($param['id'] == $this->cmd_cart) && $param['data']) {
#echo "FOUND: INIT: val=$param['value'], op=$param['op'], id=$param['id'], data=$param['data']<br>\n";
#			$savename = $this->name;
#			$name = $param['data'];
			$this->name = $param['data'];
			break;
		}
	}
	
	// 2. Loop through passed values and build arrays of adds, deletes and sets.
	foreach ($request as $param) {
#echo "param=$param, val=$value, op=$param['op'], id=$param['id'], data=$param['data']<br>\n";
		if ($param['op'] == $this->cmd_sku_add) {
			$add[$param['id']][$this->cmd_quantity] = $param['value'];
			$add[$param['id']]['data'] = $param['data'];
	
		} elseif ($param['op'] == $this->cmd_sku_data_set) {
			if (($param['data'] == $this->cmd_quantity) && ($param['value'] < 1)) {
				$add[$param['id']][$this->cmd_quantity] = $param['value'];
			} else {
				$add[$param['id']]['data'] = $param['value'];
			}
	
		} elseif ($param['op'] == $this->cmd_pos_data_set) {
			if (($param['data'] == $this->cmd_quantity) && ($param['value'] < 1)) {
				$del[$param['id']]['op'] = $this->cmd_pos_del;
			} else {
				$set[$param['id']][$param['data']] = $param['value'];
			}
	
		} elseif ($param['op'] == $this->cmd_pos_del) {
			if ($param['value']) {
				$del[$param['id']]['op'] = $param['op'];
			}
	
		} elseif ($param['op'] == $this->cmd_pos_data_del) {
			if ($param['value']) {
				$del[$param['id']]['op'] = $param['op'];
				$del[$param['id']]['data'] = $param['data'];
			}
	
		} elseif ($param['op'] == $this->cmd_del) {
			if ($param['value']) {
				$del[$param['id']]['op'] = $param['op'];
#				$this->del ();
				break;
			}
		}
	}
	
	
	// 3. Process ADDs. This will add records in the cartitem table. 
	if (isset($add) ) {
		unset($this->newitems);
		foreach ($add as $id => $field) {
#echo "<!--ADD id=$id, quantity={$field[$this->cmd_quantity]}, data={$field['data']}-->\n";
			if ($field[$this->cmd_quantity] > 0) {
				if ($field['data']) {
					if (strpos($field['data'], $this->cmd_data_separator) === false) {
						$dataarray[0] = $field['data'];
					} else {
						$dataarray = explode($this->cmd_data_separator, $field['data']);
					}
					foreach ($dataarray as $d) {
						list($name, $value) = explode($this->cmd_data_equals, $d);
#echo "<!--ADD name=$name, value=$value-->\n";
						$newdata[] = new A_Cart_Itemdata($name, $value);
					}
				} else {
					$newdata = null;
				}					
				$this->newitems[] = new A_Cart_Item($id, $field[$this->cmd_quantity], $newdata);
			}
		}
	}
	
	
	// 4. Process DELETEs. This will delete records in the cartitem table. 
	if (isset($del) ) {
		foreach ($del as $id => $field) {
	
			if (isset($field['op']) ) {
#echo "<!--DELETE id=$id, op={$field['op']}, data={$field['data']}-->\n";
				if ($field['op'] == $this->cmd_pos_del) {
					$this->cart->deleteItemByID($id);
					// remove any set commands for deleted item
					if (isset($set[$id]) ) {
						unset ($set[$id] );
					}
#				} elseif ($field['op'] == $this->cmd_pos_data_del) {
#					$this->clearItemData (intval($id), $field['data']);
				}
			}
		}
	}
	
	// 5. SET any values that have been changed
	if (isset($set) ) {
		foreach ($set as $id => $setcmd) {
#echo "SETCMD: id=$id, " . print_r($setcmd, true) . "<br>\n";
			$id = intval($id);
			if (isset($this->cart->items[$id])) {
				foreach ($setcmd as $data => $value) {
					if ($data == 'quantity') {
						$this->cart->items[$id]->setQuantity(intval($value));
					} else {
						$this->cart->items[$id]->setData($data, intval($value));
					}
#echo "SETCMD: id=$id, data=$data, value=$value<br>\n";
				}
			}
		}
	}
	
	/*
	echo '<pre>';
	echo var_dump($this->cart);
	echo '</pre>';
	echo "op=$param['op'], id=$id, data=$data, value=$value<br>\n";
	*/
	
	return 0;
}


}
