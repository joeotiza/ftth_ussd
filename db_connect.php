<?php 

$conn= new mysqli('localhost','root','','ftth_ussd')or die("Could not connect to mysql".mysqli_error($con));

$customersquery="SELECT *, DATEDIFF(`Expiration`, CURDATE()) AS `TED`,
(CASE
	WHEN `Expiration` < CURDATE() THEN 'Expired'
	ELSE 'Active'
END) AS `Status`
FROM (SELECT `User name` AS `Account_ID`, `Expiration`, `Service` AS `Current_Package`, `First name` AS `FirstName`, `Last name` AS `LastName`, `Mobile` AS `MobileNumber`, `Email`, `ONT_Location_Code` AS `LocationCode`
FROM (SELECT * FROM `pyramite_active`
UNION
SELECT * FROM `pyramite_expired`) AS `pyramite`
LEFT JOIN `location_codes` ON `pyramite`.`User name`=`location_codes`.`ONT_Username`
UNION
SELECT `Correlation ID` AS `Account_ID`, STR_TO_DATE(`Topup End Date`, '%c/%d/%Y') as `Expiration`,
`GPONPlan` AS `Current_Package`,
SUBSTRING_INDEX(SUBSTRING_INDEX(`Customer Name`, ' ', 1), ' ', -1) AS `FirstName`,
TRIM( SUBSTR(`Customer Name`, LOCATE(' ', `Customer Name`)) ) AS `LastName`,
`Contact Number` AS `MobileNumber`, `EMail Address` AS `Email`, `ONT_Location_Code` AS `LocationCode`
FROM `customers`
LEFT JOIN `location_codes` ON `customers`.`Correlation ID`=`location_codes`.`ONT_Username`
WHERE `User Group` NOT LIKE '%Gratis%' AND `User Group` NOT LIKE '%Staff%' AND `Correlation ID` NOT IN
(SELECT `User name` FROM
(SELECT * FROM `pyramite_active`
UNION
SELECT * FROM `pyramite_expired`) AS `pyramite`)) AS `myCustomers`
LEFT JOIN
(SELECT `LocationCode`, `LocationDetails`.`AreaCode`, `AreaName`, `EstateName` FROM `LocationDetails` LEFT JOIN `AreaDetails` ON `LocationDetails`.`AreaCode`=`AreaDetails`.`AreaCode`) AS `myLocations`
ON `myCustomers`.`LocationCode`=`myLocations`.`LocationCode` WHERE `Current_Package` LIKE '%home%' ";

$penetrationquery="SELECT *, t1.`LocationCode` AS `t1LocationCode`,
sum(case when `Status` = 'Active' then 1 else 0 end) as `Active`,
sum(case when `Status` = 'Expired' then 1 else 0 end) as `Expired`
FROM (SELECT `AreaCode`,`LocationDetails`.`homes`,`EstateName`,`location_codes`.`ONT_Location_Code` AS `LocationCode`, COUNT(`location_codes`.`ONT_Location_Code`) AS `Connected`,
COUNT(`location_codes`.`ONT_Location_Code`) / NULLIF(`homes`, 0) AS `Penetration`
FROM `LocationDetails`
LEFT JOIN `location_codes`
ON `LocationDetails`.`LocationCode`=`location_codes`.`ONT_Location_Code`
GROUP BY `LocationDetails`.`LocationCode`) AS t1
LEFT JOIN
(SELECT `myLocations`.`AreaName`,`myCustomers`.`LocationCode` AS `LocationCode`, DATEDIFF(`Expiration`, CURDATE()) AS `TED`,
(CASE
	WHEN `Expiration` < CURDATE() THEN 'Expired'
	ELSE 'Active'
END) AS `Status`
FROM (SELECT `User name` AS `Account_ID`, `Expiration`, `Service` AS `Current_Package`, `First name` AS `FirstName`, `Last name` AS `LastName`, `Mobile` AS `MobileNumber`, `Email`, `ONT_Location_Code` AS `LocationCode`
FROM (SELECT * FROM `pyramite_active`
UNION
SELECT * FROM `pyramite_expired`) AS `pyramite`
LEFT JOIN `location_codes` ON `pyramite`.`User name`=`location_codes`.`ONT_Username`
UNION
SELECT `Correlation ID` AS `Account_ID`, STR_TO_DATE(`Topup End Date`, '%c/%d/%Y') as `Expiration`,
`GPONPlan` AS `Current_Package`,
SUBSTRING_INDEX(SUBSTRING_INDEX(`Customer Name`, ' ', 1), ' ', -1) AS `FirstName`,
TRIM( SUBSTR(`Customer Name`, LOCATE(' ', `Customer Name`)) ) AS `LastName`,
`Contact Number` AS `MobileNumber`, `EMail Address` AS `Email`, `ONT_Location_Code` AS `LocationCode`
FROM `customers`
LEFT JOIN `location_codes` ON `customers`.`Correlation ID`=`location_codes`.`ONT_Username`
WHERE `User Group` NOT LIKE '%Gratis%' AND `User Group` NOT LIKE '%Staff%' AND `Correlation ID` NOT IN
(SELECT `User name` FROM
(SELECT * FROM `pyramite_active`
UNION
SELECT * FROM `pyramite_expired`) AS `pyramite`)) AS `myCustomers`
LEFT JOIN
(SELECT `LocationCode`, `LocationDetails`.`AreaCode`, `AreaName`, `EstateName` FROM `LocationDetails` LEFT JOIN `AreaDetails` ON `LocationDetails`.`AreaCode`=`AreaDetails`.`AreaCode`) AS `myLocations`
ON `myCustomers`.`LocationCode`=`myLocations`.`LocationCode` WHERE `Current_Package` LIKE '%home%' ) AS t2
ON t1.`LocationCode`=t2.`LocationCode`
WHERE `Connected`<>0
GROUP BY t1.`LocationCode` ";
