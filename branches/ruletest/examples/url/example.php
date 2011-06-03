<?php

$url = new URL(1);
$url->fromCurrent(1);
$url->set('id', 43);
$url->set('name', 'matt');
echo $url->getLink('test', array('style'=>'color:gold;'));

class URL {
    
    protected $params;
    protected $mode;
    protected $basename;
    protected $path_info_base;
    
    function URL($mode = 1, $path_info_base=''){
        $this->params = array();
        $this->mode = $mode;
        $this->path_info_base = $path_info_base;
    }
    
    function fromCurrent($include_params=true, $path_info_base=NULL){
        if( $path_info_base ){
            $this->path_info_base = $path_info_base;
        }
        switch($this->mode){
            case 1:
                // only get path after query string start
                $uriarray = explode('?', $_SERVER['REQUEST_URI']);
            	$path_info = reset($uriarray);
                // chop off the script name
                $path_info = str_replace($_SERVER['SCRIPT_NAME'], '', $path_info);
                // if the script name is in the url
                if( strpos($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']) !== false ){
                    // set the basename
                    $this->basename = str_replace($path_info, '/', $_SERVER['SCRIPT_NAME']);
                }else{
                    // else, set the basename to the directory of the script
                    $this->basename = dirname($_SERVER['SCRIPT_NAME']);
                    // now remove the basename (directory of script) from path_info
                    $path_info = str_replace($this->basename, '', $path_info);
                }
                // trim, and add slash
                $this->basename = '/' . trim($this->basename, '/');
                
                // trim off path_info_base
                if( $this->path_info_base ){
                    $path_info = str_replace($this->path_info_base, '', $path_info);
                }
                
                // trim, and add slash
                $path_info = trim($path_info, '/');
                
                // set params
                if($include_params){
                    $frags = explode('/', $path_info);
                    $i = 0;
                    while($frag = array_shift($frags)){
                        $this->params[$frag] = array_shift($frags);
                    }
                }
            break;
            default:
                // if the script is in the url
                if( strpos($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']) !== false ){
                    // set it!
                    $this->basename = '/' . trim($_SERVER['SCRIPT_NAME'], '/');
                }
                // set params
                if($include_params){
                    $this->params = $_GET;
                }
        }
    }
    
    function merge($params){
        $this->params = array_merge($this->params, $params);
    }
    
    function remove($name){
        if( isset($this->params[$name]) ){
            unset($this->params[$name]);
        }
    }
    
    function get($name){
        if(isset($this->params[$name])){
            return $this->params[$name];
        }
    }
    
    function set($name, $value){
        $this->params[$name] = $value;
    }
    
    function getURL(){
        switch($this->mode){
            case 1:
                $url = ! empty($this->path_info_base) ? '/' . $this->path_info_base : '';
                foreach($this->params as $k=>$v){
                    $url .= '/' . $k . '/' . $v;
                }
            break;
            default:
                $sep = '';
                $url = '?';
                foreach($this->params as $k=>$v){
                    $url .= $sep . $k . '=' . $v;
                    $sep = '&';
                }
        }
        return $this->basename . $url;
    }
    
    function getLink($text, $attrs=array()){
        $sep = '';
        $attrs['href'] = $this->getURL();
        $html = '';
        foreach($attrs as $k=>$v){
            $html .= $sep . $k . '="' . $v . '"';
            $sep = ' ';
        }
        return '<a ' . $html . '>' . $text . '</a>';
    }
    
}

?>