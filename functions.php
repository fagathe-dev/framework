<?php


/**
 * init_session
 *
 * @return mixed
 */
function init_session()
{
    if (session_status() === PHP_SESSION_NONE || session_status() === PHP_SESSION_DISABLED) {
        return session_start();
    }
    return session_start();
}


/**
 * Transform array to string for strip_tags
 * @param array $data
 * @return string
 */
function array_to_string(array $data): string
{
    $str = '';
    foreach ($data as $v) {
        $str .= "&lt;{$v}&gt;";
    }
    return $str;
}