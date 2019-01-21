<?php

/*
 * Create Date Time Dropdown
 *  
 * Created on Aug 20, 2016
 * Created by Rafiqul Islam
 */

date_default_timezone_set('Asia/Dhaka');
 
$pb_since = [
	'd' => date('d'),
	'm' => date('m'),
	'y' => date('Y'),
	'h' => date('H'),
	'i' => date('i'),
	'a' => date('A'),
];
 
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$pb_since['d'] = $_POST['pb_since']['d'];
	$pb_since['m'] = $_POST['pb_since']['m'];
	$pb_since['y'] = $_POST['pb_since']['y'];
	$pb_since['h'] = $_POST['pb_since']['h'];
	$pb_since['i'] = $_POST['pb_since']['i'];
	$pb_since['a'] = $_POST['pb_since']['a'];
	
	$dateTime = $pb_since['y'].'-'.$pb_since['m'].'-'.$pb_since['d'].' '.$pb_since['h'].':'.$pb_since['i'].':00'.' '.$pb_since['a'];
}


echo createDateTimeDropdown(12, 'pb_since', 'pb_since', '', $pb_since['d'],$pb_since['m'],$pb_since['y'],$pb_since['h'],$pb_since['i'],$pb_since['a'], true);
echo '<br>';
echo createDateTimeDropdown(24, 'pb_since', 'pb_since', '', $pb_since['d'],$pb_since['m'],$pb_since['y']);
 

function createDateTimeDropdown($format, $name, $id='', $class='', $dateSelected='', $monthSelected='', $yearSelected='', $hourSelected='', $minuteSelected='', $ampmSelected='',$timeInclude = false){
    $class = trim($class.' mlr2');
    
    $months = [
            '01'=>'Jan',
            '02'=>'Feb',
            '03'=>'Mar',
            '04'=>'Apr',
            '05'=>'May',
            '06'=>'Jun',
            '07'=>'Jul',
            '08'=>'Aug',
            '09'=>'Sep',
            '10'=>'Oct',
            '11'=>'Nov',
            '12'=>'Dec'
        ];
        $ampm = ['AM', 'PM'];
        $formats = [24, 12];
        //$format = isset($formats[$format]) ? $formats[$format] : '24';
        $format = (int)$format;
        if(!isset($formats[$format])) $format = 24;
    
    $str = '<select name="'.$name.'[d]" id="'.$id.'_d" class="'.$class.'">';
    $str .= '<option value="">DD</option>';
    for ($i=1; $i<=31; $i++){
        $d = sprintf("%02d", $i);
        $selected = ($dateSelected==$d) ? ' selected' : '';
        $str .= '<option value="'.$d.'"'.$selected.'>'.$i.'</option>';
    }
    $str .= '</select>';
    
    $str .= '<select name="'.$name.'[m]" id="'.$id.'_m" class="'.$class.'">';
    $str .= '<option value="">Month</option>';
    foreach ($months as $key=>$val){
        $selected = ($monthSelected==$key) ? ' selected' : '';
        $str .= '<option value="'.$key.'"'.$selected.'>'.$val.'</option>';
    }
    $str .= '</select>';
    
    $year = date('Y');
    $str .= '<select name="'.$name.'[y]" id="'.$id.'_y" class="'.$class.'">';
    $str .= '<option value="">Year</option>';
    for ($i=$year+5; $i>=$year-50; $i--){
        $selected = ($yearSelected==$i) ? ' selected' : '';
        $str .= '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
    }
    $str .= '</select>';
    
    if($timeInclude){
        
        $str .= '<select name="'.$name.'[h]" id="'.$id.'_h" class="'.$class.'">';
        $str .= '<option value="">HH</option>';
        for ($i=1; $i<=$format; $i++){
            $d = sprintf("%02d", $i);
            $selected = ($hourSelected==$d) ? ' selected' : '';
            $str .= '<option value="'.$d.'"'.$selected.'>'.$d.'</option>';
        }
        $str .= '</select>';

        $str .= '<select name="'.$name.'[i]" id="'.$id.'_i" class="'.$class.'">';
        $str .= '<option value="">MM</option>';
        for ($i=0; $i<=59; $i++){
            $d = sprintf("%02d", $i);
            $selected = ($minuteSelected==$d) ? ' selected' : '';
            $str .= '<option value="'.$d.'"'.$selected.'>'.$d.'</option>';
        }
        $str .= '</select>';
        
        if($format==12){
            $str .= '<select name="'.$name.'[a]" id="'.$id.'_a" class="'.$class.'">';
            $str .= '<option value=""></option>';
            foreach ($ampm as $val){
                $selected = ($ampmSelected==$val) ? ' selected' : '';
                $str .= '<option value="'.$val.'"'.$selected.'>'.$val.'</option>';
            }
            $str .= '</select>';
        }
    }
    
    return $str;
}
