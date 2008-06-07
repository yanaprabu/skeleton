<?php
/**
 * APL Cart Pricing Class library
 *
 * Copyright (C) 2004 Christopher Thompson. All Rights Reserved.
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 * See the GNU Lesser General Public License for more details.
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 **/


class A_Cart_Pricing
{protected $currency_symbol = '$';protected $currency_format = '%01.2f';			// sprintf format


/**
 *  create pricing array in the format: array[min_quantity]=price
 **/
public function getPricingArray($pricing, $price)
{
	$p = explode ('|', $pricing);
	foreach ($p as $prange) {
		list($key, $val) = explode ('=', $prange);
		$parray[$key] = $val;
#echo "page_text_price: prange=$prange, key=$key, val=$val<br>";
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
 **/
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




# END class A_Cart_Common
}
