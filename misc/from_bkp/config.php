<?php

/**
 * Contains all Configuraion Parameters
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 02, 2016 12:34
 */

//define('ENVIRONMENT', 'production');
define('ENVIRONMENT', 'development');

## DATABASE
define('DB_HOSTNAME'        , 'localhost');
define('DB_USERNAME'        , 'root');
define('DB_PASSWORD'        , '');
define('DB_DATABASE'        , 'armyrisdev');

define('TIME_ZONE'          , 'Asia/Dhaka');
define('SESSION_PREFIX'     , '_RIS_DCANT__');

define('TOKEN_LEVEL'        , '_token');
define('SALT'               , '1836749250');


## ONNOROKOM SMS PROVIDER
define('ONNOROKOM_SMS_USERNAME' , '01769018585');
define('ONNOROKOM_SMS_PASSWORD' , 'rootuser');

define('SUPPORT_ROLE_ID', 3);
		

$base_directory             =  $_SERVER['DOCUMENT_ROOT'] . '/armyris';
$base_url                   = "http://" . $_SERVER['SERVER_NAME'] . '/armyris';
define('BASE_URL'           , $base_url);

define('BASE_DIRECTORY'     , $base_directory);
define('LOG_DIRECTORY'      , $base_directory.'/logs');

define('CURRENCY'           , 'Taka');
define('LIMIT_PER_PAGE'     , 25);

define('DEFAULT_PACKAGE'    , 'default');

//define('CONNECT_BEGIN_TIME'    , '00:00:00');
$subscriber_categories = ['Paid', 'Complementary', 'Free'];
$subscriber_statuses = ['1' => 'Active', '0' => 'Suspended', '2' => 'Deleted'];

$connect_begin_time = ['h'=>'12', 'i'=>'00', 's'=>'00', 'a'=>'pm'];
$connect_end_time = ['h'=>'11', 'i'=>'59', 's'=>'59', 'a'=>'am'];


## MIKROTIK ROUTERS
$mikrotik_routers = [
	1 => [
        'router_name'   => 'Fiber@Home',
        'router_ip'     => '103.7.249.202',
        'username'      => 'software',
        'password'      => 'software12344321@#$',
    ],
    2 => [
        'router_name'   => 'AAMRA',
        'router_ip'     => '203.202.246.41',
        'username'      => 'software',
        'password'      => 'software12344321@#$',
    ],
    /*3 => [
        'router_name'   => 'TEST ROUTER 3',
        'router_ip'     => '203.202.246.42',   // GLOBAL IP
        //'router_ip'     => '172.26.0.121',    // LOCAL IP
        'username'      => 'software',
        'password'      => 'software',
    ],*/
];