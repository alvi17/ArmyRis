<?php
/**
 * Check Subscriber's Log History
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date May 06, 2017 08:16 am
 */

class LogHistory{
	public static $subscriber_status = array(1=>'Active', 0=>'Suspended', 2=>'Deleted');

	public function __construct() {}

	public static function commentDetails($comment, $uid, $utype)
	{
		$uname = self::getUsernameByIdAndType($uid, $utype);
		return $comment.' by ' . ($utype=='system' ? 'admin' : 'subscriber') . " <small><i>($uname)</i></small>";
	}

	public static function getUsernameByIdAndType($uid, $utype)
	{
		if($utype=='system'){
			$sql = "SELECT TRIM(CONCAT(u.`firstname`, ' ', u.`lastname`)) AS uname
					FROM users u
					WHERE u.`id` = ?";
		} else{
			$sql = "SELECT TRIM(CONCAT(s.`firstname`, '' , s.`lastname`)) AS uname
					FROM subscribers s
					WHERE s.`id_subscriber_key` = ?";
		} 

		$tmp = DB::getInstance()->query($sql, [$uid])->first();

		return isset($tmp['uname']) ? $tmp['uname'] : '';
	}

	public static function subsNameRank($username)
	{
		$name_rank = '';

		$sql = "SELECT 
				  TRIM(CONCAT(s.`firstname`, '' , s.`lastname`)) AS `name`
				, r.`name` AS `rank`
				FROM subscribers s
				LEFT JOIN ranks r ON r.`id` = s.`rank_id`
				WHERE s.`username` = ?";
		$res = DB::getInstance()->query($sql, [$username])->first();
		$tot = count($res);

		return $name_rank = $tot>0 ? "{$res['name']} <small>{$res['rank']}</small> " : '';
	}

	public static function subsNameRankProfileLink($username)
	{
		$str = '';

		$sql = "SELECT 
				  TRIM(CONCAT(s.`firstname`, '' , s.`lastname`)) AS `name`
				, r.`name` AS `rank`
				, s.id_subscriber_key AS id
				FROM subscribers s
				LEFT JOIN ranks r ON r.`id` = s.`rank_id`
				WHERE s.`username` = ?";
		$res = DB::getInstance()->query($sql, [$username])->first();
		$tot = count($res);

		return $str = $tot>0 ? "<a class=\"instrc\" href='".BASE_URL.'/subscriber/detail.php?id='.$res['id']."'>{$res['name']} <small>{$res['rank']}</small></a> " : '';
	}

	public static function listConnectivityHistories($username, $fr, $to, $is_total = false, $page=1, $limit = LIMIT_PER_PAGE)
	{
		$where_cond_more = '';

		if(!empty($fr) && !empty($to)){
			$where_cond_more .= " AND c.`created_at` BETWEEN '{$fr} 00:00:00' AND '{$to} 23:59:59'";
		} else{
			if(!empty($fr)){
				$where_cond_more .= " AND c.`created_at` >= '{$fr} 00:00:00'";
			}
			elseif(!empty($to)){
				$where_cond_more .= " AND c.`created_at` <= '{$to} 23:59:59'";
			}
		}

		if($is_total){
			$fields = "COUNT(1) AS TOTAL";
			$order_limit = "";
		} else{
			$fields = "c.`status_id`
				, p.`name` AS `package_name`
				, c.`amount`
				, c.`connection_from`
				, c.`connection_to`
				, c.`comment`
				, c.`created_at`
				, c.`created_by`
				, c.`created_user_type`";

			$order_limit = "ORDER BY c.`created_at` DESC
	            LIMIT ".($page-1)*$limit.", {$limit}";
		}

		$sql = "SELECT
				{$fields}
				FROM subscribers_connections_audit c
				INNER JOIN `subscribers` s ON s.`id_subscriber_key`= c.`subscriber_id`
				LEFT JOIN `packages` p ON p.`id` = c.`package_id`
				WHERE s.`username` = '{$username}'
				{$where_cond_more}
				{$order_limit}";
		//echo "<pre>$sql"; die;
		
		return $data = DB::getInstance()->query($sql)->results();
	}

