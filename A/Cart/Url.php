<?php
/**
 * Url.php
 *
 * @package  A_Cart
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Cart_Url
 *
 * Generate URLs to add items to the shopping cart.  Links created with this class can be placed in a page, and when clicked, will lead to a page to add that item to the cart.
 */
class A_Cart_Url
{
		protected $cart;
		protected $base_url;
		protected $cmd_separator = ';';	protected $cmd_data_separator = ',';	protected $cmd_data_equals = ':';
		protected $cmdcart = 'cart';	protected $cmd_del = 'del';
		protected $cmd_sku_add = 'skuadd';	protected $cmd_sku_data_set = 'skudataset';
		protected $cmd_pos_del = 'posdel';	protected $cmd_pos_data_set = 'posdataset';	protected $cmd_pos_data_del = 'posdatadel';
		protected $cmd_quantity = 'quantity';
	
	
	/**
	 * @param A_Cart_Manager $cart Manager is only needed for Cart URL methods that take $id parameter
	 * @return $this
	 */
	public function setManager($cart)
	{
		$this->cart = $cart;
		return $this;
	}
	
	/**
	 * @param string $base_url
	 * @return $this
	 */
	public function setBaseURL($base_url)
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
	
	/**
	 * 
	 * PAGE URL GENERATION
	 * command formats
	 * del:				op=1
	 * item_add:		op_sku=quantity
	 * 					op_sku_data=value
	 * item_del:		op_pos=1
	 * item_data_set:	op_pos_data=value
	 * item_data_del:	op_pos_data=1
	 * 
	 * @param unknown_type $cart
	 * @return string
	 */
	public function pageDeleteURL($cart='')
	{
		return "{$this->base_url}{$this->cmd_del}{$this->cmd_separator}{$this->cmdcart}{$this->cmd_separator}$cart=1&submit=1&";
	}
	
	/**
	 * @param string $value
	 * @param string $cart
	 * @return string
	 */
	public function pageFormSubmit($value='', $cart='')
	{
		return "<input type=\"submit\" name=\"submit{$this->cmd_separator}{$this->cmdcart}{$this->cmd_separator}$cart\" value=\"$value\">\n";
	}
	
	/**
	 * @param string $image
	 * @param string $value
	 * @param string $cart
	 * @return string
	 */
	public function pageFormSubmitImage($image, $value='', $cart='')
	{
		if ($image) {
			return "<input type=\"image\" name=\"submit{$this->cmd_separator}{$this->cmdcart}{$this->cmd_separator}$cart\" value=\"$value\" src=\"$image\" border=0>\n";
		}
		return '';
	}
	
	/**
	 *  URL and Form Generation Function for use on Product Pages. All pass sku/quantity.
	 *  
	 *  @param mixed $id
	 *  @param int $quantity
	 *  @return string
	 */
	public function pageAddItemURL($id, $quantity=1)
	{
		if($id && $quantity) {
			return "{$this->base_url}{$this->cmd_sku_add}{$this->cmd_separator}{$id}{$this->cmd_separator}=$quantity&submit=1&";
		}
		return 0;
	}
	
	/**
	 * @param mixed $id
	 * @param int $quantity
	 * @return string
	 */
	public function pageAddItemFormHidden($id, $quantity=1)
	{
		if ($id && $quantity >= 0) {
			return "<input type=\"hidden\" name=\"{$this->cmd_sku_add}{$this->cmd_separator}{$id}{$this->cmd_separator}\" value=\"$quantity\">\n";
		}
		return '';
	}
	
