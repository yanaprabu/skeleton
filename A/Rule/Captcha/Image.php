<?php
/**
 * Image.php
 *
 * @package	A_Rule
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Rule_Captcha_Image
 * 
 * Generate an image using GD for captcha
 */
class A_Rule_Captcha_Image
{

	protected $captcha;
	protected $length;
	
	public function __construct($captcha)
	{
		$this->captcha = $captcha;
	}
	
	public function out()
	{
		header("Content-type: image/png");
		$width = 75;
		$height = 25;
		$num_lines = 10;
		$im = imagecreate($width, $height);
		if ($im) {
			$bg_color = imagecolorallocate($im, 255, 255, 255);
			imagefill($im, 0, 0, $bg_color);
			$text_color = imagecolorallocate($im, 0, 0, 0);
			for ($i = 0; $i < $num_lines; $i++) {
				imageline($im, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $text_color);
			}
			imagestring($im, 5, 12, 5, $this->captcha->getCode(), $text_color);
			imagepng($im);
			imagedestroy($im);
		} else {
			return '';
		}
	}

}
