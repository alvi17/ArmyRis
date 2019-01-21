<?php

/*
 * G:/xampp/php/php.exe -f G:/xampp/htdocs/armyris/modules/disconnect_notification_before_3_days.php
 */

 
 set_time_limit(0);
 
require('E:/xampp/htdocs/armyris/core/init_alt.php');
require_once('E:/xampp/htdocs/armyris/libs/nusoap/lib/nusoap.php');


$db = connectDb(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$log_file = BASE_DIRECTORY.'/logs/'.date('Y').'/'.date('m').'/disconnect_notification_'.date('Ymd').'.log';


$sms_text = "Respected Subscriber

Your connectivity will expire on ". Date('j F, Y \a\t 12:00 \p\m', strtotime("+3 days")) ."

Please recharge to stay connected.

Residential Internet
AITSO";


$msisdns = [];
$sql = "SELECT s.`id_subscriber_key` AS subscriber_id
        , s.`official_mobile` AS msisdn
        FROM subscribers s
        WHERE DATE(s.`connection_to`) = DATE_ADD(CURDATE(), INTERVAL 3 DAY)
        AND LENGTH(s.`official_mobile`) = 11";
$stmt = $db->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll();
$msgList = [];
foreach($results as $res){
    $msisdns[$res['subscriber_id']] = $res['msisdn'];
}

//$msisdns = ['1' => '01911745532', '2' => '01769116576', '3' => '01769013338', '4' => '01856289503', '5' => '01769018592'];
foreach($msisdns as $msisdn){
    $msgList["WsSms"][] = [
        "MobileNumber"  => $msisdn,
        "SmsText"       => $sms_text,
        "Type"          => "TEXT",
    ];
}

$smsSendResponse = null;			
try{
    $soapClient =  new nusoap_client("https://api2.onnorokomSMS.com/sendSMS.asmx?wsdl", 'wsdl');
    $header = array(
        'UserName'      => ONNOROKOM_SMS_USERNAME,
        'UserPassword'  => ONNOROKOM_SMS_PASSWORD,
        'MarskText'     => 'AITSO',
        'CampingName'   => '',
    );
    $smsSendResponse = $soapClient->call("OneToOneBulk", array("messageHeader"=>$header, "wsSmses" => $msgList));
}
catch (Exception $e) {
	$smsSendResponse =  $e->getMessage();
}




/*
$sql = "INSERT INTO bulk_sms (`channel`, `sms_text`, `msisdns`, `response`, `dtt_sent`) VALUES
        ('DISCONNECT_NOTIFICATION', '{$sms_text}', '".json_encode($msisdns)."', '".json_encode($smsSendResponse)."', NOW())";
$stmt = $db->prepare($sql);
$stmt->execute();
*/



/*
SUCCESS RESPONSE:
Array
(
    [OneToOneBulkResult] => 1900||01769014726||40331420/1900||01769014726||40331421/1900||01769014726||40331422/1900||01769014726||40331423/1900||01769014726||40331424/
)


ERROR RESPONSE:

1.
Array
(
    [faultcode] => soap:Server
    [faultstring] => Server was unable to process request. ---> Value cannot be null.
Parameter name: source
    [detail] =>
)

2.
Array
(
    [faultcode] => soap:Server
    [faultstring] => Server was unable to process request. ---> Object reference not set to an instance of an object.
    [detail] =>
) 

3.
Array
(
    [faultcode] => soap:Server
    [faultstring] => Server was unable to process request. ---> Object reference not set to an instance of an object.
    [detail] =>
)
*/






/*
Array
(
    [WsSms] => Array
        (
            [0] => Array
                (
                    [MobileNumber] => 01911745532
                    [SmsText] => Respected Subscriber,
Your connectivity will expire in 03 days.
Please recharge to stay connected.

Res Int
AITSO
                    [Type] => TEXT
                )

            [1] => Array
                (
                    [MobileNumber] => 01769116576
                    [SmsText] => Respected Subscriber,
Your connectivity will expire in 03 days.
Please recharge to stay connected.

Res Int
AITSO
                    [Type] => TEXT
                )

            [2] => Array
                (
                    [MobileNumber] => 01769013338
                    [SmsText] => Respected Subscriber,
Your connectivity will expire in 03 days.
Please recharge to stay connected.

Res Int
AITSO
                    [Type] => TEXT
                )

            [3] => Array
                (
                    [MobileNumber] => 01856289503
                    [SmsText] => Respected Subscriber,
Your connectivity will expire in 03 days.
Please recharge to stay connected.

Res Int
AITSO
                    [Type] => TEXT
                )

            [4] => Array
                (
                    [MobileNumber] => 01769018592
                    [SmsText] => Respected Subscriber,
Your connectivity will expire in 03 days.
Please recharge to stay connected.

Res Int
AITSO
                    [Type] => TEXT
                )

        )

)
 
 

SUCCESS: Array
(
    [OneToOneBulkResult] => 1900||01911745532||40331534/1900||01769116576||40331535/1900||01769013338||40331536/1900||01856289503||40331537/1900||01769018592||40331538/
)
*/