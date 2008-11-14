<?php
/**
 *  Optional include file for SimpleTest
 *  @package    SimpleTest
 *  @subpackage UnitTester
 *  @version    $Id: default_reporter.php 1779 2008-04-21 17:40:34Z edwardzyang $
 */

/**#@+
 *  include other SimpleTest class files
 */
require_once(dirname(__FILE__) . '/simpletest.php');
require_once(dirname(__FILE__) . '/scorer.php');
require_once(dirname(__FILE__) . '/reporter.php');
require_once(dirname(__FILE__) . '/xml.php');
/**#@-*/

/**
 *    Parser for command line arguments. Extracts
 *    the a specific test to run and engages XML
 *    reporting when necessary.
 *    @package SimpleTest
 *    @subpackage UnitTester
 */
class SimpleCommandLineParser {
    private $_to_property = array(
            'case' => '_case', 'c' => '_case',
            'test' => '_test', 't' => '_test',
    );
    private $_case = '';
    private $_test = '';
    private $_xml  = false;
    private $_help = false;
    private $_no_skips = false;
    
    /**
     *    Parses raw command line arguments into object properties.
     *    @param string $arguments        Raw commend line arguments.
     */
    function __construct($arguments) {
        if (! is_array($arguments)) {
            return;
        }
        foreach ($arguments as $i => $argument) {
            if (preg_match('/^--?(test|case|t|c)=(.+)$/', $argument, $matches)) {
                $property = $this->_to_property[$matches[1]];
                $this->$property = $matches[2];
            } elseif (preg_match('/^--?(test|case|t|c)$/', $argument, $matches)) {
                $property = $this->_to_property[$matches[1]];
                if (isset($arguments[$i + 1])) {
                    $this->$property = $arguments[$i + 1];
                }
            } elseif (preg_match('/^--?(xml|x)$/', $argument)) {
                $this->_xml = true;
            } elseif (preg_match('/^--?(no-skip|no-skips|s)$/', $argument)) {
                $this->_no_skips = true;
            } elseif (preg_match('/^--?(help|h)$/', $argument)) {
                $this->_help = true;
            }
        }
    }
    
    /**
     *    Run only this test.
     *    @return string        Test name to run.
     */
    function getTest() {
        return $this->_test;
    }
    
    /**
     *    Run only this test suite.
     *    @return string        Test class name to run.
     */
    function getTestCase() {
        return $this->_case;
    }
    
    /**
     *    Output should be XML or not.
     *    @return boolean        True if XML desired.
     */
    function isXml() {
        return $this->_xml;
    }
    
    /**
     *    Output should suppress skip messages.
     *    @return boolean        True for no skips.
     */
    function noSkips() {
        return $this->_no_skips;
    }
    
    /**
     *    Output should be a help message. Disabled during XML mode.
     *    @return boolean        True if help message desired.
     */
    function help() {
        return $this->_help && !$this->_xml;
    }
    
    /**
     *    Returns plain-text help message for command line runner.
     *    @return string         String help message
     */
    function getHelpText() {
        return <<<HELP
SimpleTest command line default reporter (autorun)
Usage: php <test_file> [args...]

    -c <class>      Run only the test-case <class>
    -t <method>     Run only the test method <method>
    -s              Suppress skip messages
    -x              Return test results in XML
    -h              Display this help message

HELP;
    }
    
}

/**
 *    The default reporter used by SimpleTest's autorun
 *    feature. The actual reporters used are dependency
 *    injected and can be overridden.
 *    @package SimpleTest
 *    @subpackage UnitTester
 */
class DefaultReporter extends SimpleReporterDecorator {
    
    /**
     *  Assembles the appopriate reporter for the environment.
     */
    function __construct() {
        if (SimpleReporter::inCli()) {
            $parser = new SimpleCommandLineParser($_SERVER['argv']);
            $interfaces = $parser->isXml() ? array('XmlReporter') : array('TextReporter');
            if ($parser->help()) {
                // I'm not sure if we should do the echo'ing here -- ezyang
                echo $parser->getHelpText();
                exit(1);
            }
            $reporter = new SelectiveReporter(
                    SimpleTest::preferred($interfaces),
                    $parser->getTestCase(),
                    $parser->getTest());
            if ($parser->noSkips()) {
                $reporter = new NoSkipsReporter($reporter);
            }
        } else {
            $reporter = new SelectiveReporter(
                    SimpleTest::preferred('HtmlReporter'),
                    @$_GET['c'],
                    @$_GET['t']);
            if (@$_GET['skips'] == 'no' || @$_GET['show-skips'] == 'no') {
                $reporter = new NoSkipsReporter($reporter);
            }
        }
        parent::__construct($reporter);
    }
}
?>