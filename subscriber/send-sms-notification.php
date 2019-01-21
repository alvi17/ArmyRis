<?php

/**
 * Send SMS Notification
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date April 08, 2017 15:31
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/Operation.php";
require_once('../libs/nusoap/lib/nusoap.php');

$pageCode       = 'subscriber-send-sms-notification';
$pageContent	= 'subscriber/send-sms-notification';
$pageTitle 		= 'Send SMS Notification';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$total_active_subscibers = 0;

$area = Input::post('area');
$building = Input::post('building');
$sms_text = Input::post('sms_text');

$areas = Utility::listServerAreas();
$buildings = Utility::listBuildingsByAreaId($area);


if(Input::exists() 
    && (!empty($area) || !empty($building)) 
    && !empty($sms_text)
){
    $smsSendResponse = null;			
    try{
        $mobile_numbers = Operation::listActiveSubsceibersMobileNumbers($area, $building);
        $format_sms = [];
        foreach($mobile_numbers as $mn){
            $format_sms[] = [
                "MobileNumber" => $mn,
                "SmsText" => $sms_text,
                "Type" => "TEXT"
            ];
        }
        $msgList = array(
            "WsSms" => $format_sms
        );

        $soapClient =  new nusoap_client("https://api2.onnorokomSMS.com/sendSMS.asmx?wsdl", 'wsdl');
        $header = array(
            'UserName'      => ONNOROKOM_SMS_USERNAME,
            'UserPassword'  => ONNOROKOM_SMS_PASSWORD,
            'MarskText'     => 'AITSO',
            'CampingName'   => '',
        );
        $smsSendResponse = $soapClient->call("OneToOneBulk", array("messageHeader"=>$header, "wsSmses" => $msgList));

        $data = [
            'sms_text' => $sms_text,
            'sms_receiver' => json_encode($mobile_numbers),
            'sms_type' => 'Subscriber SMS Notification',
            'area_id' => $area,
            'building_id' => $building,
            'dtt_sent' => date('Y-m-d H:i:s'),
            'uid_sent' => Session::get('uid'),
        ];
        $id = DB::getInstance()->insert('sms_notificatoin_send', $data, true);
        
        Session::put('success', 'SMS sent successfully.');
        Utility::redirect('send-sms-notification.php');
    }
    catch (Exception $e) {
        $smsSendResponse =  $e->getMessage();
    }
}


require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';

// 