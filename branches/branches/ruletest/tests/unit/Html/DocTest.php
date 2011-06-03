<?php

class Html_DocTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHtml_DocEmpty() {
		$Html_Doc = new A_Html_Doc();
		
		$expect = "<html>\n<head>\n</head>\n<body>\n</body>\n</html>\n";
		$this->assertEqual($Html_Doc->render(), $expect);
	}
	
	function testHtml_DocType() {
		$Html_Doc = new A_Html_Doc();
		
		$expect = "<!DOCTYPE html>\n<html>\n<head>\n</head>\n<body>\n</body>\n</html>\n";
		$Html_Doc->setDoctype('HTML_5');
		$this->assertEqual($Html_Doc->render(), $expect);
	}
	
	function testHtml_DocTitle() {
		$Html_Doc = new A_Html_Doc();
		
		$str = 'Title';
		$expect = "<html>\n<head>\n<title>$str</title>\n</head>\n<body>\n</body>\n</html>\n";
		$Html_Doc->setTitle($str);
		$this->assertEqual($Html_Doc->render(), $expect);
		$this->assertEqual($Html_Doc->getTitle(), $str);
	}
	
	function testHtml_DocBase() {
		$Html_Doc = new A_Html_Doc();
		
		$str = 'http://www.foo.com/bar/';
		$expect = "<html>\n<head>\n<base href=\"$str\"/>\n</head>\n<body>\n</body>\n</html>\n";
		$Html_Doc->setBase($str);
		$this->assertEqual($Html_Doc->render(), $expect);
		$this->assertEqual($Html_Doc->getBase(), $str);
	}
	
	function testHtml_DocMetaHttpEquiv() {
		$Html_Doc = new A_Html_Doc();
		
		$expect = "<html>\n<head>\n<meta http-equiv=\"Cache-Control\" content=\"no-cache\"/>\n</head>\n<body>\n</body>\n</html>\n";
		$Html_Doc->addMetaHttpEquiv('Cache-Control', 'no-cache');
		$this->assertEqual($Html_Doc->render(), $expect);

		$expect = "<html>\n<head>\n<meta http-equiv=\"Cache-Control\" content=\"no-cache\"/>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF8\"/>\n</head>\n<body>\n</body>\n</html>\n";
		$Html_Doc->addMetaHttpEquiv('Content-Type', 'text/html; charset=UTF8');
		$this->assertEqual($Html_Doc->render(), $expect);

		$expect = "<html>\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF8\"/>\n</head>\n<body>\n</body>\n</html>\n";
		$Html_Doc->removeMetaHttpEquiv('Cache-Control');	// remove 
		$this->assertEqual($Html_Doc->render(), $expect);
	}

	function testHtml_DocMetaName() {
		$Html_Doc = new A_Html_Doc();
		
		$expect = "<html>\n<head>\n<meta name=\"copyright\" content=\"1776\"/>\n</head>\n<body>\n</body>\n</html>\n";
		$Html_Doc->addMetaName('copyright', '1776');
		$this->assertEqual($Html_Doc->render(), $expect);

		$expect = "<html>\n<head>\n<meta name=\"copyright\" content=\"1776\"/>\n<meta name=\"keywords\" content=\"foo, bar, baz\"/>\n</head>\n<body>\n</body>\n</html>\n";
		$Html_Doc->addMetaName('keywords', 'foo, bar, baz');
		$this->assertEqual($Html_Doc->render(), $expect);

		$expect = "<html>\n<head>\n<meta name=\"keywords\" content=\"foo, bar, baz\"/>\n</head>\n<body>\n</body>\n</html>\n";
		$Html_Doc->removeMetaName('copyright');	// remove 
		$this->assertEqual($Html_Doc->render(), $expect);
	}

	function testHtml_DocStyles() {
		$Html_Doc = new A_Html_Doc();
		
		$expect = "<html>\n<head>\n<style type=\"text/css\" media=\"all\"/>\np { color: black; }\nh1 { color: blue; }\n</style>\n</head>\n<body>\n</body>\n</html>\n";
		$Html_Doc->addStyle('p { color: black; }');
		$Html_Doc->addStyle('h1 { color: blue; }');
		$this->assertEqual($Html_Doc->render(), $expect);
	}
	
	function testHtml_DocStylesheets() {
		$Html_Doc = new A_Html_Doc();
		
		$expect = "<html>\n<head>\n<style type=\"text/css\" media=\"all\"/>\np { color: black; }\nh1 { color: blue; }\n</style>\n</head>\n<body>\n</body>\n</html>\n";
		$Html_Doc->addStylesheet("p { color: black; }\nh1 { color: blue; }");
		$this->assertEqual($Html_Doc->render(), $expect);
	}
	
	function testHtml_DocStyleLinks() {
		$Html_Doc = new A_Html_Doc();
		
		$expect = "<html>\n<head>\n<link rel=\"stylesheet\" type=\"text/css\" href=\"foo.css\" media=\"all\"/>\n</head>\n<body>\n</body>\n</html>\n";
		$Html_Doc->addStyleLink("foo.css");
		$this->assertEqual($Html_Doc->render(), $expect);
		
		$expect = "<html>\n<head>\n<link rel=\"stylesheet\" type=\"text/css\" href=\"foo.css\" media=\"all\"/>\n<link rel=\"stylesheet\" type=\"text/css\" href=\"bar.css\" media=\"all\"/>\n</head>\n<body>\n</body>\n</html>\n";
		$Html_Doc->addStyleLink("bar.css");
		$this->assertEqual($Html_Doc->render(), $expect);
	}
	
	function testHtml_DocScripts() {
		$Html_Doc = new A_Html_Doc();
		
		$expect = "<html>\n<head>\n<script type=\"text/javascript\">\nfoo = 'bar';\n</script>\n</head>\n<body>\n</body>\n</html>\n";
		$Html_Doc->addScript("foo = 'bar';");
		$this->assertEqual($Html_Doc->render(), $expect);
		
		$expect = "<html>\n<head>\n<script type=\"text/javascript\">\nfoo = 'bar';\n</script>\n<script type=\"text/javascript\">\nbar = foo('baz');\n</script>\n</head>\n<body>\n</body>\n</html>\n";
		$Html_Doc->addScript("bar = foo('baz');");
		$this->assertEqual($Html_Doc->render(), $expect);
	}
	
	function testHtml_DocScriptLinks() {
		$Html_Doc = new A_Html_Doc();
		
		$expect = "<html>\n<head>\n<script type=\"text/javascript\" src=\"foo.js\"></script>\n</head>\n<body>\n</body>\n</html>\n";
		$Html_Doc->addScriptLink("foo.js");
		$this->assertEqual($Html_Doc->render(), $expect);
		
		$expect = "<html>\n<head>\n<script type=\"text/javascript\" src=\"foo.js\"></script>\n<script type=\"text/javascript\" src=\"bar.js\"></script>\n</head>\n<body>\n</body>\n</html>\n";
		$Html_Doc->addScriptLink("bar.js");
		$this->assertEqual($Html_Doc->render(), $expect);
	}
	
	function testHtml_DocSLinks() {
		$Html_Doc = new A_Html_Doc();
		
		$expect = "<html>\n<head>\n<link rel=\"Alternate\" href=\"http://foo.com\" type=\"\" title=\"\" media=\"all\"/>\n</head>\n<body>\n</body>\n</html>\n";
		$Html_Doc->addLinkRel('Alternate', 'http://foo.com');
		$this->assertEqual($Html_Doc->render(), $expect);
		
		$expect = "<html>\n<head>\n<link rel=\"Alternate\" href=\"http://foo.com\" type=\"\" title=\"\" media=\"all\"/>\n<link rev=\"Copyright\" href=\"http://bar.com\" type=\"\" title=\"\" media=\"all\"/>\n</head>\n<body>\n</body>\n</html>\n";
		$Html_Doc->addLinkRev('Copyright', 'http://bar.com');
		$this->assertEqual($Html_Doc->render(), $expect);
		
		$expect = "<html>\n<head>\n<link rel=\"Alternate\" href=\"http://foo.com\" type=\"\" title=\"\" media=\"all\"/>\n<link rev=\"Copyright\" href=\"http://bar.com\" type=\"\" title=\"\" media=\"all\"/>\n<link rev=\"parent\" href=\"http://foobar.com\" type=\"\" title=\"\" media=\"all\"/>\n</head>\n<body>\n</body>\n</html>\n";
		$Html_Doc->addLinkRev('parent', 'http://foobar.com');
		$this->assertEqual($Html_Doc->render(), $expect);
		
		$expect = "<html>\n<head>\n<link rel=\"Alternate\" href=\"http://foo.com\" type=\"\" title=\"\" media=\"all\"/>\n<link rev=\"Copyright\" href=\"http://bar.com\" type=\"\" title=\"\" media=\"all\"/>\n</head>\n<body>\n</body>\n</html>\n";
		$Html_Doc->removeLinkRev('parent');
		$this->assertEqual($Html_Doc->render(), $expect);
	}
	
	function testHtml_DocBodyAttributes() {
		$Html_Doc = new A_Html_Doc();
		
		$expect = "<html>\n<head>\n</head>\n<body style=\"font-family:arial;\">\n</body>\n</html>\n";
		$Html_Doc->setBodyAttr('style', 'font-family:arial;');
		$this->assertEqual($Html_Doc->render(), $expect);
		
		$expect = "<html>\n<head>\n</head>\n<body style=\"font-family:arial;\" width=\"200px\">\n</body>\n</html>\n";
		$Html_Doc->setBodyAttr('width', '200px');
		$this->assertEqual($Html_Doc->render(), $expect);
		
		$expect = "<html>\n<head>\n</head>\n<body width=\"200px\">\n</body>\n</html>\n";
		$Html_Doc->removeBodyAttr('style');
		$this->assertEqual($Html_Doc->render(), $expect);
	}
	
}
