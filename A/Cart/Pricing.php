<?php
/**
 * Pricing.php
 *
 * @package  A_Cart
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Cart_Pricing
 *
 * Shopping Cart Pricing Class library
 */
class A_Cart_Pricing
{
		protected $currency_symbol = '$';	protected $currency_format = '%01.2f'; // sprintf format
	
	/**
	 *  create pricing array in the format: array[min_quantity]=price
	 *  
	 *  @param array $pricing
	 *  @param int $price
	 *  @return array
	 */
	public function getPricingArray($pricing, $price)
	{
		$p = explode ('|', $pricing);
		foreach ($p as $prange) {
			list($key, $val) = explode ('=', $prange);
			$parray[$key] = $val;
		}
		if (! $parray[1]) {
			$parray[1] = $price;
		}
		ksort ($parray, SORT_NUMERIC);
		return $parray;
	}

	/**
	 *  displays price
	 * adds currency symbol if given
	 * splits pricing string in the format: min_quantity=price|min_quantity=price ...
	 * example: 5=10.00|10=19.00| 15=28.00
	 * if not given, 1=$price is added
	 * 
	 * @param int $price
	 * @param array $pricing
	 * @param string $separator
	 * @param string $currency_symbol
	 * @param string $currency_format
	 * @return string
	 */
	public function getPrice($price, $pricing, $separator=', ', $currency_symbol='', $currency_format='')
	{
		if (! $currency_symbol) {
			// if not passed then get global
			$currency_symbol = $this->currency_symbol;
		}
		if (! $currency_format) {
			// if not passed then get global
			$currency_format = $this->currency_format;
		}
		if ($pricing) {
			$parray = $this->getPricingArray($pricing, $price);
			$str = '';
			foreach ($parray as $key => $val) {
				if ($str) {
					$str .= $separator;
				}
				$str .= "$key @ $currency_symbol" . sprintf ($currency_format, $val);
			}
			return $str;
		} else {
			return $currency_symbol . sprintf ($currency_format, $price);
		}
	}

}
