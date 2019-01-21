SELECT * FROM subscribers;
SELECT * FROM subscribers_areas_audit;
SELECT * FROM subscribers_packages_audit;
SELECT * FROM subscribers_categories_audit;
SELECT * FROM subscribers_miktorik_logs;
SELECT * FROM ip_addresses WHERE status_id = 1;

TRUNCATE TABLE subscribers;
TRUNCATE TABLE subscribers_areas_audit;
TRUNCATE TABLE subscribers_packages_audit;
TRUNCATE TABLE subscribers_categories_audit;

UPDATE ip_addresses i
SET i.`status_id` = 0
, i.`updated_at` = NULL
, i.`updated_by` = 1
, i.`version` = 1
WHERE i.`status_id` = 1
;

-- ========================================================
-- SUBSCRIBERS AUDIT CHK
SELECT * FROM `subscribers` ORDER BY id_subscriber_key DESC LIMIT 10;
SELECT * FROM `subscribers_areas_audit` ORDER BY id DESC LIMIT 10;
SELECT * FROM `subscribers_login_credentials_audit` ORDER BY id DESC LIMIT 10;
SELECT * FROM `subscribers_categories_audit` ORDER BY id DESC LIMIT 10;
SELECT * FROM `subscribers_connections_audit` ORDER BY id_connection_key DESC LIMIT 10;
SELECT * FROM `subscribers_miktorik_logs` ORDER BY id DESC LIMIT 10;
SELECT * FROM `subscribers_packages_audit` ORDER BY id DESC LIMIT 10;
SELECT * FROM `payments` ORDER BY `id_payment_key` DESC LIMIT 10;
-- ========================================================