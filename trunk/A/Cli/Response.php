<?php
/**
 * Response.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Cli_Response
 *
 * CLI response. Encapsulates character encoding, quoting, escaping, and content.
 *
 * @package  A_Cli
 */
class A_Cli_Response extends A_Cli_View
{

    public function out()
    {
    	echo $this->render();
    }

}
