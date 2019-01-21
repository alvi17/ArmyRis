<?php 

//$msisdn = '01911745532';
//$msisdn = '01769116576';
//$msisdn = '01769013338';
//$msisdn = '01769018585';
$msisdn = '01856289503';

try{
	$soapClient = new SoapClient("https://api2.onnorokomSMS.com/sendSMS.asmx?wsdl"); 
	$paramArray = array(
		'userName'		=> "01769018585",
		'userPassword'	=> "rootuser44@#$1",
		'mobileNumber'	=> '88'.$msisdn, 
		'smsText'		=> "This is a non-masking test SMS from ArmyRIS",
		'type'			=>"TEXT",
		'marskName'		=> "AITSO", 
		'campaignName'	=> '', 
	);

	$value = $soapClient->__call("OneToOne", array($paramArray));
	echo $value->OneToOneResult;
	
} catch (Exception $e) {
	echo $e->getMessage();
}


/*
1900||01769116576||39743109/
1900||01769013338||39743113/
1900||01769018585||39743117/
*/