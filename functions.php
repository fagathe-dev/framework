<?php 

/**
 * Skip accents in string
 * @param string $str
 * @param string $charset
 * @return string
 */
function skip_accents(string $str,string $charset='utf-8' ):string 
{
    $str    = trim($str);
    $str    = htmlentities( $str, ENT_NOQUOTES, $charset );
    
    $str    = preg_replace( '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str );
    $str    = preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $str );
    $str    = preg_replace( '#&[^;]+;#', '', $str );
    $str    = preg_replace('/[^A-Za-z0-9\-]/', ' ', $str);
    
    return $str;
}

/**
 * init_session
 *
 * @return mixed
 */
function init_session()
{
    if ( session_status() === PHP_SESSION_NONE || session_status() === PHP_SESSION_DISABLED ) {
        return session_start();
    }
    return session_start();
}

phpinfo();
function get_url ():string {
    return $_SERVER['REQUEST_URI'];
}

/**
 * Transform array to string for strip_tags
 * @param array $data
 * @return string
 */
function array_to_string(array $data):string
{
    $str = '';
    foreach ($data as $v) {
        $str .= "&lt;{$v}&gt;"; 
    }
    return $str;
}