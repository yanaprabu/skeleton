<?php
/**
 * Head.php
 *
 * @package  A_Html
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Html_Head
 * 
 * Generate <head> tag
 */
class A_Html_Head {
	
	protected $_config = array(
					'meta' => array(),
					'links' => array(),
					'style_links' => array(),
					'stylesheets' => array(),
					'styles' => array(),
					'script_links' => array(),
					'scripts' => array(),
	); 
	protected $_withTags = true;
	protected $_label = '';
	
	/*
	* optional configuration array
	*/
	public function __construct($config=array()) {
		if ($config) {
			$this->config($config);
		}
	}
	
	public function config($config) {
		$this->_config = array_merge($this->_config, $config);
		return $this;
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

	public function _addConfig($name, $data) {
		if ($name && $data) {
			if ($this->_label) {
				$data['label'] = $this->_label;
			}
			if ($this->_before === null) {
				$this->_config[$name][] = $data;
			} elseif ($this->_before == '') {
				$this->_config[$name] = array_merge(array($data), $this->_config[$name]);
			} else {
				$move = false;
				foreach ($this->_config[$name] as $key => $data) {
					if (isset($data['label']) && ($data['label'] == $this->_before)) {
						array_splice($this->_config[$name], $key, 0,array($data));
						break;
					}
				}
			}
		}
		$this->_label = '';
		$this->_before = null;
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
	 
	/**
	 * Convenience methods
	 */
	public function setCharset($charset) {
		return $this->addMetaHttpEquiv('Content-Type', "text/html; charset=$charset");
	}

	public function setLanguage($language) {
		return $this->addMetaHttpEquiv('Content-Language', $language);
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

	/*
	* name=string, value=string or renderer
	*/
	public function render($attr=array(), $content=null) {
		$html = '';
		$html .= $this->renderTitle();
		$html .= $this->renderBase();
		$html .= $this->renderMeta();
		$html .= $this->renderLinks();
		$html .= $this->renderStyleLinks();
		$html .= $this->renderStylesheets();
		$html .= $this->renderStyles();
		$html .= $this->renderScriptLinks();
		$html .= $this->renderScripts();
		return $this->_withTags ? "<head>\n$html</head>\n" : $html;
	}

	public function __toString() {
		$this->render();
	}
}
