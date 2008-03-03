<?php
require_once('A/Cart/Creditcard/Validate.php');

class Cart_Creditcard_ValidateTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testCart_Creditcard_ValidateNotNull() {
/*
American Express	378282246310005
American Express	371449635398431
American Express Corporate	378734493671000
Australian BankCard	5610591081018250
Diners Club	30569309025904
Diners Club	38520000023237
Discover	6011111111111117
Discover	6011000990139424
JCB	3530111333300000
JCB	3566002020360505
MasterCard	5555555555554444
MasterCard	5105105105105100
Visa	4111111111111111
Visa	4012888888881881
*/
  		$number='';
  		$type='';
  		$Cart_Creditcard_Validate = new A_Cart_Creditcard_Validate($number, $type);
		
		$result = true;
  		$this->assertTrue($result);
		$this->assertFalse(!$result);
	}
	
}
