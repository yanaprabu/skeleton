<?php
/**
 * anything that implements this interface can be added to A_Rule_Set
 */
interface A_Rule_Interface {

    public function isValid($container);
    public function getErrorMsg();

}