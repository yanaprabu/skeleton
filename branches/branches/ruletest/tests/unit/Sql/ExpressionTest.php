<?php

class Sql_ExpressionTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
    function testSimpleEqualsUsingTwoParameters() { 
        $expression = new A_Sql_Expression('foo', 'bar');
        $this->assertEqual($expression->render(), "foo = 'bar'");
    }
    
    function testSimpleDoesEqualsUsingTwoParametersWithOperatorEmbedded() { 
        $expression = new A_Sql_Expression('foo =', 'bar');
        $this->assertEqual($expression->render(), "foo = 'bar'");
    }   
    
    function testSingleParameterWithArrayOfSingleExpressions() {
        $expressions = array('foo' => 'bar');
        $expression = new A_Sql_Expression($expressions);
        $this->assertEqual($expression->render(), "foo = 'bar'");
    }
    
    function testSingleParameterWithArrayOfMultipleExpressions() {
        $expressions = array(
            'foo' => 'bar',
            'foo2' => 'bar',
            'foo3' => 'bar3'
        );
        
        $expression = new A_Sql_Expression($expressions);
        $this->assertEqual($expression->render(), "foo = 'bar' AND foo2 = 'bar' AND foo3 = 'bar3'");
    }   
    
    function testAlternateCommaSeperator() {
        $expressions = array(
            'foo' => 'bar',
            'foo2' => 'bar',
            'foo3' => 'bar3'
        );
        $expression = new A_Sql_Expression($expressions);
        $this->assertEqual($expression->render(','), "foo = 'bar', foo2 = 'bar', foo3 = 'bar3'");
    }
 
    function testAlternateLogicalSeperator() {
        $expressions = array(
            'foo' => 'bar',
            'foo2' => 'bar',
            'foo3' => 'bar3'
        );
        $expression = new A_Sql_Expression($expressions);
        $this->assertEqual($expression->render('OR'), "foo = 'bar' OR foo2 = 'bar' OR foo3 = 'bar3'");
    }
    
    function testBracketsMultipleValuesExpressions() {
        $expressions = array(
            'foo IN' => array('foo2', 'bar2'),
            'foo2 NOT IN' => array('foo3', 'bar3')
        );
        $expression = new A_Sql_Expression($expressions);
        $this->assertEqual($expression->render(), "foo IN ('foo2', 'bar2') AND foo2 NOT IN ('foo3', 'bar3')");
    }   
    
    function testValuesAreEscaped() { 
        $expression = new A_Sql_Expression('foobar', "foo'bar");
        $this->assertEqual($expression->render(), "foobar = 'foo\'bar'");
    }

}
