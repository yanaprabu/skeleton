<?php
/**
 * Xslt.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Template_Xslt
 *
 * Template class that transforms XML templates with XSL. No blocks.
 *
 * @package A_Template
 */
class A_Template_Xslt extends A_Template_Base implements A_Renderer
{

	protected $filenamexml = '';

	public function __construct($filenamexsl='', $data=array())
	{
	    parent::__construct($filenamexsl, $data);
	}

	public function setXML($xml)
	{
	    $this->template = $xml;
		return $this;
	}

	public function setXMLFilename($filename)
	{
	    $this->filenamexml = $filename;
		return $this;
	}

	public function render()
	{
		if ($this->filename && ($this->template || $this->filenamexml)) {
			$xml = new DOMDocument();
			if ($this->template) {
				$xml->loadXML($this->template);
			} elseif ($this->filenamexml) {
				$xml->load($this->filenamexml);
			}

			$xsl = new DOMDocument();
			$xsl->load($this->filename);

			$processor = new XSLTProcessor();
			$processor->importStyleSheet($xsl);
			return $processor->transformToXML($xml);
		}
	}

}
