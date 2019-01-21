<?php

/**
 * Logout page for Subscriber and System User both
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */

require "core/config.php";
require "core/init.php";

Session::destroy();
Utility::redirect('login.php');