	public static function listPaymentHistories($username, $fr, $to, $is_total = false, $page=1, $limit = LIMIT_PER_PAGE)
	{
		$where_cond_more = '';

		if(!empty($fr) && !empty($to)){
			$where_cond_more .= " AND p.`created_at` BETWEEN '{$fr} 00:00:00' AND '{$to} 23:59:59'";
		} else{
			if(!empty($fr)){
				$where_cond_more .= " AND p.`created_at` >= '{$fr} 00:00:00'";
			}
			elseif(!empty($to)){
				$where_cond_more .= " AND p.`created_at` <= '{$to} 23:59:59'";
			}
		}

		if($is_total){
			$fields = "COUNT(1) AS TOTAL";
			$order_limit = "";
		} else{
			$fields = "p.`created_at` AS `date`
			    , p.`type`
		        , if(p.`debit`=0, '', p.`debit`) AS `debit`
		        , if(p.`credit`=0, '', p.`credit`) AS `credit`
		        , p.`balance`
		        , p.`created_by`
        		, p.`created_user_type`";

			$order_limit = " ORDER BY p.`id_payment_key` DESC
	            LIMIT ".($page-1)*$limit.", {$limit}";
		}

"
SELECT p.`type`
        , if(p.`debit`=0, '', p.`debit`) AS `debit`
        , if(p.`credit`=0, '', p.`credit`) AS `credit`
        , p.`balance`
        , p.`created_at`
        FROM `payments` p
        INNER JOIN `subscribers` s ON s.`id_subscriber_key` = p.`subscriber_id`
        WHERE s.`username` = ?
        ORDER BY p.`id_payment_key` DESC
";

		$sql = "SELECT
				{$fields}
				FROM `payments` p
        		INNER JOIN `subscribers` s ON s.`id_subscriber_key` = p.`subscriber_id`
				WHERE s.`username` = '{$username}'
				{$where_cond_more}
				{$order_limit}";
		//echo "<pre>$sql"; die;

		return $data = DB::getInstance()->query($sql)->results();
	}


	public static function listPackageHistories($username, $fr, $to, $is_total = false, $page=1, $limit = LIMIT_PER_PAGE)
	{
		$where_cond_more = '';

		if(!empty($fr) && !empty($to)){
			$where_cond_more .= " AND pa.`dtt_mod` BETWEEN '{$fr} 00:00:00' AND '{$to} 23:59:59'";
		} else{
			if(!empty($fr)){
				$where_cond_more .= " AND pa.`dtt_mod` >= '{$fr} 00:00:00'";
			}
			elseif(!empty($to)){
				$where_cond_more .= " AND pa.`dtt_mod` <= '{$to} 23:59:59'";
			}
		}

		if($is_total){
			$fields = "COUNT(1) AS TOTAL";
			$order_limit = "";
		} else{
			$fields = "pa.`dtt_mod` AS `date`
				, p.`name` AS `package`
				, pa.`comment`
				, pa.`uid_mod`
				, pa.`user_type`";

			$order_limit = " ORDER BY pa.`dtt_mod` DESC
	            LIMIT ".($page-1)*$limit.", {$limit}";
		}

		$sql = "SELECT
				{$fields}
				FROM subscribers_packages_audit pa
				INNER JOIN `subscribers` s ON s.`id_subscriber_key`= pa.`subscriber_id`
				LEFT JOIN `packages` p ON p.`id` = pa.`package_id`
				WHERE s.`username` = '{$username}'
				{$where_cond_more}
				{$order_limit}";
		//echo "<pre>$sql"; die;

		return $data = DB::getInstance()->query($sql)->results();
	}


