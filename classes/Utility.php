<?php
/**
 * Description of Utility functions/methods
 *
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date December 02, 2016 10:07
 */

use Carbon\Carbon;

class Utility{
	
	public static function confirmDirExists($file)
    {
        $dirName = dirname($file);
        if (!is_dir($dirName)) {
            mkdir($dirName, 0777, true);
        }
    }
	
    /**
     * Prints an array in Readable format
     * @param type $array
     */
    public static function pr($array) 
	{
        echo '<pre>'; print_r($array); echo '</pre>';
    }
    
    /** 
     * Prints array in Array format. It is suitable to copy the prined value into an arry variable.
     * @param type $array
     */
    public static function pa($array) 
	{
        echo '<pre>'; var_export($array); echo '</pre>';
    }
    
    /** 
     * Dumps Array.
     * @param type $array
     */
    public static function dump($array) 
	{
        echo '<pre>'; var_dump($array); echo '</pre>';
    }

    /** 
     * Dumps Array and Stops Execution
     * @param type $array
     */
    public static function dd($array) 
	{
        echo '<pre>'; var_dump($array); echo '</pre>'; die;
    }
    
    public static function redirect($url)
    {
        header('Location: '.$url);
        exit;
    }
    
    public static function commaSeperator($number, $decimal_point=0){
        return number_format($number, $decimal_point, '.', ',');
    }


    /** 
     * This function will take $_SERVER['REQUEST_URI'] and build a breadcrumb based on the user's current path
     * <p><?php echo breadcrumbs(); ?></p>
     * <p><?php echo breadcrumbs(' > '); ?></p>
     * <p><?php echo breadcrumbs(' ^^ ', 'Index'); ?></p>
     */
    public static function breadcrumbs($separator = ' &raquo; ', $lastText = '') {
        // This gets the REQUEST_URI (/path/to/file.php), splits the string (using '/') into an array, and then filters out any empty values
        //$path = array_filter(explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
        
        $current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $path = array_filter(explode('/', str_replace(BASE_URL, '', strtok($current_url, '?'))));
        
        if(substr(end($path), -4)!='.php'){
            $path[] = 'index.php';
        }

        // This will build our "base URL" ... Also accounts for HTTPS :)
        //$base = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        $base = BASE_URL;
        $home = '<i class="fa fa-home"></i> Home';

        // Initialize a temporary array with our breadcrumbs. (starting with our home page, which I'm assuming will be the base URL)
        $breadcrumbs = Array("<a href=\"$base\" class=\"tip-bottom\">$home</a>");

        // Find out the index for the last value in our path array
        $arr = array_keys($path);
        $last = end($arr);

        // Build the rest of the breadcrumbs
        foreach ($path AS $x => $crumb) {
            // Our "title" is the text that will be displayed (strip out .php and turn '_' into a space)
            $title = ucwords(str_replace(['.php', '_', '-'], ['', ' ', ' '], $crumb));

            if ($x != $last){
                $breadcrumbs[] = "<a href=\"$base/$crumb\">$title</a>";
            } else{
//                $title = !empty($lastText) ? $lastText : $title == 'Index'? 'List' : $title;
                if(!empty($lastText)){
                    $title = $lastText;
                } elseif($title == 'Index'){
                    $title = 'List';
                }
                $breadcrumbs[] = "<a href=\"\" class=\"current\">$title</a>";//$title;
            }
        }

        // Build our temporary array (pieces of bread) into one big string :)
        return implode($separator, $breadcrumbs);
    }
    
    public static function listRanks(){
        $ranks = [];
        $result = DB::getInstance()->query("SELECT `id`, `name` FROM `ranks` ORDER BY `order` ASC");       
        if($result->count()){
            foreach($result->results() as $row){
                $ranks[$row['id']] = $row['name'];
            }
        } 
        return $ranks;
    }
    
    public static function listServerAreas(){
        $areas = [];
        $result = DB::getInstance()->query("SELECT `id_area`, `area_name` FROM `areas` WHERE `status_id` = 1 ORDER BY `area_name` ASC");
        if($result->count()){
            foreach($result->results() as $row){
                $areas[$row['id_area']] = $row['area_name'];
            }
        }
        return $areas;
    }
    
