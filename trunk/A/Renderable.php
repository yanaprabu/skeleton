<?php
/**
 * Renderable.php
 *
 * @package  A
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Renderable
 *
 * Interface representing a renderable object.
 */
interface A_Renderable
{

	/**
	 * Render this object.  Call when program is ready to convert object to a string.  Usually calls the render() method of the A_Renderer kept internally.
	 * 
	 * @return string Rendered result
	 * @see A_Renderer
	 */
	public function render();
	
	/**
	 * Magic method so that object is automatically rendered when used in a string context.  Should call render().
	 * 
	 * @return string Rendered result
	 * @see render()
	 */
	public function __toString();
	
	/**
	 * Sets the internal A_Renderer object to use.
	 * 
	 * @param A_Renderer $renderer
	 * @return $this Should return self for fluency.
	 */
	public function setRenderer(A_Renderer $renderer);
	
	/**
	 * Indicate whether or not this Renderable has been given a Renderer.
	 * 
	 * @return bool True if has a renderer, false otherwise
	 */
	public function hasRenderer();

}
