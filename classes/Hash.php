<?php

/**
 * Description of Hash
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */
class Hash {
    public static function make($string, $salt=''){
        return hash('sha256', $string.$salt);
    }
    
    public static function salt($length){
        return mcrypt_create_iv($length);
    }
    
    public static function unique(){
        return self::make(uniqid());
    }
}
