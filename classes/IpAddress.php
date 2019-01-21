<?php

/**
 * Description of IpAddress
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date November 28, 2016 21:03
 */
class IpAddress {
    /**
     * @param string $range
     * @return array
     * $ips=ipListFromRange("194.8.42.0/24");
     */
    public static function ipListFromRange($range){
        $parts = explode('/',$range);
        $exponent = 32-$parts[1].'-';
        $count = pow(2,$exponent);
        $start = ip2long($parts[0]);
        $end = $start+$count;
        return array_map('long2ip', range($start, $end) );
    }

    /**
     * @param string $start
     * @param string $end
     * @return array
     * $ips = listIpsBetweenTwoValues('10.20.1.200', '10.20.2.15');
     */
    public static function listIpsBetweenTwoValues($start, $end){
        $start = ip2long($start);
        $end = ip2long($end);
        return array_map('long2ip', range($start, $end) );
    }
}
