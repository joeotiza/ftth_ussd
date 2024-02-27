<?php 

$conn= new mysqli('localhost','root','','ftth_ussd')or die("Could not connect to mysql".mysqli_error($con));

$tz = 'Africa/Nairobi';
$timestamp = time();
$dt = new DateTime("now", new DateTimeZone($tz)); //first argument "must" be a string
$dt->setTimestamp($timestamp); //adjust the object to correct timestamp
//echo $dt->format('d.m.Y, H:i:s');

$customersquery="SELECT 
*,
DATEDIFF(`Expiration`, CURDATE()) AS `TED`,
CASE
	WHEN `Expiration` <= CURDATE() THEN 'Expired'
	ELSE 'Active'
END AS `Status`
FROM (
SELECT 
	`User name` AS `Account_ID`,
	`Expiration`,
	`Service` AS `Current_Package`,
	`First name` AS `FirstName`,
	`Last name` AS `LastName`,
	`Mobile` AS `MobileNumber`,
	`Email`,
	`ONT_Location_Code` AS `LocationCode`
FROM 
	(
		SELECT * FROM `pyramite_active`
		UNION
		SELECT * FROM `pyramite_expired`
	) AS `pyramite`
LEFT JOIN 
	`location_codes` ON `pyramite`.`User name` = `location_codes`.`ONT_Username`
UNION
SELECT 
	`Correlation ID` AS `Account_ID`,
	STR_TO_DATE(`Topup End Date`, '%c/%d/%Y') as `Expiration`,
	`GPONPlan` AS `Current_Package`,
	SUBSTRING_INDEX(SUBSTRING_INDEX(`Customer Name`, ' ', 1), ' ', -1) AS `FirstName`,
	TRIM(SUBSTR(`Customer Name`, LOCATE(' ', `Customer Name`))) AS `LastName`,
	`Contact Number` AS `MobileNumber`,
	`EMail Address` AS `Email`,
	`ONT_Location_Code` AS `LocationCode`
FROM 
	`customers`
LEFT JOIN 
	`location_codes` ON `customers`.`Correlation ID` = `location_codes`.`ONT_Username`
WHERE 
	`User Group` NOT LIKE '%Gratis%' 
	AND `User Group` NOT LIKE '%Staff%'
	AND `Service Status` LIKE 'Active'
	AND `Topup End Date` <> ''
	AND `Correlation ID` NOT IN (
		SELECT `User name` FROM (
			SELECT * FROM `pyramite_active`
			UNION
			SELECT * FROM `pyramite_expired`
		) AS `pyramite`
	)
) AS `myCustomers`
LEFT JOIN 
(
	SELECT 
		`LocationCode`,
		`LocationDetails`.`AreaCode`,
		`AreaName`,
		`EstateName`
	FROM 
		`LocationDetails`
	LEFT JOIN 
		`AreaDetails` ON `LocationDetails`.`AreaCode` = `AreaDetails`.`AreaCode`
) AS `myLocations` ON `myCustomers`.`LocationCode` = `myLocations`.`LocationCode`
WHERE 
`Current_Package` LIKE '%home%' ";

$penetrationquery="SELECT * FROM (SELECT `LocationDetails`.`LocationID`, `LocationDetails`.`LocationCode`, `LocationDetails`.`EstateName`,
`LocationDetails`.`homes`, `AreaDetails`.`AreaID`, `AreaDetails`.`AreaCode`, `AreaDetails`.`AreaName`
FROM `LocationDetails`
LEFT JOIN `AreaDetails`
ON `LocationDetails`.`AreaCode`=`AreaDetails`.`AreaCode`) AS t1
LEFT JOIN
(SELECT  
t2.`LocationCode` AS `t1LocationCode`,
SUM(CASE WHEN t2.`Status` = 'Active' THEN 1 ELSE 0 END) AS `Active`,
SUM(CASE WHEN t2.`Status` = 'Expired' THEN 1 ELSE 0 END) AS `Expired`,
COUNT(`t2`.`Status`) AS `Connected`,
COUNT(`t2`.`Status`) / NULLIF(`homes`, 0) AS `Penetration`
FROM (
SELECT 
	`AreaCode`,
	`LocationDetails`.`homes`,
	`EstateName`,
	`location_codes`.`ONT_Location_Code` AS `LocationCode`
FROM 
	`LocationDetails`
INNER JOIN 
	`location_codes` ON `LocationDetails`.`LocationCode` = `location_codes`.`ONT_Location_Code`
GROUP BY 
	`LocationDetails`.`LocationCode`
) AS t1
INNER JOIN (
SELECT 
	`myLocations`.`AreaName`,
    `myLocations`.`LocationID`,
	`myCustomers`.`LocationCode` AS `LocationCode`,
	DATEDIFF(`Expiration`, CURDATE()) AS `TED`,
	(CASE
		WHEN `Expiration` <= CURDATE() THEN 'Expired'
		ELSE 'Active'
	END) AS `Status`
FROM (
	SELECT 
		`User name` AS `Account_ID`,
		`Expiration`,
		`Service` AS `Current_Package`,
		`First name` AS `FirstName`,
		`Last name` AS `LastName`,
		`Mobile` AS `MobileNumber`,
		`Email`,
		`ONT_Location_Code` AS `LocationCode`
	FROM 
		(
			SELECT * FROM `pyramite_active`
			UNION
			SELECT * FROM `pyramite_expired`
		) AS `pyramite`
	INNER JOIN 
		`location_codes` ON `pyramite`.`User name` = `location_codes`.`ONT_Username`
	UNION
	SELECT 
		`Correlation ID` AS `Account_ID`,
		STR_TO_DATE(`Topup End Date`, '%c/%d/%Y') AS `Expiration`,
		`GPONPlan` AS `Current_Package`,
		SUBSTRING_INDEX(SUBSTRING_INDEX(`Customer Name`, ' ', 1), ' ', -1) AS `FirstName`,
		TRIM(SUBSTR(`Customer Name`, LOCATE(' ', `Customer Name`))) AS `LastName`,
		`Contact Number` AS `MobileNumber`,
		`EMail Address` AS `Email`,
		`ONT_Location_Code` AS `LocationCode`
	FROM 
		`customers`
	INNER JOIN 
		`location_codes` ON `customers`.`Correlation ID` = `location_codes`.`ONT_Username`
	WHERE 
		`User Group` NOT LIKE '%Gratis%' 
		AND `User Group` NOT LIKE '%Staff%'
		AND `Service Status` LIKE 'Active' 
		AND `Topup End Date` <> ''
		AND `Correlation ID` NOT IN (
			SELECT `User name` FROM (
				SELECT * FROM `pyramite_active`
				UNION
				SELECT * FROM `pyramite_expired`
			) AS `pyramite`
		)
) AS `myCustomers`
INNER JOIN (
	SELECT 
    	`LocationID`,
		`LocationCode`,
		`LocationDetails`.`AreaCode`,
		`AreaName`,
		`EstateName`
	FROM 
		`LocationDetails`
	INNER JOIN 
		`AreaDetails` ON `LocationDetails`.`AreaCode` = `AreaDetails`.`AreaCode`
) AS `myLocations` ON `myCustomers`.`LocationCode` = `myLocations`.`LocationCode`
WHERE 
	`Current_Package` LIKE '%home%'
) AS t2 ON t1.`LocationCode` = t2.`LocationCode`
GROUP BY 
t1.`LocationCode`
HAVING 
COUNT(`t2`.`Status`) <> 0) AS t3
ON t3.`t1LocationCode`=t1.`LocationCode` ";
