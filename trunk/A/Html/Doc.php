<?php
/**
 * Generate HTML document
 *
 * @package A_Html
 */

class A_Html_Doc {
	const HTML_4_01_STRICT = 1;
	const HTML_4_01_TRANSITIONAL = 2;
	const HTML_4_01_FRAMESET = 3;
	const XHTML_1_0_STRICT = 4;
	const XHTML_1_0_TRANSITIONAL = 5;	
	const XHTML_1_0_FRAMESET = 6;
	const XHTML_1_1 = 7; 
	const HTML_5 = 8; 
	
	protected $_config = array(
					'doctype' => '',
					'title' => '',
					'base' => '',
					'meta' => array(),
					'links' => array(),
					'style_links' => array(),
					'stylesheets' => array(),
					'styles' => array(),
					'script_links' => array(),
					'scripts' => array(),
					'body_attrs' => array(),
	); 
	protected $_body = '';
	
	/*
	* name=string, value=string or renderer
	*/
	public function renderDoctype($doctype=null) {
		$doctypes = array(
			self::HTML_5 => '<!DOCTYPE html>',
			self::HTML_4_01_STRICT => "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\"\n\"http://www.w3.org/TR/html4/strict.dtd\">",
			self::HTML_4_01_TRANSITIONAL => "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"\n\"http://www.w3.org/TR/html4/loose.dtd\">",
			self::HTML_4_01_FRAMESET => "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Frameset//EN\"\n\"http://www.w3.org/TR/html4/frameset.dtd\">",
			self::XHTML_1_0_STRICT => "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"\n\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">",
			self::XHTML_1_0_TRANSITIONAL => "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"\n\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">",	
			self::XHTML_1_0_FRAMESET => "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Frameset//EN\"\n\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd\">",
			self::XHTML_1_1 => "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n\"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">",
			);
		if ($doctype === null) {
			$doctype = $this->_config['doctype'];
		}
		if (! isset($doctypes[$doctype])) {
			$doctype = self::HTML_4_01_TRANSITIONAL;		// should this be HTML_5?
		}
		return $doctypes[$doctype] . "\n";
	}
	
	public function set($name, $value) {
		if (isset($this->_config[$name])) {
			if (is_array($this->_config[$name])) {
				$this->_config[$name][] = $value;
			} else {
				$this->_config[$name] = $value;
			}
		} elseif ($name == 'content') {					// how do we make this compatable with A_Http_Response usage?
			$this->_body = $value;
		}
		return $this;
	}

	public function setTitle($title) {
		$this->_config['title'] = $title;
		return $this;
	}

	public function getTitle() {
		return $this->_config['title'];
	}

	public function setBase($url) {
		$this->_config['base'] = $url;
		return $this;
	}

	public function getBase() {
		return $this->_config['base'];
	}

	public function _addConfig($name, $data) {
		if ($name && $data) {
			$this->_config[$name][] = $data;
		}
		$this->beforeLabel = null;
		$this->afterLabel = null;
		return $this;
	}
	
	/**
	 * 
http-equiv:
content-type
content-style-type
expires
refresh
set-cookie

name:
author
description
keywords
generator
revised

scheme:
format/URI
	 */
	public function removeMeta($attr, $type) {
		if ($attr && $type) {
			foreach ($this->_config['meta'] as $key => $data) {
				if (($data['attr'] == $attr) && ($data['type'] == $type)) {
					unset($this->_config['meta'][$key]);
				}
			}
		}
		return $this;
	}
	
	public function addMetaHttpEquiv($type, $content, $scheme='') {
		if ($type && ($content != '')) {
			$this->_addConfig('meta', array('attr'=>'http-equiv', 'type'=>$type, 'content'=>$content, 'scheme'=>$scheme, 'lang'=>''));
		}
		return $this;
	}
	
	public function removeMetaHttpEquiv($type) {
		if ($type) {
			$this->removeMeta('http-equiv', $type);
		}
		return $this;
	}
	
