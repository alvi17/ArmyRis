<?php

/**
 * Description of Date Class
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date October 22, 2016 22:57
 */

use Carbon\Carbon;

class Date {


    public static $months = array(
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    );


    public static function listYearsBetnRangeWthCurrYear($from, $to, $order='desc')
    {
        $years = array();
        $from = (int) $from;
        $to = (int) $to;

        if($order=='desc'){
            $years = range(date("Y")+$to, date("Y")-$from);
        } else{
            $years = range(date("Y")-$from, date("Y")+$to);
        }
        return $years;
    }
    
    /**
     * returns date as viewable format
     * @param datetime $date_time
     */
    public static function niceDate($date_time){
        // 8th May, 2017
        return strlen($date_time) ? Carbon::parse($date_time)->format('jS F, Y') : '';
    }
    
    public static function niceDate2($date_time){
        return strlen($date_time) ? Carbon::parse($date_time)->format('jS \o\f F, Y') : '';
    }
    
    public static function niceDateTime($date_time){
        // FORMAT: Thursday, 15 Dec, 2016 6:00 am
        return strlen($date_time) ? Carbon::parse($date_time)->format('D, j M, Y g:i a') : '';
    }
    
    public static function niceDateTime2($date_time){
        // FORMAT: 15 Dec, 2016 6:00 am
        return strlen($date_time) ? Carbon::parse($date_time)->format('j M, Y g:i a') : '';
    }
    
    public static function niceDateTime3($date_time){
        // FORMAT: December 17, 2016 5:59 am
        return strlen($date_time) ? Carbon::parse($date_time)->format('F j, Y g:i a') : '';
    }
    
    public static function niceDateTime4($date_time){
        // FORMAT: 17th December, 2016 at 5:59 am
        return strlen($date_time) ? Carbon::parse($date_time)->format('jS F, Y \a\t g:i a') : '';
    }
    
    public static function niceDateTime5($date_time){
        // FORMAT: 17/12/2016 5:59 am
        return strlen($date_time) ? Carbon::parse($date_time)->format('j/n/Y g:i a') : '';
    }
    
    public static function duration($date1, $date2){
        $str = '';
        $date1 = new DateTime($date1);
        $date2 = new DateTime($date2);
        $interval = $date1->diff($date2);
        
        //Utility::pr($interval); exit;
        
        if($interval->y > 0){
            $str .= $interval->y . " years ";
        }
        if($interval->m > 0){
            $str .= $interval->m." months ";
        }
        if($interval->d > 0){
            $str .= $interval->d." days ";
        }
        if($interval->h > 0){
            $str .= $interval->h." hours ";
        }
        if($interval->i > 0){
            $str .= $interval->i." minitues ";
        }
        if(empty($str)){
            $str = '0 minitue';
        }
        
        return trim($str);
    }
    
    public static function durationDays($date1, $date2){
        $str = '';
        $date1 = new DateTime($date1);
        $date2 = new DateTime($date2);
        $interval = $date1->diff($date2);
        
        return $interval->days . " days ";
    }
    
    public static function isValidDate($date)
    {   
        return (\DateTime::createFromFormat('Y-m-d', $date) !== false) ? true : false;
    }
    
    public static function isValidDateTime24HrFormat($date_time)
    {
        return (\DateTime::createFromFormat('Y-m-d H:i:s', $date_time) !== false) ? true : false;
    }
    
    public static function isValidDateTime12HrFormat($date_time)
    {
        return (\DateTime::createFromFormat('Y-m-d h:i:s a', $date_time) !== false) ? true : false;
    }
    
    public static function isFutureDate($date)
    {
        $today = new \DateTime(); // This object represents current date/time
        $today->setTime(0,0,0); // reset time part, to prevent partial comparison

        $match_date = \DateTime::createFromFormat( "Y-m-d", $date );
        $match_date->setTime(0,0,0); // reset time part, to prevent partial comparison

        $diff = $today->diff($match_date);
        $diffDays = (integer)$diff->format("%R%a"); // Extract days count in interval
        
        return $diffDays > 0 ? true : false;
    }

    public static function convertDate24HrFormatFromArray($raw = ['d'=>'', 'm'=>'', 'y'=>'', 'h'=>'', 'i'=>'', 'a'=>''])
    {
        $dateStr = $raw['y'].'-'.$raw['m'].'-'.$raw['d'].' '.$raw['h'].':'.$raw['i'].':00 '.$raw['a'];
        return self:: isValidDateTime12HrFormat($dateStr)
                    ? date("Y-m-d H:i:s", strtotime($dateStr)) 
                    : null;
    }

