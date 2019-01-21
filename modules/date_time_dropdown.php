<?php

/*
 * Create Date Time Dropdown
 *  
 * Created on Aug 20, 2016
 * Created by Rafiqul Islam
 */



function createDateTimeDropdown($name, $id='', $class='', $dateSelected='', $monthSelected='', $yearSelected='', $hourSelected='', $minuteSelected='', $ampmSelected='',$timeInclude = true){
    $class = trim($class.' mlr2');
    
    $months = array(
        '01'=>'January',
        '02'=>'February',
        '03'=>'March',
        '04'=>'April',
        '05'=>'May',
        '06'=>'June',
        '07'=>'July',
        '08'=>'August',
        '09'=>'September',
        '10'=>'October',
        '11'=>'November',
        '12'=>'December'
    );
    $ampm = array('am', 'pm');
    
    $str = '<select name="'.$name.'[d]" id="'.$id.'_d" class="'.$class.'">';
    $str .= '<option value="">DD</option>';
    for ($i=1; $i<=31; $i++){
        $d = sprintf("%02d", $i);
        $selected = ($dateSelected==$d) ? ' selected' : '';
        $str .= '<option value="'.$d.'"'.$selected.'>'.$i.'</option>';
    }
    $str .= '</select> ';
    
    $str .= '<select name="'.$name.'[m]" id="'.$id.'_m" class="'.$class.'">';
    $str .= '<option value="">Month</option>';
    foreach ($months as $key=>$val){
        $selected = ($monthSelected==$key) ? ' selected' : '';
        $str .= '<option value="'.$key.'"'.$selected.'>'.$val.'</option>';
    }
    $str .= '</select> ';
    
    $year = date('Y');
    $str .= '<select name="'.$name.'[y]" id="'.$id.'_y" class="'.$class.'">';
    $str .= '<option value="">Year</option>';
    for ($i=$year; $i>=$year-5; $i--){
        $selected = ($yearSelected==$i) ? ' selected' : '';
        $str .= '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
    }
    $str .= '</select>';
    
    if($timeInclude){
        $str .= ' at <select name="'.$name.'[h]" id="'.$id.'_h" class="'.$class.'">';
        $str .= '<option value="">HH</option>';
        for ($i=1; $i<=12; $i++){
            $d = sprintf("%02d", $i);
            $selected = ($hourSelected==$d) ? ' selected' : '';
            $str .= '<option value="'.$d.'"'.$selected.'>'.$d.'</option>';
        }
        $str .= '</select>:';

        $str .= '<select name="'.$name.'[i]" id="'.$id.'_i" class="'.$class.'">';
        $str .= '<option value="">MM</option>';
        for ($i=0; $i<=59; $i=$i+5){
        //for ($i=0; $i<=59; $i++){
            $d = sprintf("%02d", $i);
            $selected = ($minuteSelected==$d) ? ' selected' : '';
            $str .= '<option value="'.$d.'"'.$selected.'>'.$d.'</option>';
        }
        $str .= '</select> ';

        $str .= '<select name="'.$name.'[a]" id="'.$id.'_a" class="'.$class.'">';
        $str .= '<option value=""></option>';
        foreach ($ampm as $val){
            $selected = ($ampmSelected==$val) ? ' selected' : '';
            $str .= '<option value="'.$val.'"'.$selected.'>'.$val.'</option>';
        }
        $str .= '</select>';
    }
    
    return $str;
}
