<?php
/**
 * Fedex.php
 *
 * @package  A_Cart
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Cart_Shipping_Fedex
 * 
 * Get FedEx shipping information via web service API 
 */
class A_Cart_Shipping_Fedex
{protected $shipping_type;protected $postal_from;protected $postal_to;protected $country_to;protected $weight;

public function __construct($shipping_type='',  $postal_from='', $postal_to='',  $country_to='US',  $weight='')
{
	$this->shipping_type = $shipping_type;
	$this->postal_from = $postal_from;
	$this->postal_to = $postal_to;
	$this->country_to = $country_to;
	$this->weight = $weight;
}


public function getPrice ()
{

	// FPO - Fedex Priority Overnight - $price1
	// FSO - Fedex Standard Overnight - $price2
	// F2D - Fedex 2-Day Standard - $price3
	// FES - Fedex Express Saver - $price4
	// FFO - Fedex First Overnight - $price5
	// FGD - Fedex Ground - $gndprice

	if($this->shipping_type != "FGD") {
		$tmp = "jsp_name=index&orig_country=US&language=english&portal=plain&account=&heavy_weight=NO&packet_zip=&hold_packaging=&orig_zip={$this->postal_from}&dest_zip={$this->postal_to}&dest_country_val=U.S.A.&company_type=Express&packaging=1&weight={$this->weight}&weight_units=lbs&dim_units=in&dim_length=&dim_width=&dim_height=&dropoff_type=4&submit_button=Get+Rate\n\n\n\n";
		$request = "POST /servlet/RateFinderServlet HTTP/1.1\nAccept: image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, application/vnd.ms-excel, application/msword, */*\nReferer: http://rate.dmz.fedex.com/servlet/RateFinderServlet\nAccept-Language: en-us\nContent-Type: application/x-www-form-urlencoded\nAccept-Encoding: gzip, deflate\nUser-Agent: Mozilla/4.0 (compatible; MSIE 5.5; Windows 98)\nHost: rate.dmz.fedex.com\nContent-Length: ".strlen($tmp)."\nConnection: Keep-Alive\nCache-Control: no-cache\n\n" .$tmp;

		$socket = fsockopen("rate.dmz.fedex.com", 80);

		fputs($socket, $request);

		while ($out = fread ($socket, 2048))
			$iBuffer .= $out;

		$preprice = addcslashes("</TD><TD BGCOLOR=\"", "\^.$|()[]*+?{}") . ".*". addcslashes("\" ALIGN=right class='resultstable'>", "\^.$|()[]*+?{}");
		$postprice = addcslashes("</TD><TD>&nbsp;</TD><TD></TD><TD></TD></TR>", "\^.$|()[]*+?{}");
		$price_match = '([0-9]+\.[0-9]+)';

		$regexp = $preprice . $price_match . $postprice .".*".$preprice . $price_match . $postprice .".*". $preprice . $price_match . $postprice . ".*". $preprice . $price_match . $postprice  .".*".  $preprice . $price_match . $postprice;	
		ereg($regexp, $iBuffer, $regs);
		list(,$FPO_fedex, $FSO_fedex, $F2D_fedex, $FES_fedex,$FFO_fedex) = $regs;

		switch($this->shipping_type) {
			case 'FPO':
				return($FPO_fedex);
				break;
			case 'FSO':
				return($FSO_fedex);
				break;
			case 'F2D':
				return($F2D_fedex);
				break;
			case 'FES':
				return($FES_fedex);
				break;
			case 'FFO':
				return($FFO_fedex);
				break;
		}
	} else {
		$socket = fopen("http://grd.fedex.com/cgi-bin/rrr2010.exe?func=Rate&OriginZip={$this->postal_from}&OriginCountryCode=US&DestZip={$this->postal_to}&DestCountryCode={$this->country_to}&Weight={$this->weight}&WeightUnit=1&DimUnit=&Screen=Ground", "r");
				
		while ($out = fread ($socket, 2048))
			$iBuffer .= $out;
	
		$regexp  = addcslashes("<!TotalCharges>", "\^.$|()[]*+?{}")."([0-9]+\.[0-9]+)".addcslashes("<!/TotalCharges>", "\^.$|()[]*+?{}");
		ereg($regexp,$iBuffer,$regs);
		list($gndprice) = $regs;
		
		return($gndprice);
	}
}


}
