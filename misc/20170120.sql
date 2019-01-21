CREATE TABLE tmp_paid
SELECT
  tmp.username
, tmp.created_at
, tmp.disconnect_at
FROM (
SELECT -- p.*,
  p.`subscriber_id`
, s.`username`
, p.`created_at`
, DATE_ADD(p.`created_at`, INTERVAL 30 DAY) AS disconnect_at
FROM `payments` p
INNER JOIN subscribers s ON s.`id_subscriber_key` = p.`subscriber_id`
WHERE p.`type` = 'Scratch Card'
ORDER BY p.`created_at` DESC
) AS tmp 
GROUP BY tmp.`subscriber_id`
;