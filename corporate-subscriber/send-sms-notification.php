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
require "../modules/subscriber/Subscriber.php";
require "../modules/subscriber/Corporate.php";
require_once('../libs/nusoap/lib/nusoap.php');

$pageCode       = 'corporate-subscriber-send-sms-notification';
$pageContent	= 'corporate-subscriber/send-sms-notification';
$pageTitle 		= 'Send SMS Notification';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$c_subs_ids = isset($_POST['c_subs_ids']) ? $_POST['c_subs_ids'] : [];
$sms_text = Input::post('sms_text');

$subscribers = Corporate::listCorporateSubscribersData();
$errors = array();


if(Input::exists() //&& !empty($$c_subs_ids) && !empty($sms_text)
){

    ## VALIDATION
    if(empty($c_subs_ids)){
        $errors['c_subs_ids'] = "Corporate Subscribers should ot be empty!";
    }
    if(empty($sms_text)){
        $errors['sms_text'] = "SMS text should not be empty!";
    }
    if(empty($errors)){
        $smsSendResponse = null;            
        try{
            $mobile_numbers = [];
            $format_sms = [];
            foreach($c_subs_ids as $csid){
                $mobile_numbers[$csid] = $subscribers[$csid]['mobile'];
                $format_sms[] = [
                    "MobileNumber" => $subscribers[$csid]['mobile'],
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

            // echo 'header: <pre>'; var_export($header); echo '</pre>'; echo '<hr>';
            // echo 'msgList: <pre>'; var_export($msgList); echo '</pre>'; echo '<hr>';
            // echo 'smsSendResponse: <pre>'; var_export($smsSendResponse); echo '</pre>'; echo '<hr>';
            // exit;
            
            $data = [
                'sms_text' => $sms_text,
                'sms_receiver' => json_encode($mobile_numbers) . '|'. json_encode($smsSendResponse),
                'sms_type' => 'Corporate Subscriber SMS Notification',
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
}


require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';

// 