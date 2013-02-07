<?php
class A_Log_Backtrace
{
	protected $cli = false;
	protected $max_strlen = 20;

	function __construct($cli=false, $max_strlen=20)
	{
	}
	
	function write($log=array())
	{
        $backtrace = debug_backtrace();
        if ($cli) {
                $msg = '';
                $line_start='';
                $separator="\t";
                $line_end="\n";
        } else {
                $msg = "<table>\n";
                $line_start='<tr><td>';
                $separator="</td><td>";
                $line_end="</td></tr>\n";
        }
        $msg .= $line_start . 'n' . $separator . 'file' . $separator . 'line' . $separator . 'function'  . $line_end;
        foreach($backtrace as $index => $data) {
                $msg .= $line_start;
                $msg .= $index . $separator;
                $msg .= $data['file'] . $separator;
                $msg .= $data['line'] . $separator;
                $msg .= isset($data['class']) ? $data['class'].'->' : '';
                $msg .= "{$data['function']}(";
                if (count($data['args'])) {
                        $args = array();
                        foreach ($data['args'] as $arg) {
                                if (is_object($arg)) {
                                        $args[] = get_class($arg);
                                } elseif (is_array($arg)) {
                                        foreach ($arg as $key => $val) {
                                                $str = 'array(';
                                                $str .= is_string($key) ? "'$key'" : $key;
                                                $str .= "=>";
                                                $str .= is_string($val) ? "'" . (strlen($val) > $max_strlen ? substr($val, 0, $max_strlen-3) . '...' : $val) . "'" : $val;
                                                $str .= ")";
                                                $args[] = $str;
                                                break;
                                        }
                                } elseif (!isset($arg) || ($arg === null)) {
                                        $args[] = 'NULL';
                                } elseif (is_string($arg)) {
                                        if ($arg === '') {
                                                $args[] = "''";
                                        } else {
                                                $args[] = "'" . (strlen($arg) > $max_strlen ? substr($arg, 0, $max_strlen-3) . '...' : $arg) . "'";
                                        }
                                } else {
                                        $args[] = $arg;
                                }
                        }
                        $msg .= implode(', ', $args);
                }
                $msg .= ')' . $line_end;
        }
        if (!$cli) {
                $msg .= "</table>\n";
        }
        return $msg;
	}

}
	
 