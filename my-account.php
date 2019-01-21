<?php

/**
 * My Account page (common page)
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */

require "core/config.php";
require "core/init.php";
require "modules/user/User.php";
require "modules/subscriber/Subscriber.php";

Auth::confirmLoggedIn();

if(Session::get('usertype')=='system'){
    require BASE_DIRECTORY.'/my-account-system.php';
} else{
    require BASE_DIRECTORY.'/my-account-subscriber.php';
}

