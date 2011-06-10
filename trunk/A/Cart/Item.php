<?php
/**
 * Item.php
 *
 * @package  A_Cart
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Cart_Item
 *
 * Escapsulate a purchasable item that can be added to the shopping cart
 */
class A_Cart_Item
{	
	protected $id;	protected $productid;	protected $unitprice;	protected $quantity;	protected $quantityfixed;	protected $data;	protected $isunique;	protected $hastax;	protected $hasshipping;
	
	/**
	 * Constructor
	 * 
	 * @param mixed $product
	 * @param int $quantity
	 * @param mixed $data
	 * @param bool $isunique
	 * @param bool $hastax
	 * @param bool $hasshipping
	 */
	public function __construct($product, $quantity, $data=null, $isunique=false, $hastax=true, $hasshipping=true)
	{
		$this->productid = $product;
		$this->setQuantity($quantity);
		if ($data) {
			$this->addData($data);
		}
		$this->isunique = $isunique;
		$this->hastax = $hastax;
		$this->hasshipping = $hasshipping;
	}
	
	/**
	 * Get item ID
	 * 
	 * @return mixed
	 */
	public function getID()
	{
		return $this->id;
	}
	
	/**
	 * Set item ID
	 * 
	 * @param mixed $id
	 * @return $this
	 */
	public function setID($id)
	{
		$this->id = $id;
		return $this;
	}
	
	/**
	 * Get product ID
	 * 
	 * @return mixed
	 */
	public function getProductID()
	{
		return $this->productid;
	}
	
	/**
	 * Get item quantity
	 * 
	 * @return int
	 */
	public function getQuantity()
	{
		return $this->quantity;
	}
	
	/**
	 * Get price for a single item
	 * 
	 * @return int
	 */
	public function getUnitPrice()
	{
		return $this->unitprice;
	}
	
	/**
	 * Set price for a single item
	 * 
	 * @param int $price
	 * @return $this
	 */
	public function setUnitPrice($price)
	{
		$this->unitprice = $price;
		return $this;
	}
	
	/**
	 * Calculate sum total
	 * 
	 * @return int
	 */
	public function getPrice()
	{
		return $this->unitprice * $this->quantity;
	}
	
	/**
	 * @param mixed $name
	 * @return mixed
	 */
	public function getData($name)
	{
		return $this->data[$name];
	}
	
	/**
	 * @param mixed $name
	 * @param mixed $value
	 * @return $this
	 */
	public function setData($name, $value)
	{
		if ($name) {
			$this->data[$name] = $value;
		}
		return $this;
	}
	
	/**
	 * @param bool $isunique
	 * @return $this
	 */
	public function setUnique($isunique=true)
	{
		$this->isunique = $isunique;
		return $this;
	}
	
	/**
	 * @return bool
	 */
	public function isUnique()
	{
		return $this->isunique;
	}
	
	/**
	 * @param int $quantity
	 * @return $this
	 */
	public function setQuantity($quantity)
	{
		if ($this->quantityfixed) {
			$n = count($this->quantityfixed) - 1;
			for ($i = $n; $i >= 0; $i--) {
				if ($this->quantityfixed[$i] <= $quantity) {
					$quantity = $this->quantityfixed[$i];
					break;
				}
			}
		}
		$this->quantity = intval($quantity);
		return $this;
	}
	
	/**
	 * @param mixed $quantityfixed
	 * @return $this
	 */
	public function setQuantityFixed($quantityfixed)
	{
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
	
	/**
	 * @param A_Cart_Item $item
	 * @return bool
	 */
	public function sameAs($item)
	{
		return ($this->productid  == $item->getProductID()) && ($this->unitprice == $item->getUnitPrice());
	}
	
	/**
	 * @param A_Cart_Item $item
	 * @return $this
	 */
	public function add($item)
	{
		$this->setQuantity($this->quantity + $item->getQuantity());
		return $this;
	}
	
	/**
	 * @param mixed $data
	 * @return int number of datum added
	 */
	public function addData($data)
	{
		if (is_array($data)) {
			$addcount = 0;
			foreach ($data as $d) {
				$name = $d->getName();
				if ($name) {
					$this->data[$name] = $d->getValue();
					$addcount++;
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

}

class A_Cart_Itemdata
{
		protected $name = '';	protected $value = '';	protected $options = null;
	
	/**
	 * Constructor
	 * 
	 * @param string $name
	 * @param mixed $value
	 * @param mixed $options
	 */
	public function __construct($name, $value, $options=null)
	{
		$this->name = $name;
		$this->value = $value;
		$this->options = $options;
	}
	
	/**
	 * @return string
	 */
	public function getName()
	{
		return($this->name);
	}
	
	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return($this->value);
	}
	
	/**
	 * @return mixed
	 */
	public function getOptions()
	{
		return($this->options);
	}

}
