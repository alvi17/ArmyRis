<?php

/**
 * Login Page for Subscriber and System User both
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */

require "core/config.php";
require "core/init.php";
require "modules/user/User.php";
require "modules/subscriber/Subscriber.php";
require "modules/acl/Roles.php";

if(Auth::isLoggedIn()){
    Utility::redirect(BASE_URL);
}
$errors = [];
$username = Input::post('username');
$password = Input::post('password');

if(Input::exists()){
    $user = new User();
    $subscriber = new Subscriber();

    $systemLogin = false;   // System User Login
    $subsLogin = false;     // Subscriber Login

    $systemLogin = $user->login($username, $password);
    
    if(!$systemLogin){
        $subsLogin = $subscriber->login($username, $password);
    }
    if(!$systemLogin && !$subsLogin){
        $errors['message'] = 'Login failed.';
    } else{
        //Session::put('success', 'Log in successed.');
        Utility::redirect('index.php');
    }
}

$pageCode       = 'login';
$pageTitle 		= 'Login';
require BASE_DIRECTORY.'/views/layouts/login.phtml';