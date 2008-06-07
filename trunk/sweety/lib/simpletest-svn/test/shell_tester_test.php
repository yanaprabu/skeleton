<?php
// $Id: shell_tester_test.php 1782 2008-04-25 17:09:06Z pp11 $
require_once(dirname(__FILE__) . '/../autorun.php');
require_once(dirname(__FILE__) . '/../shell_tester.php');
Mock::generate('SimpleShell');

class TestOfShellTestCase extends ShellTestCase {
    var $_mock_shell = false;
    
    function getShell() {
        return $this->_mock_shell;
    }
    
    function testGenericEquality() {
        $this->assertEqual('a', 'a');
        $this->assertNotEqual('a', 'A');
    }
    
    function testExitCode() {
        $this->_mock_shell = new MockSimpleShell();
        $this->_mock_shell->setReturnValue('execute', 0);
        $this->_mock_shell->expectOnce('execute', array('ls'));
        $this->assertTrue($this->execute('ls'));
        $this->assertExitCode(0);
    }
    
    function testOutput() {
        $this->_mock_shell = new MockSimpleShell();
        $this->_mock_shell->setReturnValue('execute', 0);
        $this->_mock_shell->setReturnValue('getOutput', "Line 1\nLine 2\n");
        $this->assertOutput("Line 1\nLine 2\n");
    }
    
    function testOutputPatterns() {
        $this->_mock_shell = new MockSimpleShell();
        $this->_mock_shell->setReturnValue('execute', 0);
        $this->_mock_shell->setReturnValue('getOutput', "Line 1\nLine 2\n");
        $this->assertOutputPattern('/line/i');
        $this->assertNoOutputPattern('/line 2/');
    }
}
?>