	public function addMetaName($type, $content, $scheme='', $lang='') {
		if ($type && ($content != '')) {
			$this->_addConfig('meta', array('attr'=>'name', 'type'=>$type, 'content'=>$content, 'scheme'=>$scheme, 'lang'=>$lang));
		}
		return $this;
	}
	
	public function removeMetaName($type) {
		if ($type) {
			$this->removeMeta('name', $type);
		}
		return $this;
	}
	
	public function addLink($attr, $rel, $href, $type='', $media='all') {
		if ($attr && $rel && $href) {
			$this->_addConfig('links', array('attr'=>$attr, 'rel'=>$rel, 'href'=>$href, 'type'=>$type, 'media'=>$media));
		}
		return $this;
	}
	
	/**
	 * removeLink()
	 * Remove link elements from the head object
	 * @param string $attr
	 * @param string $rel The link element type defined upon creation
	 */
	public function removeLink($attr, $rel) {
		foreach ($this->_config['links'] as $key => $data) {
			if (($data['attr'] == $attr) && ($data['rel'] == $rel)) {
				unset($this->_config['links'][$key]);
			}
		}
		return $this;
	}
	
	/**
	 * @param $rel
	 * @param $href
	 * @param $type
	 * @param $media
	 * @return $this
	 */
	public function addLinkRel($rel, $href, $type='', $media='all') {
		if ($rel && $href) {
			$this->addLink('rel', $rel, $href, $type, $media);
		}
		return $this;
	}
	
	/**
	 * removeLinkRel()
	 * Remove link elements from the head object that have a rel attribute.
	 * This method provides specific data to removeLink()
	 * @param string $type The type of the link element defined upon creation.
	 */
	public function removeLinkRel($rel) {
		$this->removeLink('rel', $rel);
		return $this;
	}
	
	/**
	 * @param $rel
	 * @param $href
	 * @param $type
	 * @param $media
	 * @return $this
	 */
	public function addLinkRev($rel, $href, $type='', $media='all') {
		if ($rel && $href) {
			$this->addLink('rev', $rel, $href, $type, $media);
		}
		return $this;
	}
	
	/**
	 * removeLinkRev()
	 * Removes link elements from the head object that have a rev attribute.
	 * This method provides specific data to removeLink()
	 * @param string $type The type of the link element defined upon creation.
	 */
	public function removeLinkRev($rel) {
		$this->removeLink('rev', $rel);
		return $this;
	}
	
	public function addStyle($style, $media='all') {
		if ($style) {
			$this->_addConfig('styles', array('style'=>$style, 'media'=>$media));
		}
		return $this;
	}
	 
	public function addStylesheet($sheet, $media='all') {
		if ($sheet) {
			$this->_addConfig('stylesheets', array('sheet'=>$sheet, 'media'=>$media));
		}
		return $this;
	}
	 
	public function addStyleLink($url, $media='all') {
		if ($url) {
			$this->_addConfig('style_links', array('href'=>$url, 'media'=>$media));
		}
		return $this;
	}
	 
	public function addScript($script, $type='text/javascript') {
		if ($script) {
			$this->_addConfig('scripts', array('script'=>$script, 'type'=>$type));
		}
		return $this;
	}
	 
	public function addScriptLink($url, $type='text/javascript') {
		if ($url) {
			$this->_addConfig('script_links', array('src'=>$url, 'type'=>$type));
		}
		return $this;
	}
	 
	public function setBodyAttr($attr, $value) {
		$this->_config['body_attrs'][$attr] = $value;
		return $this;
	}
	
	/**
	 * removeBodyAttr()
	 * Remove an attribute assigned to the body element with setBodyAttr()
	 * @param mixed $attr The attribute to remove
	 */
	public function removeBodyAttr($attr) {
		if (isset($this->_config['body_attrs'][$attr])) {
			unset($this->_config['body_attrs'][$attr]);
		}
		return $this;
	}

	public function setBody($body) {
		return $this->_body = $body;
	}

	/**
	 * Compatability with Response/View
	 */
	public function setContent($body) {
		return $this->_body = $body;
	}

	public function setRenderer($body) {
		return $this->_body = $body;
	}

