SELECT *
FROM `useraccount` u
WHERE u.`uniqueCode` = 'ba100828' -- 'ba5295'
;

SELECT *
FROM `audituseraccount` u
WHERE u.`uniqueCode` = 'ba100828'
ORDER BY u.`Id` DESC
;

SELECT 
u.`status` = '0'
, u.`updateddate` = NOW()
, u.`updatedby` = '2'
FROM `useraccount` u
WHERE u.`uniqueCode` = 'qwe' -- 'ba5295'
;

INSERT INTO `audituseraccount` au
INNER JOIN `useraccount` u ON u.`componentId` = au.`componentId`
SET au.`componentId` = u.`componentId`
, au.`password` = u.`password`
, au.`fullName` = u.`fullName`
, au.`address` = u.`address`
, au.`auditDescription` = 'Disable Internet Checkin by userId:2 on 2016-10-24 16:08:12'
, au.`operation` = 'Disable Internet Checkin'
, au.`status` = '0'
, au.`logDate` = ''
;