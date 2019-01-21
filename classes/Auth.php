<?php
/**
 * Authentication related functions
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */

class Auth {
    const SUPER_ADMIN_ROLE_ID = 1;
    
    public static function isLoggedIn(){
        $uid = Session::get('uid');
        return !empty($uid) ? true : false;
    }
    
    public static function confirmLoggedIn(){
        if(!self::isLoggedIn()){
            //Session::put('error', '');
            Utility::redirect(BASE_URL.'/login.php');
        }
    }
    
    public static function isSuperAdminRole(){
        return in_array(self::SUPER_ADMIN_ROLE_ID, Session::get('user_roles')) ? true : false;
    }

    public static function isAuthenticatedPage($pageCode) {
        self::confirmLoggedIn();
        
        if(self::isSuperAdminRole()) {
            return true;
        }
        
        $acl_pages = Session::get('acl_pages');
        foreach($acl_pages as $pages){
            foreach($pages as $page){
                if(isset($page['code']) && $page['code']== $pageCode){
                    return true;
                }
            }
        }
        
        return false;
    }
    
    public static function isSystemUser(){
        self::confirmLoggedIn();
        return Session::get('usertype')=='system' ? true : false;
    }
    
    public static function isSubscriberUser(){
        self::confirmLoggedIn();
        return Session::get('usertype')=='subscriber' ? true : false;
    }
}
