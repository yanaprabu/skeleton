<?php
/*

UPS Shipping Types:

     GND => Ground
     3DS => 3 Day Select
     2DA => 2nd Day Air
     2DM => 2nd Day Air Early AM
     1DP => Next Day Air Saver
     1DA => Next Day Air
     1DM => Next Day Air Early AM
     STD => Canada Standard
     XPR => Worldwide Express
     XPD => Worldwide Expedited
     XDM => Worldwide Express Plus
      
*/

class A_Cart_Shipping_UPS
{protected $http_post = false;		// true=POST, false=GETprotected $shipping_type;protected $postal_from;protected $postal_to;protected $country_to;protected $weight;protected $value = 0;protected $buffer = '';

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
    /*********************
        send to ups program
        Send Vars
            $OriginPostalCode
            $DestZipCode
            $PackageWeight
            $upsProduct
            
        OUT VARS
            $result[0]
            
        UPS PRODUCT CODE (this should be in a drop down menu)
             Next Day Air Early AM         1DM
             Next Day Air                  1DA
            Next Day Air Saver             1DP
            2nd Day Air AM                 2DM
            2nd Day Air                     2DA
            3 Day Select                 3DS
            Ground                         GND
            Canada Standard                 STD
             Worldwide Express             XPR
            Worldwide Express Plus         XDM
            Worldwide Expedited             XPD

        UPS RATE CHART
            Regular+Daily+Pickup
            On+Call+Air
            One+Time+Pickup
            Letter+Center
            Customer+Counter

        Container Chart
            Customers Packaging            00
            UPS Letter Envelope            01
                or
            UPS Tube
            UPS Express Box                21
            UPS Worldwide 25kg Box        22
            UPS Worldwide 10 kg Box        23
            
        ResCom UPS Table
            Residential                    1
            Commercial                    2
            
    ***********************/
    
    $cost = 'Error';
    
    $upsAction = "3"; //3 Price a Single Product OR 4 Shop entire UPS product range
    $upsProduct = $this->shipping_type; //set UPS Product Code See Chart Above
    $OriginPostalCode = $this->postal_from; //zip code from where the client will ship from
    $DestZipCode = $this->postal_to; //set where product is to be sent
    $PackageWeight = $this->weight; //weight of product
    $OrigCountry = "US"; //country where client will ship from
    $DestCountry = $this->country_to; //set to country whaere product is to be sent
    $RateChart = "Regular+Daily+Pickup"; //set to how customer wants UPS to collect the product
    $Container = "00"; //Set to Client Shipping package type
    $ResCom = "2"; //See ResCom Table
    
    $port = 80;
    $domain = "www.ups.com";
    $request = "/using/services/rave/qcostcgi.cgi";
    $request .= "?";
    $request .= "accept_UPS_license_agreement=yes";
    $request .= "&";
    $request .= "10_action=$upsAction";
    $request .= "&";
    $request .= "13_product=$upsProduct";
    $request .= "&";
    $request .= "14_origCountry=$OrigCountry";
    $request .= "&";
    $request .= "15_origPostal=$OriginPostalCode";
    $request .= "&";
    $request .= "19_destPostal=$DestZipCode";
    $request .= "&";
    $request .= "22_destCountry=$DestCountry";
    $request .= "&";
    $request .= "23_weight=$PackageWeight";
    $request .= "&";
    $request .= "47_rateChart=$RateChart";
    $request .= "&";
    $request .= "48_container=$Container";
    $request .= "&";
    $request .= "49_residential=$ResCom";

    $fp = fsockopen($domain, 80, $error, $errmsg, 30);
    if(!$fp) {
        $cost = "$errmsg ($error)";
    } else {
        fputs($fp, "GET $request HTTP/1.0\n\n");
        while(!feof($fp)) {
            $buffer = fgets($fp, 1024);
            if (substr($buffer, 0, 9) == 'UPSOnLine') {
            	$result = explode("%", $buffer);
	            $errcode = substr("$result[0]", -1);
	            if (in_array ($errcode, array ('3', '4', '5', '6'))) {
	                $cost = trim ($result[8]);
	            }
	            break;
            }
        }
        fclose($fp);
    }
/*
echo "UPS Shipping:<br>";
echo "$request<br>";
echo '<pre>';
echo $buffer;
echo '</pre>';
*/
	return $cost;
}


public function getPriceQcostDSS ()
{
// return single shipping_type
$upsAction = '3';
// return all shipping_types
#$upsAction = '4';

// UPS rate chart to use in the calculation,

#$upsRateChart = 'Customer+Counter';
$upsRateChart = 'Regular+Daily+Pickup';

//  shipping destination is commercial=0, residential=1
$upsResidentialInd = '0';
#$upsResidentialInd = '1';

// Package Type: 00 - Shipper Supplied Package
$upsPackagingType = '00';
				
	$args  = 'AppVersion=1.2';
	$args .= '&AcceptUPSLicenseAgreement=yes';
	$args .= '&ResponseType=application/x-ups-rss';
	$args .= '&ActionCode=' . $upsAction;
	$args .= '&ServiceLevelCode=' . $this->shipping_type;
	$args .= '&RateChart=' . $upsRateChart;
	$args .= '&ShipperPostalCode=' . substr($this->postal_from, 0, 5);
	$args .= '&ConsigneePostalCode=' . substr($this->postal_to, 0, 5);
	$args .= '&ConsigneeCountry=' . $this->country_to;
	$args .= '&PackageActualWeight=' . $this->weight;
	$args .= '&DeclaredValueInsurance=' . $this->value;
	$args .= '&DCISInd=0';
	$args .= '&SNDestinationInd1=0';
	$args .= '&SNDestinationInd2=0';
	$args .= '&ResidentialInd=' . $upsResidentialInd;
	$args .= '&PackagingType=' . $upsPackagingType;

	if ($this->http_post) {
		$method    = 'POST';
		$path    = '/using/services/rave/qcost_dss.cgi';
		$httpversion = "HTTP/1.0\nContent-type: application/x-www-form-urlencoded\nContent-length: " . strlen($args) . "\n\n";
		$request = "$method $path $httpversion$args";
	} else {
		$method    = 'GET';
		$path    = '/using/services/rave/qcost_dss.cgi';
		$httpversion = "HTTP/1.0\n\n";
		$request = "$method $path?$args $httpversion";
	}

	$socket = fsockopen('www.ups.com', 80);
	fputs($socket, $request);	
	$buffer = fread ($socket, 4096);
	fclose($socket);

echo "UPS Shipping:<br>";
echo "$request<br>";
echo '<pre>';
echo $buffer;
echo '</pre>';
/*
*/
	strtok($buffer, "\n\r");

	$i = 0;
	while ($line = strtok("\n\r")) {
		if (ereg('^UPSOnLine', $line) ) {
			$rates[$i] = explode ('%', $line);
			if ($rates[$i][5] == $this->shipping_type) {
				if ($rates[$i][3] == 0) {
					$price = $rates[$i][12];
				} else {
					$price = -1;
				}
			}
			++$i;
		}
	}
	
/*
echo '<pre>';
echo $rates;
echo '</pre>';
*/

	return($price);
}


}
