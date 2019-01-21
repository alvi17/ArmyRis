<?php

/* 
 * Lists Scratchcards and Search Scratchcards using a key
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date January 12, 2017 03:42 am
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/complaint/Complaint.php";
require "../modules/sms/Sms.php";
require "../modules/date_time_dropdown.php";
require_once('../libs/nusoap/lib/nusoap.php');

$pageCode       = 'complaint-add';
$pageContent	= 'complaint/add';
$pageTitle 		= 'Add Complaint';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}

$ranks = Utility::listRanks();
$areas = Utility::listServerAreas();
$problemTypes = Complaint::listProblemTypes();
$supports_in_charge = Complaint::listSupportsInCharge();

$area = Input::post('area');
$buildings = Utility::listBuildingsByAreaId($area);

$id = (int) Input::request('_id');
$building = Input::post('building');
$str = Input::post('str');
$rank = Input::post('rank');
$ptype = Input::post('ptype');
$pdetails = Input::post('pdetails');
$status = Input::post('status');
if(empty($status)){$status = 1;}
$support_in_charge = Input::post('support_in_charge');


$pb_since['d'] = date('d');
$pb_since['m'] = date('m');
$pb_since['y'] = date('Y');
$pb_since['h'] = date('h');
//$pb_since_i = date('i');
$tmp = date('i');
if($tmp%5==0){
    $pb_since['i'] = $tmp;
} else{
    do{
        $pb_since['i'] = --$tmp;
    }while($tmp%5!=0);
}
$pb_since['a'] = date('a');

if(Input::exists()){
    $now = date('Y-m-d H:i:s');
    $uid = Session::get('uid');
    $utype = Session::get('usertype');

    $pb_since['d'] = $_POST['pb_since']['d'];
    $pb_since['m'] = $_POST['pb_since']['m'];
    $pb_since['y'] = $_POST['pb_since']['y'];
    $pb_since['h'] = $_POST['pb_since']['h'];
    $pb_since['i'] = $_POST['pb_since']['i'];
    $pb_since['a'] = $_POST['pb_since']['a'];
    
    // 12 hour format date
    $tmp = $pb_since['y'].'-'.$pb_since['m'].'-'.$pb_since['d'].' '.$pb_since['h'].':'.$pb_since['i'].':00'.' '.$pb_since['a'];
    // 24 hour format date
    $dtt_pb_since = date('Y-m-d H:i:s', strtotime($tmp));
    
    $validate = new Validate();
    $validation = $validate->check($_POST, [
        '_id' => [
            'label' => 'Subscriber Data',
            'value' => $id,
            'rules' => ['required' => true],
        ],
        'ptype' => [
            'label' => 'Problem Type',
            'value' => $ptype,
            'rules' => ['required' => true],
        ],
        
        'support_in_charge' => [
            'label' => 'Support-in-Charge',
            'value' => $support_in_charge,
            'rules' => ['required' => true],
        ],
    ]);
    
    $errors = $validation->errors();
    
    if(strtotime($dtt_pb_since) > time()) {
        $errors['pb_since'] = 'Problem Since cannot be future.';
    }
    
    if(empty($errors)){
    //if($validation->passed()) {
        $insert_data = [
            'subscriber_id' => $id,
            'pb_since' => $dtt_pb_since,
            'pb_type' => $ptype,
            'pb_details' => $pdetails,
            'id_status' => $status,
            'uid_add' => $uid,
            'dtt_add' => $now,
            'add_user_type' => $utype,
            'uid_in_charge' => $support_in_charge,
            'version' => '1',
        ];
        $complain_id = DB::getInstance()->insert('complains', $insert_data, true);
        
        $insert_data = [
            'complain_id' => $complain_id,
            'subscriber_id' => $id,
            'pb_since' => $dtt_pb_since,
            'pb_type' => $ptype,
            'pb_details' => $pdetails,
            'id_status' => $status,
            'uid_mod' => $uid,
            'dtt_mod' => $now,
            'mod_user_type' => $utype,
            'uid_in_charge' => $support_in_charge,
            'version' => '1',
        ];
        DB::getInstance()->insert('complains_audit', $insert_data);
        
        ## SEND SMS TO SUPPORT ASSISTANT
        //$sms_assistant = Complaint::getSmsReceiverMobileNumber($id);
        $problem = isset($problemTypes[$ptype]) ? $problemTypes[$ptype] : '';
        $sms_text_assistant = Complaint::getComplaintSmsText($id, $problem);
        //$sms_send_status = Sms::send($sms_assistant, $sms_text_assistant);
        
        ## SEND SMS TO SUBSCRIBER
        $sms_subscriber = Complaint::getSubscriberMobileNumber($id);
        $sms_text_subscriber = Complaint::getComplaintAcknowledgeSmsText($problem, Date::niceDateTime2($now));
        
        
        $mobile_support_in_charge = isset($supports_in_charge[$support_in_charge]['mobile']) ? $supports_in_charge[$support_in_charge]['mobile'] : '';
        
        
        $smsSendResponse = null;			
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
                'MarskText'     => '',
                'CampingName'   => '',
            );
            $smsSendResponse = $soapClient->call("OneToOneBulk", array("messageHeader"=>$header, "wsSmses" => $msgList));
        }
        catch (Exception $e) {
            $smsSendResponse =  $e->getMessage();
        }
                
        $upd_data = [
            'sms_receiver' => $mobile_support_in_charge.', '.$sms_subscriber,
            'sms_text' => json_encode($msgList),
            'sms_send_response' => json_encode($smsSendResponse),
        ];
        
        DB::getInstance()->update('complains', $upd_data, 'id', $complain_id);
        
        Session::put('success', 'Complaint Added Successfully.');
        Utility::redirect('general-report.php');
    }
    
}

$data = [
        'username' => '',
        'ba_no' => '',
        'rank' => '',
        'firstname' => '',
        'lastname' => '',
        'house' => '',
        'building' => '',
        'area' => '',
        'official_mobile' => '',
        'status' => '',
        'connection_to' => '',
    ];
if(!empty($id)){
    $sql = "SELECT 
                  s.`id_subscriber_key`     AS `id`
                , s.`username`
                , s.`ba_no`
                , s.`firstname`
                , s.`lastname`
                , r.`name` as `rank`
                , s.`official_mobile`
                , DATE_FORMAT(s.`connection_to`,'%d/%m/%Y %H:%i') AS connection_to
                , s.`house_no` AS `house`
                , b.`building_name` AS `building`
                , a.`area_name` AS `area`
                , CASE s.`status_id`
                    WHEN '0' THEN 'Suspended'
                    WHEN '1' THEN 'Active'
                    WHEN '2' THEN 'Deleted'
                  END as `status`
            FROM `subscribers` s
            LEFT JOIN `ranks` r on r.`id` = s.`rank_id`
            LEFT JOIN `areas` a ON a.`id_area` = s.`area_id`
            LEFT JOIN `buildings` b ON b.`id_building` = s.`building_id`
            WHERE s.`id_subscriber_key` = {$id}";
    $result = DB::getInstance()->query($sql, []);
    if($result->count()){
        $data = $result->first();
    }
}

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';