    public static function listBuildings(){
        $buildings = [];
        $sql = "SELECT b.`id_building` AS `id`, b.`building_name` AS `name`
                FROM buildings b
                WHERE b.`is_active` = 1
                ORDER BY b.`building_name` ASC";
        $result = DB::getInstance()->query($sql);
        if($result->count()){
            foreach($result->results() as $row){
                $buildings[$row['id']] = $row['name'];
            }
        }
        
        return $buildings;
    }
    
    public static function listBuildingsByAreaId($area_id){
        $buildings = [];
        $sql = "SELECT b.`id_building` AS `id`, b.`building_name` AS `name`
                FROM buildings b
                WHERE b.`area_id` = ? AND b.`is_active` = 1
                ORDER BY b.`building_name` ASC";
        $result = DB::getInstance()->query($sql, [$area_id]);
        if($result->count()){
            foreach($result->results() as $row){
                $buildings[$row['id']] = $row['name'];
            }
        }
        
        return $buildings;
    }
    
    public static function listBuildingDetailsByAreaId($area_id){
        $buildings = [];
        $area_id = (int) $area_id;
        
        if(!empty($area_id)){
            $cond = "b.`area_id` = ? AND";
            $condVal = [$area_id];
        } else{
            $cond = '';
            $condVal = [];
        }
        $sql = "SELECT b.`id_building`, a.`area_name`, b.`building_name`, b.`router_no`, b.`local_ip`
                , b.`ip_block`, b.`remote_ip_first`, b.`remote_ip_last`
                FROM `buildings` b
                INNER JOIN `areas` a ON a.`id_area` = b.`area_id`
                WHERE {$cond} b.`is_active` = 1
                ORDER BY b.`area_id` ASC, b.`building_name` ASC";
        $result = DB::getInstance()->query($sql, [$area_id]);
        if($result->count()){
            foreach($result->results() as $row){
                $buildings[$row['id_building']] = [
                    'area'      => $row['area_name'],
                    'building'  => $row['building_name'],
                    'router_no' => $row['router_no'],
                    'local_ip'  => $row['local_ip'],
                    'ip_block'  => $row['ip_block'],
                    'remote_ip_first'  => $row['remote_ip_first'],
                    'remote_ip_last'  => $row['remote_ip_last'],
                ];
            }
        }
        
        return $buildings;
    }
    
    public static function getBuildingnInfoByBuildingId($building_id){
        $data = ['area_id' => '', 'router_no' => '', 'building_name' => ''];
        
        if(!empty($building_id)){
            $sql = "SELECT b.`area_id`, b.`building_name`, b.`router_no`
                    FROM buildings b
                    WHERE b.`id_building` = ? AND b.`is_active` = 1";
            $result = DB::getInstance()->query($sql, [$building_id]);
            if($result->count()){
                $data = $result->first();
            }
        }
        
        return $data;
    }
    
    public static function getRouterNoLocalIpByBuildingId($building_id){
        $data = ['router_no' => '', 'local_ip' => ''];
        
        if(!empty($building_id)){
            $sql = "SELECT b.`router_no`, b.`local_ip`
                    FROM buildings b
                    WHERE b.`id_building` = ? AND b.`is_active` = 1";
            $result = DB::getInstance()->query($sql, [$building_id]);
            if($result->count()){
                $data = $result->first();
            }
        }
        
        return $data;
    }


    public static function listRemoteIpsByBuildingId($building_id, $remote_ip=''){
        $remote_ips = [];
        
        if(!empty($building_id)){
            $fields = [];
            $sql = "SELECT `id_ip_key` AS id, `ip`
                    FROM `ip_addresses`
                    WHERE (`building_id` = ? AND `status_id` = 0)
                    ";
            $fields[] = $building_id;
            if(!empty($remote_ip)){
                $sql .= " OR `ip`= ?";
                $fields[] = $remote_ip;
            }
            
            $result = DB::getInstance()->query($sql, $fields);
            if($result->count()){
                foreach($result->results() as $row){
                    $remote_ips[$row['id']] = $row['ip'];
                }
            }
        }
        
        return $remote_ips;
    }

