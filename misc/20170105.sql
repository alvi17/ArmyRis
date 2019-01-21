SELECT
  COUNT(1) AS total_card
, SUM(c.`amount`) AS total_amount 
FROM `armyris_card_db`.`rechargecard` c
WHERE c.`status` > 0
AND DATE(`c.updateddate`) BETWEEN '2016-11-01' AND '2017-01-06' 
;



SELECT 
  r.amount,
  r.comments,
  p.uniquecode,
  p.userName,
  p.createddate AS billTime,
  DATE_ADD(p.createddate, INTERVAL 30 DAY) AS disconnectTime,
  '2' AS routerNo
FROM `armyrisdb2`.`payment` AS p 
INNER JOIN `armyris_card_db`.`rechargecard` AS r ON p.uniquecode = r.uniquecode 
WHERE DATE(p.`createddate`) BETWEEN '2016-11-01' AND '2017-01-06'
ORDER BY p.`createddate`
LIMIT 999999
;



CREATE TABLE `armyrisdev`.`tmp_payments_nov_till`
SELECT t.* FROM 
(
	SELECT 
	  r.amount,
	  r.comments,
	  p.uniquecode,
	  p.userName,
	  p.createddate AS billTime,
	  DATE_ADD(p.createddate, INTERVAL 30 DAY) AS disconnectTime,
	  '1' AS routerNo
	FROM `armyrisdb`.`payment` AS p 
	INNER JOIN `armyris_card_db`.`rechargecard` AS r ON p.uniquecode = r.uniquecode 
	WHERE DATE(p.`createddate`) BETWEEN '2016-11-01' AND '2017-01-06'

	UNION

	SELECT 
	  r.amount,
	  r.comments,
	  p.uniquecode,
	  p.userName,
	  p.createddate AS billTime,
	  DATE_ADD(p.createddate, INTERVAL 30 DAY) AS disconnectTime,
	  '2' AS routerNo
	FROM `armyrisdb2`.`payment` AS p 
	INNER JOIN `armyris_card_db`.`rechargecard` AS r ON p.uniquecode = r.uniquecode 
	WHERE DATE(p.`createddate`) BETWEEN '2016-11-01' AND '2017-01-06'

) AS t
ORDER BY t.billTime DESC
LIMIT 999999
;



UPDATE `armyrisdev`.`tmp_payments_nov_till` t
SET t.`userName` = REPLACE(t.`userName`,',','')
;
UPDATE `armyrisdev`.`tmp_payments_nov_till` t
SET t.`userName` = REPLACE(t.`userName`,'-','')
;
UPDATE `armyrisdev`.`tmp_payments_nov_till` t
SET t.`userName` = REPLACE(t.`userName`,'_','')
;
UPDATE `armyrisdev`.`tmp_payments_nov_till` t
SET t.`userName` = REPLACE(t.`userName`,' ','')
;

SELECT * FROM `armyrisdev`.`tmp_payments_nov_till` t
WHERE t.`userName` = 'bss101387'
;

SELECT * FROM `armyrisdev`.`tmp_payments_nov_till` t
GROUP BY t.`userName`
;


-- Recharged from November 2016 and added
-- Added
SELECT t.`userName`
, t.`routerNo`
, t.`amount`
, t.`billTime`
, t.`disconnectTime`
FROM `armyrisdev`.`tmp_payments_nov_till_unique` t
INNER JOIN `armyrisdev`.`subscribers` s ON s.`username` = t.`userName`
LIMIT 999999
;


-- REcharged from November 2016 but Missed
SELECT t.`userName`
, t.`routerNo`
, t.`amount`
, t.`billTime`
, t.`disconnectTime`
FROM `armyrisdev`.`tmp_payments_nov_till_unique` t
LEFT JOIN `armyrisdev`.`subscribers` s ON s.`username` = t.`userName`
WHERE s.`username` IS NULL
LIMIT 999999
;

-- REcharged from December 2016 but Missed
SELECT t.`userName`
, t.`routerNo`
, t.`amount`
, t.`billTime`
, t.`disconnectTime`
FROM `armyrisdev`.`tmp_payments_nov_till_unique` t
LEFT JOIN `armyrisdev`.`subscribers` s ON s.`username` = t.`userName`
WHERE s.`username` IS NULL
AND t.`billTime` >= '2016-12-01 00:00:00'
LIMIT 999999
;


UPDATE subscribers s
INNER JOIN `tmp_payments_nov_till_unique` t ON t.`userName` = s.`username`
;