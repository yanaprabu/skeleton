<?php
/*
 * by Chris Shiflett (http://shiflett.org/)
 */
function convert_smart_quotes($string) { 
    $search = array(chr(145), 
                    chr(146), 
                    chr(147), 
                    chr(148), 
                    chr(151)); 
    $replace = array("'", 
                     "'", 
                     '"', 
                     '"', 
                     '-'); 
    return str_replace($search, $replace, $string); 
}
