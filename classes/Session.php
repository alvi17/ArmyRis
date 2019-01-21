<?php

/**
 * Contains all Session related functions
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */
class Session {
    
    /*
     * The session_regenerate_id() function creates a new unique-ID for to represent the current user’s session. 
     * This should be regenerated time any important authentication action is performed, such as logging in or updating user profile data. 
     * Giving the sessions a new ID after such actions make your application more secure by reducing the risk of a specific attack known as “Session Hijacking.”
     */
    public static function regenerate() {
        session_regenerate_id();
    }
    
    public static function put($name, $value){
        return $_SESSION[SESSION_PREFIX][$name] = $value;
    }
    
    public static function has($name){
        return isset($_SESSION[SESSION_PREFIX][$name]) ? true : false;
    }
    
    public static function get($name){
        return self::has($name) ? $_SESSION[SESSION_PREFIX][$name] : null;
    }
    
    public static function delete($name){
        if(self::has($name)){
            unset($_SESSION[SESSION_PREFIX][$name]);
        }
    }
    
    public static function flash($name, $string=''){
        if(self::has($name)){
            $session = self::get($name);
            self::delete($name);
            return $session;
        } 
        //else {
        //    self::put($name, $string);
        //}
        return false;
    }
    
    public static function destroy(){
        unset($_SESSION[SESSION_PREFIX]);
        //session_destroy();
    }
}
