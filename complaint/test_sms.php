<?php 
require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/complaint/Complaint.php";
require "../modules/sms/Sms.php";
require "../modules/date_time_dropdown.php";
require_once('../libs/nusoap/lib/nusoap.php');


$mobile_support_in_charge = '01769018602';
$sms_subscriber = '01856289503';

$sms_text_assistant = 'Assistance Text SMS';
$sms_text_subscriber = 'Subscribe Text SMS';

        try{
            $msgList = array(
                "WsSms" => array(
                    array(
                        "MobileNumber" => $mobile_support_in_charge,
                        "SmsText" => $sms_text_assistant,
                        "Type" => "TEXT"
                    ),
                    array(
                        "MobileNumber" => $sms_subscriber,
                        "SmsText" => $sms_text_subscriber,
                        "Type" => "TEXT"
                    )
                )
            );
            
            $soapClient =  new nusoap_client("https://api2.onnorokomSMS.com/sendSMS.asmx?wsdl", 'wsdl');
            $header = array(
                'UserName'      => ONNOROKOM_SMS_USERNAME,
                'UserPassword'  => ONNOROKOM_SMS_PASSWORD, 
                'MarskText'=> "AITSO",
                'CampingName'   => "OneToOneBulk"			
            );
            $smsSendResponse = $soapClient->call("OneToOneBulk", array("messageHeader"=>$header, "wsSmses" => $msgList));
			echo '<pre>'; var_export($smsSendResponse); echo '</pre>'; exit;
        }
        catch (Exception $e) {
            $smsSendResponse =  $e->getMessage();
        }
		
		
		
		
/*
{"WsSms":[{"MobileNumber":"01769018584","SmsText":"Res Int Service Center.\r\n\r\n            Connection Problem\r\n\r\nBA: ba5764\r\nName: Lt Col Tarek\r\nAddress: H#08\/C1, B#Chaya Surjo, AHQ\r\n\r\nResidential Internet\r\nAITSO","Type":"TEXT"},{"MobileNumber":"01769005764","SmsText":"Valued Subscriber\r\n\r\nYour complain (Connection Problem) is received (8 Apr, 2017 10:30 pm) with care. \r\n\r\nWe will attend the issue ASAP\r\n\r\nResidential Internet\r\nAITSO","Type":"TEXT"}]}
{"WsSms":[{"MobileNumber":"01769018604","SmsText":"Res Int Service Center.\r\n\r\n            New Connection\r\n\r\nBA: bjo20993\r\nName: Md Ishak Ali Khan\r\nAddress: H#5, B#Mannan-114, Zia Colony\r\n\r\nResidential Internet\r\nAITSO","Type":"TEXT"},{"MobileNumber":"01753303307","SmsText":"Respected Subscriber\r\n\r\nYour complain (New Connection) has been received (9 Apr, 2017 8:39 am) with care. \r\n\r\nOur Technicians will attend your problem within shortest possible time.\r\n\r\nThanks for being with us.\r\nRegards\r\n\r\nResidential Internet\r\nAITSO","Type":"TEXT"}]}
*/ 