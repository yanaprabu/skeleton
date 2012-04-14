<?php

class Http_ViewTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testHttp_ViewDefault() {
  		$locator = new A_Locator();
  		$view = new A_Http_View($locator);
		
  		$this->assertEqual('', $view->render());
		$this->assertEqual(array(), $view->getHeaders());
		$this->assertEqual(null, $view->getCookie('foo'));
		$this->assertEqual('', $view->getRedirect());
		$this->assertEqual('', $view->getContent());
		$this->assertEqual(null, $view->getTemplate());
		$this->assertEqual(null, $view->get('foo'));
		$this->assertEqual('', $view->getErrorMsg());
		$this->assertEqual(array(), $view->getErrorMsg(''));
	}
	
	function testHttp_ViewHeaders() {
  		$locator = new A_Locator();
  		$view = new A_Http_View($locator);
		
		// set a header
		$view->setHeader('Foo', 'Bar');
		$this->assertEqual(array(), array_diff_assoc(array('Foo'=>array(0=>'Bar')), $view->getHeaders()));
		// if no data it will not add the header
		$view->setHeader(array('Bar' => 'Baz'));
		$this->assertEqual(array(), array_diff_assoc(array('Foo'=>array(0=>'Bar')), $view->getHeaders()));
		// set another header
		$view->setHeader('Bar', 'Baz');
		$this->assertEqual(array(), array_diff_assoc(array('Foo'=>array(0=>'Bar'),'Bar'=>array(0=>'Baz')), $view->getHeaders()));
		// null value removes header
		$view->setHeader('Bar');
		$this->assertEqual(array(), array_diff_assoc(array('Foo'=>array(0=>'Bar')), $view->getHeaders()));
	}
	
	function testHttp_ViewCookie() {
  		$locator = new A_Locator();
  		$view = new A_Http_View($locator);
		
		$view->setCookie('Foo', 'Bar', 'Baz');
		$this->assertEqual(array(), array_diff_assoc(array('Foo', 'Bar', 'Baz'), $view->getCookie('Foo')));
	}
	
	function testHttp_ViewRedirect() {
  		$locator = new A_Locator();
  		$view = new A_Http_View($locator);
		
		$url = 'http://foobar.com';
		$view->setRedirect($url);
		$this->assertEqual($url, $view->getRedirect());
	}
	
	function testHttp_ViewContent() {
  		$locator = new A_Locator();
  		$view = new A_Http_View($locator);
		
		$str = '<p>Some HTML</p>';
		$view->setContent($str);
		$this->assertEqual($str, $view->getContent());
		$this->assertEqual($str, $view->render());
	}
	
	function testHttp_ViewTemplate() {
  		$locator = new A_Locator();
  		$view = new A_Http_View($locator);
		
		$file = 'foobar.php';
		$view->setTemplate($file);
		$view->setTemplatePath(dirname(__FILE__) . '/templates');
		$this->assertEqual($file, $view->getTemplate());

		$view->set('foo', 'Foo. ');
		$view->set('bar', 'Bar. ');
		$this->assertEqual('Foo. Bar. ', $view->render());
	}
	
	function testHttp_ViewRenderer() {
  		$locator = new A_Locator();
		$view = new A_Http_View($locator);
		$template = new A_Template_Include(dirname(__FILE__) . '/templates/foobar.php');
		
		$this->assertFalse($view->hasRenderer());
		$view->setRenderer($template);
		$this->assertTrue($view->hasRenderer());

		$view->set('foo', 'Foo. ');
		$view->set('bar', 'Bar. ');
		$this->assertEqual('Foo. Bar. ', $view->render());
	}
	
	function testHttp_ViewGetSetHas() {
  		$locator = new A_Locator();
  		$view = new A_Http_View($locator);
		
		$str = 'foobar';
		$view->set('foo', $str);
		$this->assertEqual($str, $view->get('foo'));
		$this->assertTrue($view->has('foo'));
		$this->assertFalse($view->has('bar'));
	}
	
	function testHttp_ViewEscape() {
  		$locator = new A_Locator();
  		$view = new A_Http_View($locator);
		
		$str = '<hr style="foo"> &';
		$str_escaped = '&lt;hr style=&quot;foo&quot;&gt; &amp;';
		$this->assertEqual($str_escaped, $view->escape($str));
	}
	
	function testHttp_ViewPartial() {
  		$locator = new A_Locator();
  		$view = new A_Http_View($locator);
		
		$file = 'foobar.php';
		$view->setTemplatePath(dirname(__FILE__) . '/templates');

		$str = '<hr style="foo"> &';
		$str_escaped = '&lt;hr style=&quot;foo&quot;&gt; &amp;';

		$view->set('foo', 'Foo. ');
		$view->set('bar', 'Bar. ');
		$this->assertEqual('Foo. Bar. ', $view->partial($file));

		$view->set('foo', 'X');
		$view->set('bar', 'Y');
		$this->assertEqual('Foo. Bar. ', $view->partial($file, array('foo'=>'Foo. ', 'bar'=>'Bar. ')));
		$this->assertEqual('X', $view->get('foo'));
		$this->assertEqual('Y', $view->get('bar'));

		$view->set('foo', '');
		$view->set('bar', '');
		$this->assertEqual($str_escaped.$str, $view->partial($file, array('foo'=>$str, 'bar'=>$str), array('foo')));
	}
	
	function testHttp_ViewPartialLoop() {
  		$locator = new A_Locator();
  		$view = new A_Http_View($locator);
		
		$file = 'foobar.php';
		$view->setTemplatePath(dirname(__FILE__) . '/templates');

		$data = array(
			array('foo'=>'One, ', 'bar'=>'Two. '),
			array('foo'=>'Three, ', 'bar'=>'Four. '),
			array('foo'=>'Five, ', 'bar'=>'Six. '),
			);
		$this->assertEqual('One, Two. Three, Four. Five, Six. ', $view->partialLoop($file, $data));

		$data = array('One, ', 'Two, ', 'Three. ');
		$view->set('bar', '');		// so so $foo in template is null
		$this->assertEqual('One, Two, Three. ', $view->partialLoop($file, 'foo', $data));
	}
	
	function testHttp_ViewSetPartial() {
  		$locator = new A_Locator();
  		$view = new A_Http_View($locator);
		
		$file = 'foobar.php';
		$view->setTemplate($file);
		$view->setTemplatePath(dirname(__FILE__) . '/templates');

		$view->setPartial('bar', $file, array('foo'=>'Bar. ', 'bar'=>'Baz. '));
		$view->set('foo', 'Foo. ');
		
		$this->assertEqual('Foo. Bar. Baz. ', $view->render());

		$data = array('One, ', 'Two, ', 'Three. ');
		$view->set('foo', '');
		$view->set('bar', '');
		$view->setPartialLoop('foo', $file, 'bar', $data);
		$this->assertEqual('One, Two, Three. ', $view->render());
	}
	
	function testHttp_ViewSetEscape() {
  		$locator = new A_Locator();
  		$view = new A_Http_View($locator);
		
		$str = '<hr style="foo"> &';
		$str_escaped = '&lt;hr style=&quot;foo&quot;&gt; &amp;';

		$file = 'foobar.php';
		$view->setTemplate($file);
		$view->setTemplatePath(dirname(__FILE__) . '/templates');

		$view->setEscape('foo', $str);
		$view->set('bar', '');
		$this->assertEqual($str_escaped, $view->render());

		$view->escapeField('foo');
		$view->set('foo', $str);
		$view->set('bar', '');
		$this->assertEqual($str_escaped, $view->render());

		$view->escapeField(array('foo', 'bar'));
		$view->set('foo', $str);
		$view->set('bar', $str);
		$this->assertEqual($str_escaped.$str_escaped, $view->render());
	}
	
}
