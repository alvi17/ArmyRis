<?php 
/*
$range  = "194.8.42.0/24";
$ips=ipListFromRange($range);
echo '<pre>'; print_r($ips);  echo '</pre>';
*/


$ips = listIpsBetweenTwoValues('10.20.1.200', '10.20.2.15');
echo '<pre>'; print_r($ips);  echo '</pre>';



function ipListFromRange($range){
    $parts = explode('/',$range);
    $exponent = 32-$parts[1].'-';
    $count = pow(2,$exponent);
    $start = ip2long($parts[0]);
    $end = $start+$count;
	
    return array_map('long2ip', range($start, $end) );
}

function listIpsBetweenTwoValues($start, $end){
    $start = ip2long($start);
    $end = ip2long($end);
    
    return array_map('long2ip', range($start, $end) );
}