<?php

/**
 * Lists unoccupied Remmote IPs by building id and sets them in dropdown
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date Jan 25, 2017 10:07
 * 
 * http://localhost/armyris/modules/ajx/search-subscribers-by-keys.php?str=ba&rank=7&area=1&building=249
 */

require "../../core/config.php";
require "../../core/init.php";


$str = Input::request('str');
$rank = Input::request('rank');
$area = Input::request('area');
$building = Input::request('building');

if(!empty($str) || !empty($rank) || !empty($area) || !empty($building)){
    $whereCond = '';

    if(!empty($str)){
        $whereCond .= " AND (s.`username` like '%".$str."%' OR s.`firstname` like '%".$str."%' OR s.`lastname` like '%".$str."%' OR s.`official_mobile` like '%".$str."%')";
    }
    if(!empty($rank)){
        $whereCond .= " AND s.`rank_id` = ".$rank;
    }
    if(!empty($area)){
        $whereCond .= " AND s.`area_id` = ".$area;
    }
    if(!empty($building)){
        $whereCond .= " AND s.`building_id` = ".$building;
    }

    $sql = "SELECT 
                  s.`id_subscriber_key`     AS `id`
                , b.`router_no`
                , s.`username`
                , s.`ba_no`
                , s.`firstname`
                , s.`lastname`
                , r.`name` as `rank`
                , s.`official_mobile`
                , DATE_FORMAT(s.`connection_to`,'%d/%m/%Y %H:%i') AS connection_to
                , s.`house_no`
                , b.`building_name` AS `building`
                , a.`area_name` AS `area`
                , CASE s.`status_id`
                    WHEN '0' THEN 'Suspended'
                    WHEN '1' THEN 'Active'
                    WHEN '2' THEN 'Deleted'
                  END as `status`
            FROM `subscribers` s
            LEFT JOIN `ranks` r on r.`id` = s.`rank_id`
            LEFT JOIN `areas` a ON a.`id_area` = s.`area_id`
            LEFT JOIN `buildings` b ON b.`id_building` = s.`building_id`
            WHERE 1 ".$whereCond." ORDER BY r.`order` ASC, s.`firstname` ASC -- s.`id_subscriber_key` DESC
            LIMIT 0, 15";
    $data = DB::getInstance()->query($sql, []);
    //Utility::pr($data->results()); exit;

   
}
?>

<?php
if(isset($data) && $data->count()){
?><style>
    table#rslt th{ background: #A1B161;}
</style>
<table class="table table-condensed table-hover" id="rslt" style="font-size: .8em;">
    <tr>
        <th>#</th><th>Username</th><th>BA</th><th>Rank</th><th>Name</th><th>House</th><th>Building</th><th>Area</th><th>Phone</th><th>Status</th><th>Conn upto</th>
    </tr><?php 
    $i=0;
    foreach($data->results() as $row){
        $sl = ++$i;
        ?><tr class="row-<?php echo $sl;?>"><!--data-dismiss="modal"-->
            <td class="sl">
                <?php echo $sl;?>.
                <span style="display:hidden; color: #fff;"><?php echo $row['id'];?></span>
            </td>
            <td class="username"><?php echo $row['username'];?></td>
            <td class="ba"><?php echo $row['ba_no'];?></td>
            <td class="rank"><?php echo $row['rank'];?></td>
            <td class="name"><?php echo trim($row['firstname']. ' '.$row['lastname']);?></td>
            <td class="house"><?php echo $row['house_no'];?></td>
            <td class="building"><?php echo $row['building'];?></td>
            <td class="area"><?php echo $row['area'];?></td>
            <td class="mobile"><?php echo $row['official_mobile'];?></td>
            <td class="status"><?php echo $row['status'];?></td>
            <td class="connectivity"><?php echo $row['connection_to'];?></td>
        </tr><?php
    }
} else{
    
}
?>
</table>


<script>
$(function() {
    $('#rslt').on("click", "tr", function() {
        $('#_id').val( $(this).find('td.sl span').text() );
        $('#tx_username').text( $(this).find('td.username').text() );
        $('#tx_ba').text($(this).find('td.ba').text());
        $('#tx_rank').text(  $(this).find('td.rank').text() );
        $('#tx_name').text($(this).find('td.name').text());
        $('#tx_house').text($(this).find('td.house').text());
        $('#tx_buildig').text($(this).find('td.building').text());
        $('#tx_area').text($(this).find('td.area').text());
        $('#tx_mobile').text($(this).find('td.mobile').text());
        $('#tx_status').text($(this).find('td.status').text());
        $('#tx_connectivity').text($(this).find('td.connectivity').text());
        
        <?php  //echo '<div data-dismiss="modal">Hi</div>';?>
        $('#srch-rslt-mdl').modal('hide');
    });
});
</script>