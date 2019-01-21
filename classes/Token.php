<?php

/**
 * Description of Token
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date December 02, 2016 10:07
 */
class Token {
    public static function generate($token){
        return Session::put($token, md5(uniqid()));
    }
    
    public static function check($token, $value){
        
        if(Session::has($token) && $value === Session::get($token)){
            Session::delete($token);
            return true;
        }
        return false;
    }
}
