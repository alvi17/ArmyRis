<?php

include '../classes/Date.php';

/*$date = '02/07/2017';
echo dateDbFormat($date);

function dateDbFormat($date){
    $tmp = explode('/', $date);
    //print_r($tmp);
    
    return (isset($tmp[2])) ? $tmp[2].'-'.$tmp[1].'-'.$tmp[0] : '';
}*/


echo $from = '02/17/2017';
echo '<br>';
//echo date('d-m-Y', strtotime($from));
echo Date::dateDbFormat($from);