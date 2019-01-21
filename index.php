<?php

/**
 * Dashboard - common page
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */

require "core/config.php";
require "core/init.php";
require "modules/acl/Roles.php";
require "modules/subscriber/Subscriber.php";
require "modules/Survey.php";



Auth::confirmLoggedIn();

$roles = new Roles();

if(Session::get('usertype')=='system'){
    require BASE_DIRECTORY.'/index-system.php';
} else{
    require BASE_DIRECTORY.'/index-subscriber.php';
}