	public function ifIE($logic) {
		if ($logic) {
			$this->ifIElogic = $logic;
		}
		return $this;
	}
	
	public function before($label) {
		if ($label) {
			$this->beforeLabel= $label;
		}
		return $this;
	}
	
	public function after($label) {
		if ($label) {
			$this->afterLabel= $label;
		}
		return $this;
	}

	/**
	 * Rendering methods
	 */
	
	public function renderTitle() {
		return $this->_config['title'] ? "<title>{$this->_config['title']}</title>\n" : '';
	}

	public function renderBase() {
		return $this->_config['base'] ? "<base href=\"{$this->_config['base']}\"/>\n" : '';
	}

	public function renderMeta() {
		$str = '';
		if (is_array($this->_config['meta'])) {
			foreach ($this->_config['meta'] as $key => $data) {
				$scheme = $data['scheme'] ? " scheme=\"{$data['scheme']}\"" : '';
				$str .= "<meta {$data['attr']}=\"{$data['type']}\" content=\"{$data['content']}\"$scheme/>\n";
			}
		}
		return $str;
	}

	public function renderLinks() {
		$str = '';
		foreach ($this->_config['links'] as $link) {
			$str .= "<link {$link['attr']}=\"{$link['rel']}\" href=\"{$link['href']}\" type=\"{$link['type']}\" title=\"{$link['title']}\" media=\"{$link['media']}\"/>\n";
		}
		return $str;
	}

	public function renderStyleLinks() {
		$str = '';
		foreach ($this->_config['style_links'] as $style) {
			$str .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$style['href']}\" media=\"{$style['media']}\"/>\n";
		}
		return $str;
	}

	public function renderStylesheets() {
		$str = '';
		foreach ($this->_config['stylesheets'] as $data) {
			$media = $data['media'] ? " media=\"{$data['media']}\"" : '';
			$str .= "<style type=\"text/css\"$media/>\n{$data['sheet']}\n</style>\n";
		}
		return $str;
	}

	public function renderStyles() {
		// gather styles for each media type
		$stylemedia = array();
		foreach ($this->_config['styles'] as $style) {
			$stylemedia[$style['media']] .= "{$style['style']}\n";
		}
		// generate stylesheet for each media type
		$str = '';
		foreach ($stylemedia as $media => $styles) {
			$str .= "<style type=\"text/css\" media=\"{$media}\"/>\n$styles</style>\n";
		}
		return $str;
	}

	public function renderScriptLinks() {
		$str = '';
		foreach ($this->_config['script_links'] as $data) {
			$str .= "<script type=\"{$data['type']}\" src=\"{$data['src']}\"></script>\n";
		}
		return $str;
	}

	public function renderScripts() {
		$str = '';
		foreach ($this->_config['scripts'] as $data) {
			$str .= "<script type=\"{$data['type']}\">\n{$data['script']}\n</script>\n";
		}
		return $str;
	}

	public function renderBodyAttrs() {
		$str = '';
		foreach ($this->_config['body_attrs'] as $key => $value) {
			$str .= " $key=\"$value\"";
		}
		return $str;
	}

	public function renderBody() {
		if (is_object($this->_body) && method_exists($this->_body, 'render')) {
			return $this->_body->render();
		} else {
			return $this->_body;
		}
	}

	/*
	* name=string, value=string or renderer
	*/
	public function render($attr=array(), $content=null) {
		$html = $this->renderDoctype();
		$html .= "<html>\n<head>\n";
		$html .= $this->renderTitle();
		$html .= $this->renderBase();
		$html .= $this->renderMeta();
		$html .= $this->renderLinks();
		$html .= $this->renderStyleLinks();
		$html .= $this->renderStylesheets();
		$html .= $this->renderStyles();
		$html .= $this->renderScriptLinks();
		$html .= $this->renderScripts();
		$html .= "</head>\n<body";
		$html .= $this->renderBodyAttrs();
		$html .= ">\n";
		$html .= $this->renderBody();
		$html .= "</body>\n</html>\n";
		return $html;
	}

	public function __toString() {
		$this->render();
	}
}
