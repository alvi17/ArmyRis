<?php

/**
 * Description of Input
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */


class Input {
    public static function exists($type='post'){
        switch ($type) {
            case 'post':
                return ($_SERVER['REQUEST_METHOD'] === 'POST') ? true : false;
                break;
            
            case 'get':
                return (!empty($_GET)) ? true : false;
                break;
            
            case 'request':
                return (!empty($_REQUEST)) ? true : false;
                break;

            default:
                return false;
                break;
        }
    }
    
    public static function post($item){
        return isset($_POST[$item]) ? trim($_POST[$item]) : null;
    }
    
    public static function get($item){
        return isset($_GET[$item]) ? trim($_GET[$item]) : null;
    }
    
    public static function request($item){
        return isset($_REQUEST[$item]) ? trim($_REQUEST[$item]) : null;
    }
}
