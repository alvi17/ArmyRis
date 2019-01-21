<?php

/**
 * Description of Sms
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date February 08, 2017 18:48
 */
class Sms {
    public static function send($msisdn, $sms_text="This is a test SMS from ArmyRIS"){
        $response = '';
        try{
            $soapClient = new SoapClient("https://api2.onnorokomSMS.com/sendSMS.asmx?wsdl"); 
            $params = array(
                'userName'		=> "01769018585",
                'userPassword'	=> "rootuser44@#$1",
                'mobileNumber'	=> "88{$msisdn}", // '88'.$msisdn, 
                'smsText'		=> $sms_text,
                'type'			=> "TEXT",
                'marskName'		=> "AITSO", 
                'campaignName'	=> "", 
            );
            
//            echo 'SMS PARAMS: <br>';
//            Utility::pr($params);
//            echo '<br>';

            $value = $soapClient->__call("OneToOne", array($params));
            $response = $value->OneToOneResult;

        } catch (Exception $e) {
            $response = $e->getMessage();
        }
        
        return $response;
    }
}
