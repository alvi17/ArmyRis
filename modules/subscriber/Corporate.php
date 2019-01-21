<?php
/**
 * Check Subscriber's Log History
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date May 06, 2017 08:16 am
 */

class Corporate{
	public function __construct() {}

	public static function listCorporateSubscribers()
	{
		$subscribers = array();
		$sql = "SELECT
				  s.`id_subscriber_key` AS `id`
				, s.`firstname` 		AS `name`
				, s.`official_mobile` 	AS `mobile`
				FROM subscribers s
				WHERE s.`status_id` = 1
				AND s.`subs_type` = 'corporate'
				ORDER BY s.`firstname` ASC";

		$result = DB::getInstance()->query($sql)->results();

		foreach($result as $res){
			$subscribers[$res['id']] = $res['name'] .' ('.$res['mobile'].')';
		}

		return $subscribers;
	}

	public static function listCorporateSubscribersData()
	{
		$subscribers = array();
		$sql = "SELECT
				  s.`id_subscriber_key` AS `id`
				, s.`firstname` 		AS `name`
				, s.`official_mobile` 	AS `mobile`
				FROM subscribers s
				WHERE s.`status_id` = 1
				AND s.`subs_type` = 'corporate'
				ORDER BY s.`firstname` ASC";

		$result = DB::getInstance()->query($sql)->results();

		foreach($result as $res){
			//$subscribers[$res['id']] = $res['name'] .' ('.$res['mobile'].')';
			$subscribers[$res['id']] = array(
				'name' => $res['name'],
				'mobile' => $res['mobile'],
			);
		}

		return $subscribers;
	}
}