	/**
	 * @param string|int $id
	 * @param int $quantity
	 * @param string|int $size
	 * @return string
	 */
	public function pageAddItemFormText($id, $quantity=1, $size=2)
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
	 * 
	 * @param string $cmd used to build the formprotected name
	 * @param string $id used to build the formprotected name
	 * @param string $field used to build the formprotected name
	 * @param mixed $values contains select options in the format value1|value2|value3 or value1|value2|value3;text1|text2|text3
	 * @param string $curval the currently selected value
	 * @param string $combine used to concat the value with the text and is either '', 'before' or 'after'
	 * @return string
	 */
	public function formSelect($cmd, $id, $field, $values, $curval='', $combine='')
	{
		$str = '';
		if ($cmd && $id && $field && $values) {
			// if there are both a value list and a text label list seperate them
			if (is_string($values)) {
				if (strstr($values, ';') ) {
					list($options, $text) = explode(';', $values);
				} else {
					$text = '';
				}
				$options = explode('|', $options);
				if ($text) {
					$text = explode('|', $text);
				}
			} else {
				$options =& $values;
			}
			
			if ($options) {
				if (! $text) {
					$text =& $options;
				}
				$str = "<select name=\"{$cmd}{$this->cmd_separator}{$id}{$this->cmd_separator}{$field}\">\n";

				// create a select from | seperated data from field in products data table
				if ($combine) {
					switch ($combine) {
						case 'before':
							$i = 0;
							foreach ($options as $opt) {
								$text[$i] = "$opt {$text[$i]}";
								$i++;
							}
							break;
						case 'after':
							$i = 0;
							foreach ($options as $opt) {
								$text[$i] .= " $opt";
								$i++;
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
					$i++;
				}
				$str .= "</select>\n";
			} else {
				$str = $options;
			}
		}
		return $str;
	}
	
	/**
	 * @param string $id
	 * @param string $field
	 * @param string $values
	 * @param string $curval
	 * @param string $combine
	 * @return string
	 */
	public function pageFormSelect($id, $field, $values, $curval='', $combine='')
	{
		return $this->formSelect($this->cmd_sku_data_set, $id, $field, $values, $curval, $combine);
	}



	/**
	 * CART URL GENERATION
	 * 
	 * command formats
	 * del:				op=1
	 * addItem:			op_sku=quantity
	 * deleteItem:		op_pos=1
	 * item_data_set:	op_pos_data=value
	 * item_data_del:	op_pos_data=1
	 * 
	 *  URL and Form Generation Function for use on the Cart Page. All are position based.
	 *  
	 *  @param string $id
	 *  @return string
	 */
	public function cartDeleteItemURL($id)
	{
		if($id > 0) {
			return "{$this->base_url}{$this->cmd_pos_del}{$this->cmd_separator}{$id}{$this->cmd_separator}=1&submit=1&";
		}
		return 0;
	}
	
	/**
	 * @param string $id
	 * @param string $data
	 * @param string $value
	 * @return string
	 */
	public function cartSetItemDataURL($id, $data, $value)
	{
		if( ($id > 0) && $data && $value) {
			return "{$this->base_url}{$this->cmd_pos_data_set}{$this->cmd_separator}{$id}{$this->cmd_separator}$data=$value&submit=1&";
		}
		return 0;
	}
	
	/**
	 * @param string $id
	 * @param string $data
	 */
	public function cartClearItemDataURL($id, $data)
	{
		if(($id > 0) && $data) {
			return "{$this->base_url}{$this->cmd_pos_data_del}{$this->cmd_separator}{$id}{$this->cmd_separator}$data=1&submit=1&";
		}
		return 0;
	}
	
	/**
	 * @param string $id
	 * @param int|string $quantity
	 * @return string
	 */
	public function cartSetItemQuantityURL($id, $quantity)
	{
		if(($id > 0) && $quantity) {
			return $this->cartSetItemDataURL($id, $this->cmd_quantity, $quantity);
		}
		return 0;
	}
	
	/**
	 * @param string $id
	 * @param string $data
	 * @param string $value
	 * @return string
	 */
	public function cartSetFormHiddenField($id, $data, $value)
	{
		if (($id > 0)  && $data && $value) {
			return "<input type=\"hidden\" name=\"{$this->cmd_pos_set}{$this->cmd_separator}{$id}{$this->cmd_separator}$data\" value=\"$value\">\n";
		}
		return '';
	}
	
	/**
	 * @param string $id
	 * @param string $data
	 * @param string $value
	 * @param int $size
	 * @return string
	 */
	public function cartSetFormTextField($id, $data, $value, $size=2)
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
	 * @param string $id
	 * @param int $quantity
	 * @return string
	 */
	public function cartSetQuantityFormHiddenField($id, $quantity=-1)
	{
		if ($id > 0) {
			if ($quantity < 0) {
				$quantity = $this->cart[$id]->getQuantity();
			}
			return $this->cartSetFormHiddenField($id, $this->cmd_quantity, $quantity);
		}
		return '';
	}
	
	/**
	 * @param string $id
	 * @param int $quantity
	 * @param int $size
	 * @return string
	 */
	public function cartSetQuantityFormTextField($id, $quantity=-1, $size=2)
	{
		if ($id > 0) {
			if ($size < 1) {
				$size = 2;
			}
			if ($quantity < 0) {
				$item = $this->cart->getItemByID($id);
				$quantity = $item->getQuantity();
			}
			return $this->cartSetFormTextField($id, $this->cmd_quantity, $quantity, $size);
		}
		return '';
	}
	
	/**
	 * @param string $id
	 * @param string $field
	 * @return string
	 */
	public function cartSetFormSelect($id, $field)
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
	 * @param string $id
	 * @return string
	 */
	public function cartDeleteFromCheckbox($id)
	{
		if ($id > 0) {
			return "<input type=\"checkbox\" name=\"{$this->cmd_pos_del}{$this->cmd_separator}{$id}\" value=\"1\">\n";
		}
		return 0;
	}

}
