<?php

/**
 * Description of Init Alt
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 02, 2016 12:34
 */

## DATABASE
define('DB_HOSTNAME'        , 'localhost');
define('DB_USERNAME'        , 'root');
define('DB_PASSWORD'        , '');
define('DB_DATABASE'        , 'armyrisdev');

date_default_timezone_set('Asia/Dhaka');

## ONNOROKOM SMS PROVIDER
define('ONNOROKOM_SMS_USERNAME' , '01769018585');
define('ONNOROKOM_SMS_PASSWORD' , 'rootuser44@#$1');


define('BASE_DIRECTORY', 'E:/xampp/htdocs/armyris');


## MIKROTIK ROUTERS
$mikrotik_routers = [
	/*1 => [
        'router_name'   => 'Fiber@Home',
        'router_ip'     => '',
        'username'      => '',
        'password'      => '',
    ],*/
    1 => [
        'router_name'   => 'AAMRA',
        'router_ip'     => '203.202.254.18:5858',
        'username'      => 'root',
        'password'      => 'rootuser44',
    ],
    
	/*3 => [
        'router_name'   => 'BTCL',
        'router_ip'     => '',
        'username'      => '',
        'password'      => '',
    ],*/
];


// FUNCTION DEFINITION ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
function confirmDirExists($file)
{
    $dirName = dirname($file);
    if (!is_dir($dirName)) {
        mkdir($dirName, 0777, true);
    }
}

function connectDb($host, $username, $password, $dbname)
{
    $dbcon = null;
    try{
        $dbcon = new PDO("mysql:host={$host};dbname={$dbname}", $username, $password);
        $dbcon->exec("SET time_zone='Asia/Dhaka';");
        $dbcon->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (Exception $e) {
         echo "Database Connection Failed: " . $e->getMessage();
    }
    return $dbcon;
}