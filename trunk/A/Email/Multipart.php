<?php
/**
 * Multipart.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Email_Multipart
 *
 * Generate mime part for inclusion in multipart emails.
 *
 * @package A_Email
 */
class A_Email_Multipart
{

	protected $parts;
	protected $headers;
	protected $boundary;
	protected $type;
	protected $non_mime_message;

	public function __construct($type='', $non_mime_message='')
	{
		$this->type = $type ? $type : 'multipart/mixed';
		$this->non_mime_message = $non_mime_message ? $non_mime_message : 'This message is in MIME format.';

		$this->parts = array();
		$this->headers =  '';
		$this->boundary =  "A_" . strtoupper(md5(uniqid(time())));
	}

	/*
	 * encodings:
	 *     text/plain; charset="ISO-8859-1"
	 *     text/html; charset="ISO-8859-1"
	 *     image/jpeg; name="myname.jpg"					(encode base64)
	 *     application/octet-stream; name="myname.bin"		(encode base64)
	 *     application/zip; name="myname.zip"				(encode base64)
	 * encodings:
	 *     quoted-printable
	 *     base64
	 *     7bit
	 *     8bit
	 * id:
	 *     set to the name of the part to reference from another part e.g <img src="cid:myid"/>
	 */
	public function addPart($content, $type='text/plain', $encoding='quoted-printable', $id='')
	{
		$this->parts[] = array (
			'content' => $content,
			'type' => $type,
			'encoding' => $encoding,
			'id' => $id,
		);
	}

	public function encodeBase64($content)
	{
		return chunk_split(base64_encode($content));
	}

	/**
	 * @author bendi@interia.pl, steffen.weber@computerbase.de; from php.net
	 */
	public function encodeQuotedPrintable($content)
	{
		$content = preg_replace('/[^\x21-\x3C\x3E-\x7E\x09\x20]/e', 'sprintf("=%02X", ord("$0"));', $content);
		preg_match_all( '/.{1,73}([^=]{0,3})?/', $content, $match);
		return implode("=\r\n", $match[0]);
	}

	public function buildPart($part)
	{
		switch ($part['encoding']) {
			case '7bit':
			case '8bit':
				$content = $part['content'];
				break;
			case 'quoted-printable':
				$content = $this->encodeQuotedPrintable($part['content']);
				break;
			case 'base64':
				$content = $this->encodeBase64($part['content']);
				break;
			default:
				$content = "Unsupported content transfer encoding {$part['encoding']}";
				$part['encoding'] = '7bit';
		}
		$id = $part['id'] == '' ? '' : "Content-ID: {$part['id']}\r\n";

		return "Content-type: {$part["type"]}\r\nContent-transfer-encoding: {$part['encoding']}\r\n$id\r\n$content\r\n";
	}

	/*
	 * types:
	 * 	  multipart/mixed
	 * 	  multipart/digest
	 * 	  multipart/alternative
	 * 	  multipart/related
	 * 	  multipart/report
	 * 	  multipart/signed
	 * 	  multipart/encrypted
	 */
	public function getHeaders($type='multipart/mixed')
	{
		if ($type == '') {
			$type = $this->type;
		}
		$headers = "Mime-Version: 1.0\r\nContent-type: $type;\r\n\tboundary=\"{$this->boundary}\"\r\n\r\n{$this->non_mime_message}\r\n\r\n";

		return $headers;
	}

	public function getMessage()
	{
		$multipart = '';
		$n = count($this->parts);
		for ($i=0; $i<$n; ++$i) {
			$multipart .= "--{$this->boundary}\r\n";
	 		$multipart .=  $this->buildPart($this->parts[$i]);
		}
		return $multipart;
	}

}
