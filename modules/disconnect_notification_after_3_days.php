<?php

/*
 * G:/xampp/php/php.exe -f G:/xampp/htdocs/armyris/modules/disconnect_notification_after_3_days.php
 */

 set_time_limit(0);
 
require('G:/xampp/htdocs/armyris/core/init_alt.php');
require_once('G:/xampp/htdocs/armyris/libs/nusoap/lib/nusoap.php');


$db = connectDb(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$log_file = LOG_DIRECTORY.'/'.date('Y').'/'.date('m').'/disconnect_notification_'.date('Ymd').'.log';


$sms_text = "Respected Subscriber,
Your connectivity already been expired 3 days ago.

Please recharge quickly or account will be deleted soon.

Res Int
AITSO";


$msisdns = [];
$sql = "SELECT s.`id_subscriber_key` AS subscriber_id
        , s.`official_mobile` AS msisdn
        FROM subscribers s
        WHERE s.`status_id` = 0
        AND DATE_ADD(s.`connection_to`, INTERVAL 3 DAY) = CURDATE()
        AND LENGTH(s.`official_mobile`) = 11";
$stmt = $db->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll();
$msgList = [];

foreach($results as $res){
    $msisdns[$res['subscriber_id']] = $res['msisdn'];
}

foreach($msisdns as $msisdn){
    $msgList["WsSms"][] = [
        "MobileNumber"  => $msisdn,
        "SmsText"       => $sms_text,
        "Type"          => "TEXT",
		'maskName'		=> "AITSO", 
        'campaignName'	=> "", 
    ];
}

$smsSendResponse = null;			
try{
    $soapClient =  new nusoap_client("https://api2.onnorokomSMS.com/sendSMS.asmx?wsdl", 'wsdl');
    $header = array(
        'UserName'      => ONNOROKOM_SMS_USERNAME,
        'UserPassword'  => ONNOROKOM_SMS_PASSWORD,
        'MarskText'     => '',
        'CampingName'   => '',
    );
    $smsSendResponse = $soapClient->call("OneToOneBulk", array("messageHeader"=>$header, "wsSmses" => $msgList));
}
catch (Exception $e) {
	$smsSendResponse =  $e->getMessage();
}