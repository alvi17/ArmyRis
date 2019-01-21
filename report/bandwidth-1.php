<?php

/**
 * Lists Bandwidth within a date-range
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date Apr 10, 2017 17:56
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";

$pageCode       = 'report-bandwidth-1';
$pageContent	= 'report/bandwidth-1';
$pageTitle 		= 'Bandwidth Report (Method 1)';

if(!Auth::isAuthenticatedPage($pageCode)){
    Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
    Utility::redirect(BASE_URL.'/login.php');
}


$date_from = Input::get('date_from');
$date_to = Input::get('date_to');
//if(empty($date_from)) {$date_from = date('01-m-Y');}
if(empty($date_from)) {$date_from = date('d-m-Y', strtotime("-7 day"));}
if(empty($date_to)) {$date_to = date('d-m-Y');}

//$date_from = '01-03-2017';
//$date_to = '31-03-2017';
$dtt_date_from = date("Y-m-d 00:00:00", strtotime($date_from));
$dtt_date_to = date("Y-m-d 23:59:59", strtotime($date_to));

$sql = "SELECT
  s.`username`
, s.`firstname`
, s.`lastname`
, s.`category`
, s.`package_id`
, p.`mb_unit_value` AS `total_bandwidth`
, '0' AS `free_bandwidth`
, p.`mb_unit_value` AS `paid_bandwidth`
, s.`complemtntary_ratio_factor`
, a.`connection_from`
, a.`connection_to`
FROM `subscribers_connections_audit` a
INNER JOIN subscribers s ON s.`id_subscriber_key` = a.`subscriber_id`
INNER JOIN packages p ON p.`id` = s.`package_id`
WHERE a.`connection_from` BETWEEN '$dtt_date_from' AND '$dtt_date_to'
AND a.`connection_from`<= NOW() -- AND a.`connection_to`<= NOW()
AND a.`comment` = 'Net Connection Enabled'
AND s.`category` = 'Paid'

UNION 

SELECT
  s.`username`
, s.`firstname`
, s.`lastname`
, s.`category`
, s.`package_id`
, p.`mb_unit_value` AS `total_bandwidth`
, (p.`mb_unit_value` * s.`complemtntary_ratio_factor`) AS `free_bandwidth`
, (p.`mb_unit_value` - p.`mb_unit_value` * s.`complemtntary_ratio_factor`) AS `paid_bandwidth`
, s.`complemtntary_ratio_factor`
, a.`connection_from`
, a.`connection_to`
FROM `subscribers_connections_audit` a
INNER JOIN subscribers s ON s.`id_subscriber_key` = a.`subscriber_id`
INNER JOIN packages p ON p.`id` = s.`package_id`
WHERE a.`connection_from` BETWEEN '$dtt_date_from' AND '$dtt_date_to'
AND a.`connection_from`<= NOW() -- AND a.`connection_to`<= NOW()
AND a.`comment` = 'Net Connection Enabled'
AND s.`category` = 'Complementary'

UNION

SELECT
  s.`username`
, s.`firstname`
, s.`lastname`
, s.`category`
, s.`package_id`
, p.`mb_unit_value` AS `total_bandwidth`
, p.`mb_unit_value` AS `free_bandwidth`
, '0' AS `paid_bandwidth`
, s.`complemtntary_ratio_factor`
, a.`connection_from`
, a.`connection_to`
FROM `subscribers_connections_audit` a
INNER JOIN subscribers s ON s.`id_subscriber_key` = a.`subscriber_id`
INNER JOIN packages p ON p.`id` = s.`package_id`
WHERE a.`connection_from` BETWEEN '$dtt_date_from' AND '$dtt_date_to'
AND a.`connection_from`<= NOW() -- AND a.`connection_to`<= NOW()
AND a.`comment` = 'Net Connection Enabled'
AND s.`category` = 'Free'

GROUP BY a.`id_connection_key`
ORDER BY `connection_from` ASC

-- LIMIT 10
";

//echo '<pre>'.$sql;
//echo '<hr>';
$result = DB::getInstance()->query($sql)->results();

//Utility::pa($result);

/*
$total_deposit = 0;
foreach($deposit as $d){
    $total_deposit += $d['amount'];
}*/

require BASE_DIRECTORY.'/views/layouts/admin-base.phtml';



/*
SET @start_date = '2016-01-01';
SET @end_date = '2017-01-01';

select -- count(*) as Active 
s.int_subscriber_key
, s.tx_reg_id
, s.tx_mobile
, s.int_subscriber_type_key
, s.dtt_registration
, s.dtt_deregistration
, s.tx_status
, s.tx_last_menstrual_period
, s.tx_child_birth
from t_subscribers s
where date(s.dtt_registration) <= @end_date 
and (date(s.dtt_deregistration) >=  @start_date OR s.dtt_deregistration is null)
and (s.tx_status = 'Reregistered' or s.tx_status = 'Migrated')
order by s.dtt_registration asc
limit 999999
;
 
  
  
a.`connection_from` BETWEEN '$dtt_date_from' AND '$dtt_date_to'
a.`connection_to`


date(s.dtt_registration) <= @end_date 
and (date(s.dtt_deregistration) >=  @start_date OR s.dtt_deregistration is null)


a.`connection_from` <= '$dtt_date_to'
OR a.`connection_to` >= '$dtt_date_from'
*/