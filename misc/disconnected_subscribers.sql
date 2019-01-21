SELECT
  s.`username`
, b.`router_no`
, s.`connection_to` AS `disconnect_schedule`
, s.`updated_at` AS `disconneted_at`
, a.`comment`
FROM subscribers_connections_audit a
INNER JOIN subscribers s ON s.`id_subscriber_key` = a.`subscriber_id`
INNER JOIN buildings b ON b.`id_building` = s.`building_id`
WHERE DATE( a.`created_at`) = '2017-01-19'
AND a.`comment` = 'Account Suspended by System'
;