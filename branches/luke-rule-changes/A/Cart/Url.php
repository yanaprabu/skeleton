<?php
/**
 * Generate URLs to add items to the shopping cart
 * 
 * @package A_Cart 
 */

class A_Cart_Url
{protected $cart;
protected $base_url;
protected $cmd_separator = ';';protected $cmd_data_separator = ',';protected $cmd_data_equals = ':';
protected $cmdcart = 'cart';protected $cmd_del = 'del';
protected $cmd_sku_add = 'skuadd';protected $cmd_sku_data_set = 'skudataset';
protected $cmd_pos_del = 'posdel';protected $cmd_pos_data_set = 'posdataset';protected $cmd_pos_data_del = 'posdatadel';
protected $cmd_quantity = 'quantity';


/*
 * $cart manager is only needed for Cart URL methods that take $id parameter
 */
public function setManager ($cart)
{
	$this->cart = $cart;
	return $this;
}

public function setBaseURL ($base_url)
{
	if (strpos($base_url, '?') !== false) {
		if (substr($base_url, -1) != '&') {
			$base_url .= '&';
		}
	} else {
		$base_url .= '?';
	}
	$this->base_url = $base_url;
	return $this;
}

#
# PAGE URL GENERATION
#

/*
command formats
del:				op=1
item_add:			op_sku=quantity
					op_sku_data=value

item_del:		op_pos=1
item_data_set:	op_pos_data=value
item_data_del:	op_pos_data=1
*/

public function pageDeleteURL ($cart='')
{
	return "{$this->base_url}{$this->cmd_del}{$this->cmd_separator}{$this->cmdcart}{$this->cmd_separator}$cart=1&submit=1&";
}

public function pageFormSubmit ($value='', $cart='')
{
	return "<input type=\"submit\" name=\"submit{$this->cmd_separator}{$this->cmdcart}{$this->cmd_separator}$cart\" value=\"$value\">\n";
}

public function pageFormSubmitImage ($image, $value='', $cart='')
{
	if ($image) {
		return "<input type=\"image\" name=\"submit{$this->cmd_separator}{$this->cmdcart}{$this->cmd_separator}$cart\" value=\"$value\" src=\"$image\" border=0>\n";
	}
	return '';
}


/**
 *  URL and Form Generation Function for use on Product Pages. All pass sku/quantity. 
 **/

public function pageAddItemURL ($id, $quantity=1)
{
	if($id && $quantity){
		return "{$this->base_url}{$this->cmd_sku_add}{$this->cmd_separator}{$id}{$this->cmd_separator}=$quantity&submit=1&";
	}
	return 0;
}

public function pageAddItemFormHidden ($id, $quantity=1)
{
	if ($id && $quantity >= 0) {
		return "<input type=\"hidden\" name=\"{$this->cmd_sku_add}{$this->cmd_separator}{$id}{$this->cmd_separator}\" value=\"$quantity\">\n";
	}
	return '';
}

public function pageAddItemFormText ($id, $quantity=1, $size=2)
{
	if ($id && ($quantity >= 0)) {
		if ($size < 1) {
			$size = 2;
		}
		return "<input type=\"text\" name=\"{$this->cmd_sku_add}{$this->cmd_separator}{$id}{$this->cmd_separator}\" value=\"$quantity\" size=\"$size\">\n";
	}
	return '';
}

/**
 *  base select generator used by pageFormSelect()
 * also used by child class CART by cartFormSelect()
 * $cmd, $id, $field are used to build the formprotected name
 * $values contains select options in the format value1|value2|value3 or value1|value2|value3;text1|text2|text3
 * $curval is the currently selected value
 * $combine is used to concat the value with the text and is either '', 'before' or 'after'
 **/
public function formSelect ($cmd, $id, $field, $values, $curval='', $combine='')
{
	$str = '';
	if ($cmd && $id && $field && $values) {
# if there are both a value list and a text label list seperate them
		if (is_string($values)) {
			if (strstr($values, ';') ) {
				list($options, $text) = explode (';', $values);
			} else {
				$text = '';
			}
			$options = explode ('|', $options);
			if ($text) {
				$text = explode ('|', $text);
			}
		} else {
			$options =& $values;
		}
#echo "formSelect values=$values, options=$options, text=$text.<br>\n";

		if ($options) {
			if (! $text) {
				$text =& $options;
			}
			$str = "<select name=\"{$cmd}{$this->cmd_separator}{$id}{$this->cmd_separator}{$field}\">\n";
	
# create a select from | seperated data from field in products data table
			if ($combine) {
				switch ($combine) {
				case 'before':
					$i = 0;
					foreach ($options as $opt) {
						$text[$i] = "$opt {$text[$i]}";
						++$i;
					}
					break;
				case 'after':
					$i = 0;
					foreach ($options as $opt) {
						$text[$i] .= " $opt";
						++$i;
					}
					break;
				}
			}

			$i = 0;
			foreach ($options as $val) {
				$str .=  "<option value=\"$val\"";
				if ($val == $curval) {
					$str .=  " selected=\"1\"";
				}
				$str .=  ">{$text[$i]}</option>\n";
				++$i;
			}
			$str .= "</select>\n";
		} else {
			$str = $options;
		}
	}
	return $str;
}


public function pageFormSelect ($id, $field, $values, $curval='', $combine='')
{
	return $this->formSelect ($this->cmd_sku_data_set, $id, $field, $values, $curval, $combine);
}



//
// CART URL GENERATION
//

/*
command formats
del:				op=1
addItem:			op_sku=quantity
deleteItem:			op_pos=1
item_data_set:	op_pos_data=value
item_data_del:	op_pos_data=1
*/


/**
 *  URL and Form Generation Function for use on the Cart Page. All are position based. 
 **/

/**
 *  
 **/
public function cartDeleteItemURL ($id)
{
	if($id > 0){
		return "{$this->base_url}{$this->cmd_pos_del}{$this->cmd_separator}{$id}{$this->cmd_separator}=1&submit=1&";
	}
	return 0;
}

/**
 *  
 **/
public function cartSetItemDataURL ($id, $data, $value)
{
	if( ($id > 0) && $data && $value){
		return "{$this->base_url}{$this->cmd_pos_data_set}{$this->cmd_separator}{$id}{$this->cmd_separator}$data=$value&submit=1&";
	}
	return 0;
}

/**
 *  
 **/
public function cartClearItemDataURL ($id, $data)
{
	if(($id > 0) && $data){
		return "{$this->base_url}{$this->cmd_pos_data_del}{$this->cmd_separator}{$id}{$this->cmd_separator}$data=1&submit=1&";
	}
	return 0;
}

/**
 *  
 **/
public function cartSetItemQuantityURL ($id, $quantity)
{
	if(($id > 0) && $quantity){
		return $this->cartSetItemDataURL ($id, $this->cmd_quantity, $quantity);
	}
	return 0;
}

/**
 *  
 **/
public function cartSetFormHiddenField ($id, $data, $value)
{
	if (($id > 0)  && $data && $value) {
		return "<input type=\"hidden\" name=\"{$this->cmd_pos_set}{$this->cmd_separator}{$id}{$this->cmd_separator}$data\" value=\"$value\">\n";
	}
	return '';
}

/**
 *  
 **/
public function cartSetFormTextField ($id, $data, $value, $size=2)
{
	if (($id > 0)  && $data && $value) {
		if ($size < 1) {
			$size = 2;
		}
		return "<input type=\"text\" name=\"{$this->cmd_pos_data_set}{$this->cmd_separator}{$id}{$this->cmd_separator}$data\" value=\"$value\" size=\"$size\">\n";
	}
	return '';
}

/**
 *  
 **/
public function cartSetQuantityFormHiddenField ($id, $quantity=-1)
{
	if ($id > 0) {
		if ($quantity < 0) {
			$quantity = $this->cart[$id]->getQuantity();
		}
		return $this->cartSetFormHiddenField ($id, $this->cmd_quantity, $quantity);
	}
	return '';
}

/**
 *  
 **/
public function cartSetQuantityFormTextField ($id, $quantity=-1, $size=2)
{
	if ($id > 0) {
		if ($size < 1) {
			$size = 2;
		}
		if ($quantity < 0) {
			$item = $this->cart->getItemByID($id);
			$quantity = $item->getQuantity();
		}
		return $this->cartSetFormTextField ($id, $this->cmd_quantity, $quantity, $size);
	}
	return '';
}

/**
 *  
 **/
public function cartSetFormSelect ($id, $field)
{
	if ($id > 0) {
		$options = '';
		$value = '';
		$item = $this->cart->getItemByID($id);
		if ($item) {
			$data = $item->getData($field);
			if ($data) {
				$options = $data->getOptions();
				$value = $data->getValue();
			}
		}
		return $this->formSelect($this->cmd_pos_data_set, $id, $field, $options, $value);
	}
	return '';
}

/**
 *  
 **/
public function cartDeleteFromCheckbox ($id)
{
	if ($id > 0) {
		return "<input type=\"checkbox\" name=\"{$this->cmd_pos_del}{$this->cmd_separator}{$id}\" value=\"1\">\n";
	}
	return 0;
}

} // end class A_Cart_Url
