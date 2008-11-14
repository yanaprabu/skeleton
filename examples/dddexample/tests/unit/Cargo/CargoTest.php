<?php

echo "test";

include ('Domain/Model/Cargo/Cargo.php');

class CargoTest extends UnitTestCase	{

function __construct() {
	$this->UnitTestCase();
}

function testTest()	{
	$this->cargo = new Domain_Model_Cargo_Cargo();
}

}