    public static function listPackages(){
        $data = [];
        $sql = "SELECT `id`, `code`, `name`, `price`, `days`
                FROM `packages` WHERE status_id = 1
                ORDER BY `price` DESC";
        $result = DB::getInstance()->query($sql);
        if($result->count()){
            foreach($result->results() as $row){
                $data[$row['id']] = [
                    'code' => $row['code'],
                    'name' => $row['name'],
                    'price' => $row['price'],
                    'days' => $row['days'],
                ];
            }
        } 
        return $data;
    }
    
    public static function listRoles(){
        $ranks = [];
        $result = DB::getInstance()->query("SELECT `id`, `name` FROM `roles` WHERE `status_id` = 1 ORDER BY `id` ASC");       
        if($result->count()){
            foreach($result->results() as $row){
                $ranks[$row['id']] = $row['name'];
            }
        } 
        return $ranks;
    }
	
	public static function isValidMobile($mobile){
		$pattern = '/^01[798651][0-9]{8}$/';
		preg_match($pattern, $mobile, $matches);	
				
		if(count($matches)>0){
				return true;
		}
		return false;
	}
    
    public static function getUniqueRandomId(){
        
        //set the random id length 
        $random_id_length = 10; 

        //generate a random id encrypt it and store it in $rnd_id 
        $rnd_id = crypt(uniqid(rand(),1)); 

        //to remove any slashes that might have come 
        $rnd_id = strip_tags(stripslashes($rnd_id)); 

        //Removing any . or / and reversing the string 
        $rnd_id = str_replace(".","",$rnd_id); 
        $rnd_id = strrev(str_replace("/","",$rnd_id)); 

        //finally I take the first 10 characters from the $rnd_id 
        $rnd_id = substr($rnd_id,0,$random_id_length); 
        
        return $rnd_id;
    }
    
    public static function thousandSeperator($number){
        if(empty($number)) return $number;
        
        $number = (int) $number;
        return number_format($number, 0, '.', ',');
    }
    
    public static function extractColumnFromArray($multiDimentionArray, $column){
        return array_column($multiDimentionArray, $column);
    }
    
    public static function pagination($total, $callback, $numberperpage, $currentpage, $trail = '') 
	{
        $firstpage = 1;
        $lastpage = ceil($total / $numberperpage);
        $nextpage = $currentpage+1;
        $previouspage = $currentpage-1;
        
        if ($lastpage > 5 && $currentpage > 3) {
            # if middle point should be > 3
            if ($lastpage - $currentpage > 1) {
                # if gap between middle and last is > 1
                $middlepoint = $currentpage;
            } else {
                # otherwise middle should be respect to last
                $middlepoint = $lastpage - 2;
            }
        } else {
            $middlepoint = 3;
        }
        
        $startpoint = $middlepoint - 2;
        $endpoint = ($lastpage > 5) ? $middlepoint + 2 : $lastpage;
        
        $firstClass = $currentpage<=1 ? ' ui-state-disabled' : '';
        $lastClass = $currentpage>=$lastpage ? ' ui-state-disabled' : '';
        
        $str = '<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
    <div class="dataTables_filter">
        <label style="margin-top:5px;">Total records: '.$total.'</label>
    </div>
    <div class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers">';
    $str .= '<a class="first ui-corner-tl ui-corner-bl fg-button ui-button ui-state-default'.$firstClass.'" href="'.$callback.'1">First</a>
        <a class="previous fg-button ui-button ui-state-default'.$firstClass.'" href="'.$callback.$previouspage.'">Previous</a>';
    $str .= '<span>';
    for ($i = $startpoint; $i <= $endpoint; $i++) {
        if ($i == $currentpage) {
            
            $str .= '<a class="fg-button ui-button ui-state-disabled" href="'.$callback.$i.'">'.$i.'</a>';
        } else {
            $str .= '<a class="fg-button ui-button ui-state-default" href="'.$callback.$i.'">'.$i.'</a>';
        }
        if ($i < $endpoint) {
            $str .= '';
        }
    }
    $str .= '</span>';
    $str .= '<a class="next fg-button ui-button ui-state-default'.$lastClass.'" href="'.$callback.$nextpage.'">Next</a>
        <a class="last ui-corner-tr ui-corner-br fg-button ui-button ui-state-default'.$lastClass.'" href="'.$callback.$lastpage.'">Last</a>
    </div>
</div>';
    
        return $str;
    }
}