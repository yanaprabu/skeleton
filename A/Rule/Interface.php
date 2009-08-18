<?php
/**
 * anything that implements this interface can be added to A_Rule_Set
 * 
 * @package A_Rule
 */
interface A_Rule_Interface {

    public function isValid($container);
    public function getErrorMsg();

}