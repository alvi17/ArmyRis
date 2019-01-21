<?php 

/**
 * Description of Init
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 02, 2016 12:34
 */

if(ENVIRONMENT == 'production'){
    ## Turn off all error reporting
    error_reporting(0);
} else if(ENVIRONMENT == 'development'){
    ## Report simple running errors
    //error_reporting(E_ERROR | E_WARNING | E_PARSE);
    ini_set('display_startup_errors', 1); 
    ini_set('display_errors', 1);
    error_reporting(-1);
}

date_default_timezone_set(TIME_ZONE);
session_start();


/*spl_autoload_register(function($class){
    require_once(BASE_DIRECTORY.'/classes/'.$class.'.php');
});*/

require BASE_DIRECTORY."/classes/Auth.php";
require BASE_DIRECTORY."/classes/DB.php";
require BASE_DIRECTORY."/classes/Hash.php";
require BASE_DIRECTORY."/classes/Date.php";
require BASE_DIRECTORY."/classes/Input.php";
require BASE_DIRECTORY."/classes/Logger.php";
require BASE_DIRECTORY."/classes/Sanitise.php";
require BASE_DIRECTORY."/classes/Session.php";
require BASE_DIRECTORY."/classes/Token.php";
require BASE_DIRECTORY."/classes/Utility.php";
require BASE_DIRECTORY."/classes/Validate.php";
require BASE_DIRECTORY."/classes/ScratchCard.php";
require BASE_DIRECTORY."/classes/Payment.php";
require BASE_DIRECTORY."/classes/Location.php";
require BASE_DIRECTORY."/classes/IpAddress.php";


require BASE_DIRECTORY.'/libs/Carbon/vendor/autoload.php';





