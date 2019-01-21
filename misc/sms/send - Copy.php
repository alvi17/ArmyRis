<?php 

$msisdn = isset($_REQUEST['msisdn']) ? trim($_REQUEST['msisdn']) : '';
$_tkn = isset($_REQUEST['_tkn']) ? trim($_REQUEST['_tkn']) : '';
$sms_id = isset($_REQUEST['sms_id']) ? (int) $_REQUEST['sms_id'] : 0;
/*
print_r($_REQUEST);
echo PHP_EOL;
echo 'msisdn: '. $msisdn;
echo PHP_EOL;
echo '_tkn: '. $_tkn;
echo PHP_EOL;
echo 'sms_id: '. $sms_id;
echo PHP_EOL;
*/

$sms = [
	1 => 'Aponjon shebai nibondhon shofol hoyeche. Ovinandon, Apni protiti tothyo ekhon theke sompurno free paben. Sheba bondho korte STOP 2 likhe 16227-e SMS pathan.',
	0 => 'Aponjon shebay nibondhon shofol hoyeche.Proti shoptahe sheba pete proti tothyer jonno Tk 2.37 nishchit korun.Sheba bondho korte STOP 2 likhe 16227-e SMS pathan.',
];

if($_tkn === 'ADR7!f3c8oRd'){
	try{
		$soapClient = new SoapClient("https://api2.onnorokomSMS.com/sendSMS.asmx?wsdl"); 
		$paramArray = array(
			'userName'		=> "01715812079",
			'userPassword'	=> "rti1234",
			'mobileNumber'	=> '88'.$msisdn, 
			'smsText'		=> isset($sms[$sms_id]) ? $sms[$sms_id] : $sms[0],
			'type'			=>"TEXT",
			//'maskName'		=> "Aponjon", 
			'campaignName'	=> '', 
		);

		$value = $soapClient->__call("OneToOne", array($paramArray));
		echo $value->OneToOneResult;
		
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	
} else{
	echo "Invald argument sent: $msisdn||$_tkn||$sms_id ";
}

exit;