    /**
     * 
     * @param string $dateTime = 2016-12-09 03:23:57 pm
     * @return array = [[y]=>2016, [m]=>12, [d]=>09, [h]=>15, [i]=>23, [s]=>57]
     */
    public static function splitParameters12HrFormat($dateTime){
        if(!empty($dateTime)){
            $d = date('Y-m-d-h-i-s-a',strtotime($dateTime));
            $tmp = explode('-', $d);
        }
        return [
            'y' => isset($tmp[0]) ? $tmp[0] : '',
            'm' => isset($tmp[1]) ? $tmp[1] : '',
            'd' => isset($tmp[2]) ? $tmp[2] : '',
            'h' => isset($tmp[3]) ? $tmp[3] : '',   // 24 hours format
            'i' => isset($tmp[4]) ? $tmp[4] : '',
            's' => isset($tmp[5]) ? $tmp[5] : '',
            'a' => isset($tmp[5]) ? $tmp[6] : '',
        ];
    }
    
    /**
     * 
     * @param string $dateTime = 2016-12-09 15:23:57
     * @return array = [[y]=>2016, [m]=>12, [d]=>09, [h]=>15, [i]=>23, [s]=>57]
     */
    public static function splitParameters24HrFormat($dateTime){
        if(!empty($dateTime)){
            $d = date('Y-m-d-H-i-s',strtotime($dateTime));
            $tmp = explode('-', $d);
        }
        return [
            'y' => isset($tmp[0]) ? $tmp[0] : '',
            'm' => isset($tmp[1]) ? $tmp[1] : '',
            'd' => isset($tmp[2]) ? $tmp[2] : '',
            'h' => isset($tmp[3]) ? $tmp[3] : '',   // 24 hours format
            'i' => isset($tmp[4]) ? $tmp[4] : '',
            's' => isset($tmp[5]) ? $tmp[5] : '',
        ];
    }
    
    public static function createDropdown($format, $name, $id='', $class='', $dateSelected='', $monthSelected='', $yearSelected='', $hourSelected='', $minuteSelected='', $ampmSelected='', $timeSeperator = '', $timeInclude = false){
        //$class = trim($class.' mlr2');
        //var_dump($ampmSelected); exit;
        
        $formats = [24, 12];
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
            '12'=>'Dec',
        ];
        $ampm = ['am', 'pm'];
        
        $format = (int)$format;
        if(!in_array($format, $formats)) {$format = 24;}

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

        $current_year = date('Y');
        $from   = $current_year + 5;
        $to     = $current_year - 50;
        $str .= '<select name="'.$name.'[y]" id="'.$id.'_y" class="'.$class.'">';
        $str .= '<option value="">Year</option>';
        for ($i=$from; $i>=$to; $i--){
            $selected = ($yearSelected==$i) ? ' selected' : '';
            $str .= '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
        }
        $str .= '</select>';

        if($timeInclude){
            $str .= $timeSeperator.'<select name="'.$name.'[h]" id="'.$id.'_h" class="'.$class.'">';
            $str .= '<option value="">HH</option>';
            for ($i=0; $i<=$format; $i++){
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
    
    public static function adjustTime($datetime, $time)
    {
        if(empty($datetime['h']) && empty($datetime['i'])){
            $datetime = array_merge($datetime, $time);
        }
        
        return $datetime;
    }
    
    public static function currDateCurrMonth(){
        return date('Y-m-d');
    }

    public static function firstDateCurrWeek(){
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        return Carbon::now()->startOfWeek()->format('Y-m-d');
    }

    public static function lastDateCurrWeek(){
        Carbon::setWeekEndsAt(Carbon::SATURDAY);
        return Carbon::now()->endOfWeek()->format('Y-m-d');
    }
    
    // public static function firstDateCurrMonth(){
    //     return date('Y-m-01');
    // }
    
    // public static function lastDateCurrMonth(){
    //     return date('Y-m-t');
    // }

    public static function firstDateCurrMonth(){
        $date = new Carbon('first day of this month');
        return $date->format('Y-m-d');
    }

    public static function lastDateCurrMonth(){
        $date = new Carbon('last day of this month');
        return $date->format('Y-m-d');
    }

    public static function firstDateCurrYear(){
        //$date = new Carbon('first day of this year');
        //return $date->format('Y-m-d');
        return date('Y-01-01');
    }

    public static function lastDateCurrYear(){
        //$date = new Carbon('last day of this year');
        //return $date->format('Y-m-d');
        return date('Y-m-d', strtotime('Dec 31'));
    }


    
    /**
     * Converts 02/27/2017 to 2017-27-02
     * @param string $date
     */
    public static function dateDbFormat($date){
        //$tmp = explode('/', $date);
        //return (isset($tmp[2])) ? $tmp[2].'-'.$tmp[1].'-'.$tmp[0] : '';
        return date('Y-m-d', strtotime($date));
    }
}