	public static function listCategoryHistories($username, $fr, $to, $is_total = false, $page=1, $limit = LIMIT_PER_PAGE)
	{
		$where_cond_more = '';

		if(!empty($fr) && !empty($to)){
			$where_cond_more .= " AND ca.`dtt_mod` BETWEEN '{$fr} 00:00:00' AND '{$to} 23:59:59'";
		} else{
			if(!empty($fr)){
				$where_cond_more .= " AND ca.`dtt_mod` >= '{$fr} 00:00:00'";
			}
			elseif(!empty($to)){
				$where_cond_more .= " AND ca.`dtt_mod` <= '{$to} 23:59:59'";
			}
		}

		if($is_total){
			$fields = "COUNT(1) AS TOTAL";
			$order_limit = "";
		} else{
			$fields = "ca.`category`
				, ca.`complementary_amount`
				, ca.`comment`
				, ca.`uid_mod`
				, ca.`user_type`
				, ca.`dtt_mod` AS `date`";

			$order_limit = " ORDER BY ca.`dtt_mod` DESC
	            LIMIT ".($page-1)*$limit.", {$limit}";
		}

		$sql = "SELECT
				{$fields}
				FROM subscribers_categories_audit ca
				INNER JOIN `subscribers` s ON s.`id_subscriber_key`= ca.`subscriber_id`
				WHERE s.`username` = '{$username}'
				{$where_cond_more}
				{$order_limit}";
		//echo "<pre>$sql"; die;

		return $data = DB::getInstance()->query($sql)->results();
	}


	public static function listAreaHistories($username, $fr, $to, $is_total = false, $page=1, $limit = LIMIT_PER_PAGE)
	{
		$where_cond_more = '';

		if(!empty($fr) && !empty($to)){
			$where_cond_more .= " AND sa.`dtt_mod` BETWEEN '{$fr} 00:00:00' AND '{$to} 23:59:59'";
		} else{
			if(!empty($fr)){
				$where_cond_more .= " AND sa.`dtt_mod` >= '{$fr} 00:00:00'";
			}
			elseif(!empty($to)){
				$where_cond_more .= " AND sa.`dtt_mod` <= '{$to} 23:59:59'";
			}
		}

		if($is_total){
			$fields = "COUNT(1) AS TOTAL";
			$order_limit = "";
		} else{
			$fields = "sa.`router_no`
				, a.`area_name` AS `area`
				, b.`building_name` AS `building`
				, sa.`house_no`
				, sa.`local_ip`
				, sa.`remote_ip`
				, sa.`comment`
				, sa.`uid_mod`
				, sa.`dtt_mod` AS `date`
				, IFNULL(sa.`user_type`, 'system') AS `user_type`";

			$order_limit = " ORDER BY sa.`dtt_mod` DESC
	            LIMIT ".($page-1)*$limit.", {$limit}";
		}

		$sql = "SELECT
				{$fields}
				FROM subscribers_areas_audit sa
				INNER JOIN `subscribers` s ON s.`id_subscriber_key`= sa.`subscriber_id`
				LEFT JOIN areas a ON a.`id_area` = sa.`area_id`
				LEFT JOIN buildings b ON b.`id_building` = sa.`building_id`
				WHERE s.`username` = '{$username}'
				{$where_cond_more}
				{$order_limit}";
		//echo "<pre>$sql"; die;

		return $data = DB::getInstance()->query($sql)->results();
	}


	public static function listLoginCredentialHistories($username, $fr, $to, $is_total = false, $page=1, $limit = LIMIT_PER_PAGE)
	{
		$where_cond_more = '';

		if(!empty($fr) && !empty($to)){
			$where_cond_more .= " AND lc.`dtt_mod` BETWEEN '{$fr} 00:00:00' AND '{$to} 23:59:59'";
		} else{
			if(!empty($fr)){
				$where_cond_more .= " AND lc.`dtt_mod` >= '{$fr} 00:00:00'";
			}
			elseif(!empty($to)){
				$where_cond_more .= " AND lc.`dtt_mod` <= '{$to} 23:59:59'";
			}
		}

		if($is_total){
			$fields = "COUNT(1) AS TOTAL";
			$order_limit = "";
		} else{
			$fields = "lc.`dtt_mod` AS `date`
				, lc.`username`
				, lc.`password`
				, lc.`comment`
				, lc.`uid_mod`
				, lc.`user_type`";

			$order_limit = " ORDER BY lc.`dtt_mod` DESC
	            LIMIT ".($page-1)*$limit.", {$limit}";
		}

		$sql = "SELECT
				{$fields}
				FROM subscribers_login_credentials_audit lc 
				INNER JOIN `subscribers` s ON s.`id_subscriber_key`= lc.`subscriber_id`
				WHERE s.`username` = '{$username}'
				{$where_cond_more}
				{$order_limit}";
		//echo "<pre>$sql"; die;

		return $data = DB::getInstance()->query($sql)->results();
	}
}