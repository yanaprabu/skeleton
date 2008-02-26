<?php
  include_once 'A/DataContainer.php';
  include_once 'A/Rule/Alpha.php';
  include_once 'A/Rule/Date.php';
  include_once 'A/Rule/Email.php';
  include_once 'A/Rule/Length.php';
  include_once 'A/Rule/Match.php';
  include_once 'A/Rule/Notnull.php';
  include_once 'A/Rule/Numeric.php';
  include_once 'A/Rule/Range.php';
  include_once 'A/Rule/Regexp.php';

  class A_Rule_Test extends UnitTestCase
  {
    var $ds;

    function setUp()
    {
      $this->ds = new A_DataContainer();
    }

    function testAlphaChar()
    {
      $rule = new A_Rule_Alpha('f1', 'Field must consist of AlphaChars only');

      // Only AlphaChars
      $this->ds->set('f1', 'Loremipsumdolorsitametconsectetueradipiscingelit');
      $this->assertTrue($rule->isValid($this->ds), 'Loremipsumdolorsitametconsectetueradipiscingelit, should pass');

      // Mixed
      $this->ds->set('f1', 'Lorem1psumdolorsitametconsectetueradipiscingelit');
      $this->assertFalse($rule->isValid($this->ds), 'Lorem1psumdolorsitametconsectetueradipiscingelit, should fail');

      // Control char test
      $this->ds->set('f1', "\n");
      $this->assertFalse($rule->isValid($this->ds), 'Newline, should fail');
    }

    function testDate()
    {
      $rule = new A_Rule_Date('f1', 'Invalid date');

      // Valid date
      $this->ds->set('f1', '2004-02-29');
      $this->assertTrue($rule->isValid($this->ds), '2004-02-29, should pass (leap year)');

      // Invalid format
      $this->ds->set('f1', '03-02-02');
      $this->assertFalse($rule->isValid($this->ds), '03-02-02, should fail');

      // Valid format but invalid date
      $this->ds->set('f1', '2004-02-30');
      $this->assertFalse($rule->isValid($this->ds), '2004-02-30, should fail');
    }

    function testEmail()
    {
      $rule = new A_Rule_Email('f1', 'Invalid Email');

      // Valid Email
      $this->ds->set('f1', 'root@framework.com');
      $this->assertTrue($rule->isValid($this->ds), 'root@framework.com, shoud pass');

      // No @
      $this->ds->set('f1', 'rootatframework.com');
      $this->assertFalse($rule->isValid($this->ds), 'rootatframework.com, should fail');

      // Invalid user
      $this->ds->set('f1', 'roo(t)@framework.com');
      $this->assertFalse($rule->isValid($this->ds), 'roo(t)@framework.com, should fail');

      // Invalid domain
      $this->ds->set('f1', 'root@frame(work).com');
      $this->assertFalse($rule->isValid($this->ds), 'root@frame(work).com, should fail');

      // Mixed case
      $this->ds->set('f1', 'Root@FrameWork.com');
      $this->assertTrue($rule->isValid($this->ds), 'Root@FrameWork.com, shoud pass');

      // Special chars
      $this->ds->set('f1', 'tree.root@deep-woods.com');
      $this->assertTrue($rule->isValid($this->ds), 'tree.root@deep_woods.com, should pass');
    }

    function testStringLength()
    {
      /* Range */
      $rule = new A_Rule_Length('f1', 6, 12, 'Is not in range');

      // In range
      $this->ds->set('f1', 'abc123xyz');
      $this->assertTrue($rule->isValid($this->ds), '(Range) abc123xyz, should pass');

      // Above max
      $this->ds->set('f1', 'abc123xyz456ijk');
      $this->assertFalse($rule->isValid($this->ds), '(Range) abc123xyz456ijk, should fail');

      // Below min
      $this->ds->set('f1', 'abc');
      $this->assertFalse($rule->isValid($this->ds), '(Range) abc, should fail');

      /* Minimum only */
      $rule = new A_Rule_Length('f1', 6, NULL, 'Minimum 6');

      // Above
      $this->ds->set('f1', 'abc123');
      $this->assertTrue($rule->isValid($this->ds), '(Min) abc123, should pass');

      // Below
      $this->ds->set('f1', 'xyz');
      $this->assertFalse($rule->isValid($this->ds), '(Min) xyz, should fail');

      /* Maximum only */
      $rule = new A_Rule_Length('f1', NULL, 12, 'Maximum 12');

      // Above
      $this->ds->set('f1', 'abc123xyz456ijk');
      $this->assertFalse($rule->isValid($this->ds), '(Max) abc123xyz456ijk, should fail');

      // Below
      $this->ds->set('f1', 'abc123');
      $this->assertTrue($rule->isValid($this->ds), '(Max) abc123, should pass');
    }

    function testMatch()
    {
      $rule = new A_Rule_Match('f1', 'f2', 'Does not match');

      $this->ds->set('f1', 'abc123');
      $this->ds->set('f2', 'ABC123');

      $this->assertFalse($rule->isValid($this->ds), 'abc123 and ABC123, should fail');

      $this->ds->set('f2', 'abc123');

      $this->assertTrue($rule->isValid($this->ds), 'abc123 and abc123, should pass');
    }

    function testNumericRange()
    {
      /* Range */
      $rule = new A_Rule_Range('f1', 6, 12, 'Is not in range');

      // In range
      $this->ds->set('f1', 9);
      $this->assertTrue($rule->isValid($this->ds), 'In range, should pass');

      // Above max
      $this->ds->set('f1', 13);
      $this->assertFalse($rule->isValid($this->ds), '(Range) Above max, should fail');

      // Below min
      $this->ds->set('f1', 4);
      $this->assertFalse($rule->isValid($this->ds), '(Range) Below min, should fail');

      /* Minimum only */
      $rule = new A_Rule_Range('f1', 6, NULL, 'Minimum 6');

      // Above
      $this->ds->set('f1', 7);
      $this->assertTrue($rule->isValid($this->ds), '(Min) Above minimum, should pass');

      // Below
      $this->ds->set('f1', 5);
      $this->assertFalse($rule->isValid($this->ds), '(Min) Below min, should fail');

      /* Maximum only */
      $rule = new A_Rule_Range('f1', NULL, 12, 'Maximum 12');

      // Above
      $this->ds->set('f1', 17);
      $this->assertFalse($rule->isValid($this->ds), '(Max) Above maximum, should fail');

      // Below
      $this->ds->set('f1', 9);
      $this->assertTrue($rule->isValid($this->ds), '(Max) Below maximum, should pass');
    }

    function testNotnull()
    {
      $rule = new A_Rule_Notnull('f1', 'Field f1 is null');

      // Valid
      $this->ds->set('f1', 'foo');
      $this->assertTrue($rule->isValid($this->ds), '"foo", should pass');

      // Integer test (0)
      $this->ds->set('f1', '0');
      $this->assertTrue($rule->isValid($this->ds), '0, should pass');

      // Empty string test
      $this->ds->set('f1', '');
      $this->assertFalse($rule->isValid($this->ds), 'String of length 0, should fail');

      // NULL test
      $this->ds->set('f1', NULL);
      $this->assertFalse($rule->isValid($this->ds), 'NULL, should fail');

      // Boolean value
      $this->ds->set('f1', TRUE);
      $this->assertTrue($rule->isValid($this->ds), 'TRUE, should pass');
    }

    function testNumeric()
    {
      $rule = new A_Rule_Numeric('f1', 'Is not numeric');

      // Int value
      $this->ds->set('f1', 123);
      $this->assertTrue($rule->isValid($this->ds), '123, should pass');

      // String value
      $this->ds->set('f1', '123');
      $this->assertTrue($rule->isValid($this->ds), '123 as string, should pass');

      // Fraction
      $this->ds->set('f1', 3.141592);
      $this->assertTrue($rule->isValid($this->ds), '3.141592, should pass');

      // Fraction as string
      $this->ds->set('f1', '3.141592');
      $this->assertTrue($rule->isValid($this->ds), '3.141592 as string, should pass');

      // Invalid format
      $this->ds->set('f1', 'abc123');
      $this->assertFalse($rule->isValid($this->ds), 'abc123, should fail');
    }

    function testRegexp()
    {
      /* 5 digits */
      $rule = new A_Rule_Regexp('f1', "/^\d{5}$/", 'Regexp does not match');

      // Valid format
      $this->ds->set('f1', '12345');
      $this->assertTrue($rule->isValid($this->ds), '12345, should pass');

      // Invalid format
      $this->ds->set('f1', '1b3d5');
      $this->assertFalse($rule->isValid($this->ds), '1b3d5, should fail');
    }

}

