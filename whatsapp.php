<?php
/* Configure Database */
$conn = 'mysql:dbname=ftth_ussd;host=127.0.0.1;'; //database name
$user = 'root'; // your mysql user 
$password = ''; // your mysql password

try {
    $db = new PDO($conn, $user, $password);
}

catch(PDOException $e) {
    //var_dump($e);
    echo("PDO error occurred");
}

catch(Exception $e) {
    //var_dump($e);
    echo("Error occurred");
}
session_start();

$readCapacity = "IF(`GPONPlan` LIKE \"%100%MBPS%\", '100Mbps',
                    IF(`GPONPlan` LIKE \"%65%MBPS%\", '65Mbps',
                        IF(`GPONPlan` LIKE \"%60%MBPS%\", '60Mbps',
                            IF(`GPONPlan` LIKE \"%50%MBPS%\", '50Mbps',
                                IF(`GPONPlan` LIKE \"%40%MBPS%\", '40Mbps',
                                    IF(`GPONPlan` LIKE \"%25%MBPS%\", '25Mbps',
                                        IF(`GPONPlan` LIKE \"%10%MBPS%\", '10Mbps',
                                            IF(`GPONPlan` LIKE \"%5%MBPS%\", '5Mbps',
                                                IF(`GPONPlan` LIKE \"%4%MBPS%\", '4Mbps',
                                                    IF(`GPONPlan` LIKE \"%3%MBPS%\", '3Mbps',
                                                        'N/A'))))))))))";

$package = array();
$package_list = array();
$index = 1;
$stmt = $db->query("SELECT * FROM `Package`");
while($row= $stmt->fetch(PDO::FETCH_ASSOC)):
    $capacitymbps = $row['Capacity']."Mbps";
    $pricekes = "(KES ".number_format($row['Price']).")";
    $package[$index] = array('capacity'=>$capacitymbps, 'price'=>$pricekes);
    array_push($package_list, $capacitymbps." ".$pricekes);
    $index++;
endwhile;
$packageArray = json_encode($package_list);

$questions = array();
$answers = array();
$index = 1;
$stmt = $db->query("SELECT * FROM `questions`");
while($row= $stmt->fetch(PDO::FETCH_ASSOC)):
    array_push($questions, $row['question']);
    array_push($answers, $row['answer']);
    $index++;
endwhile;
$questionsArray = json_encode(array($questions, $answers));

$stmt = $db->query("SELECT * FROM `AreaDetails`;");
$area = array();
$area_list = array();
$estate_list['names'] = array();
$index = 1;
while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
    array_push($area_list, $row['AreaName']); 
    $estate_list[$index] = array();
    array_push($estate_list['names'], $row['AreaName']);
    $area[$index]['name'] = $row['AreaName'];
    $indexLoc = 1;
    $stmtLoc = $db->query("SELECT * FROM `LocationDetails` WHERE `AreaCode`='".$row['AreaCode']."';");
    while($rowLoc = $stmtLoc->fetch(PDO::FETCH_ASSOC)):
        array_push($estate_list[$index], $rowLoc['EstateName']);
        $area[$index]['estate'][$indexLoc] = $rowLoc['EstateName'];
        $indexLoc++;
    endwhile;
    $area[$index]['estate'][$indexLoc] = "Other";
    $index++;
endwhile;
// $areaArray = json_encode($area_list);
$estateArray = json_encode($estate_list);

if (isset($_GET['Phone']) && (!isset($_GET['rootchoice'])))
{
    $phoneNumber = $_GET['Phone'];
    $stmt = $db->query("SELECT *, SUBSTRING_INDEX(SUBSTRING_INDEX(`Customer Name`, ' ', 1), ' ', -1) AS FirstName,
        TRIM( SUBSTR(`Customer Name`, LOCATE(' ', `Customer Name`)) ) AS LastName FROM `customers`
        WHERE `Service Status` LIKE 'Active' AND `Contact Number` LIKE CONCAT('%',substring('".$phoneNumber."', -9)) LIMIT 1");
    $stmt->execute();
    
    $userAvailable = $stmt->fetch(PDO::FETCH_ASSOC);
    // ($userAvailable) ? array_push($estate_list['existingCustomer'], 1) : array_push($estate_list['existingCustomer'], 0);
    $firstName = $userAvailable['FirstName'];
    $lastName = $userAvailable['LastName'];
    $account_ID = $userAvailable['Correlation ID'];
    $customerArray = array($userAvailable['Customer ID'], $phoneNumber, $firstName, $lastName, $account_ID);
    echo json_encode($customerArray);
}

if (isset($_GET['rootchoice']))
{
    switch($_GET['rootchoice'])
    {
        case 1:
            if (!isset($_GET['Address']))
                //echo $areaArray;
                echo $estateArray;
            elseif (!isset($_GET['Confirmation']))
                echo $packageArray;
            elseif (strcasecmp($_GET['Confirmation'], 'yes') == 0)
            {
                $areaName = (isset($_GET['Area'])) ? $area[$_GET['Area']]['name'] : '';
                $estateName = (isset($_GET['Estate'])) ? $area[$_GET['Area']]['estate'][$_GET['Estate']] : '';
                $address = (isset($_GET['Address'])) ? $_GET['Address'] : '';
                $capacityName = (isset($_GET['Capacity'])) ? $package[$_GET['Capacity']]['capacity'] : '';
                $fullName = explode(" ", $_GET['Name'], 2);
                ($firstName != '') ? $firstName = $fullName[0] : $firstName;
                ($lastName != '') ? $lastName = $fullName[1] : $lastName;
                $email = $_GET['Email'];

                if (!$userAvailable)
                {
                    $stmt = $db->query("INSERT INTO `customer_details` (`FirstName`,`LastName`,`Contact Number`,`Email`,`source`)
                        VALUES ('".$fullName[0]."', '".$fullName[1]."', '" .$_GET['Phone']."', '".$email."', 'WhatsApp');");

                    $stmt = $db->query("SELECT * FROM customer_details WHERE `Contact Number` LIKE CONCAT('%',substring('".$_GET['Phone']."', -9)) LIMIT 1");
                    $stmt->execute();

                    $userAvailable = $stmt->fetch(PDO::FETCH_ASSOC);
                }
                $stmt = $db->query("INSERT INTO `get_internet` (`Customer ID`,`Area`,`Address`,`Capacity`,`source`)
                            VALUES ('".$userAvailable['Customer ID']."', '".$areaName."',
                            '".$estateName.", ".$address."', '" .$capacityName."','WhatsApp');");
                echo json_encode("Your details have been captured and you will get a call from us soon.");
            }
            else echo json_encode("Thank you for using our service");
            break;

        case 2:
            $payMenu = array();
            if ($userAvailable)
                array_push($payMenu, $userAvailable['Correlation ID']);
            array_push($payMenu, "OtherAccount");
            if (!isset($_GET['AccountID']))
                echo json_encode($payMenu);
            else
            {
                $stmt = $db->query("SELECT *, SUBSTRING_INDEX(SUBSTRING_INDEX(`Customer Name`, ' ', 1), ' ', -1) AS FirstName,
                TRIM( SUBSTR(`Customer Name`, LOCATE(' ', `Customer Name`)) ) AS LastName FROM customers WHERE `Service Status` LIKE 'Active' 
                    AND `Correlation ID` LIKE '".$_GET['AccountID']."' LIMIT 1");
                $stmt->execute();
                $userAvailable = $stmt->fetch(PDO::FETCH_ASSOC);
                // echo json_encode("Hello");
                echo json_encode(array($userAvailable['Correlation ID'], $userAvailable['FirstName'], $userAvailable['LastName']));
            }
            break;

        case 3:
            $reportMenu = array();
            if ($userAvailable)
                array_push($reportMenu, $userAvailable['Correlation ID']);
            array_push($reportMenu, "OtherAccount");
            if (!isset($_GET['AccountID']))
                echo json_encode($reportMenu);
            elseif (!isset($_GET['Confirmation']))
            {
                $stmt = $db->query("SELECT *, SUBSTRING_INDEX(SUBSTRING_INDEX(`Customer Name`, ' ', 1), ' ', -1) AS FirstName,
                TRIM( SUBSTR(`Customer Name`, LOCATE(' ', `Customer Name`)) ) AS LastName FROM customers WHERE `Service Status` LIKE 'Active' 
                    AND `Correlation ID` LIKE '".$_GET['AccountID']."' LIMIT 1");
                $stmt->execute();
                $userAvailable = $stmt->fetch(PDO::FETCH_ASSOC);
                // echo json_encode("Hello");
                echo json_encode(array($userAvailable['Correlation ID'], $userAvailable['FirstName'], $userAvailable['LastName']));
            }
            elseif (strcasecmp($_GET['Confirmation'], 'yes') == 0)
            {
                $stmt = $db->query("INSERT INTO `cases_reported` (`Correlation ID`,`reported_case`,`source`)
                        VALUES ('".$_GET['AccountID']."', '".$_GET['Issue']."','WhatsApp');");
                echo json_encode("My sincere apologies. Our engineers have been notified and your issue will be resolved ASAP.");
            }
            else echo json_encode("Thank you for using our service");
            break;

        case 4:
            $manageMenu = array();
            if ($userAvailable)
                array_push($manageMenu, $userAvailable['Correlation ID']);
            array_push($manageMenu, "OtherAccount");
            if (!isset($_GET['AccountID']))
                echo json_encode($manageMenu);
            elseif (!isset($_GET['Confirmation']))
            {
                if (!isset($_GET['ChangePlan']))
                {
                    $stmt = $db->query("SELECT *, SUBSTRING_INDEX(SUBSTRING_INDEX(`Customer Name`, ' ', 1), ' ', -1) AS FirstName,
                    TRIM( SUBSTR(`Customer Name`, LOCATE(' ', `Customer Name`)) ) AS LastName FROM customers WHERE `Service Status` LIKE 'Active' 
                        AND `Correlation ID` LIKE '".$_GET['AccountID']."' LIMIT 1");
                    $stmt->execute();
                    $userAvailable = $stmt->fetch(PDO::FETCH_ASSOC);
                    // echo json_encode("Hello");
                    echo json_encode(array($userAvailable['Correlation ID'], $userAvailable['FirstName'], $userAvailable['LastName'], $userAvailable['GPONPlan'], $userAvailable['Topup End Date']));
                }
            }
            if (isset($_GET['ChangePlan']))
            {
                if (!isset($_GET['Confirmation']))
                {
                    $stmt = $db->query("SELECT *, SUBSTRING_INDEX(SUBSTRING_INDEX(`Customer Name`, ' ', 1), ' ', -1) AS FirstName,
                    TRIM( SUBSTR(`Customer Name`, LOCATE(' ', `Customer Name`)) ) AS LastName, ".$readCapacity." AS `Capacity` FROM customers WHERE `Service Status` LIKE 'Active' 
                        AND `Correlation ID` LIKE '".$_GET['AccountID']."' LIMIT 1");
                    $stmt->execute();
                    $userAvailable = $stmt->fetch(PDO::FETCH_ASSOC);

                    $package = array();
                    $package_list = array();
                    $index = 1;
                    $stmt = $db->query("SELECT * FROM `Package`");
                    while($row= $stmt->fetch(PDO::FETCH_ASSOC)):
                        $capacitymbps = $row['Capacity']."Mbps";
                        $pricekes = "(KES ".number_format($row['Price']).")";
                        $package[$index] = array('capacity'=>$capacitymbps, 'price'=>$pricekes);
                        if ($capacitymbps != $userAvailable['Capacity'])
                            array_push($package_list, $capacitymbps." ".$pricekes);
                        $index++;
                    endwhile;
                    $packageArray = json_encode($package_list);

                    // echo json_encode("Hello");
                    echo json_encode(array($userAvailable['Correlation ID'], $userAvailable['FirstName'], 
                    $userAvailable['LastName'], $userAvailable['GPONPlan'], $userAvailable['Topup End Date'], $userAvailable['Capacity'], $package_list));
                }
                elseif (strcasecmp($_GET['Confirmation'], 'yes') == 0)
                {
                    // $string = 'test?=new';
                    $cut_position = strpos($_GET['ToMbps'], ' (KES') + 1; // remove the +1 if you don't want the ? included
                    $tombps = substr($_GET['ToMbps'], 0, $cut_position);
                    $stmt = $db->query("INSERT INTO `plan_change` (`Correlation ID`,`from_mbps`,`to_mbps`,`source`)
                            VALUES ('".$_GET['AccountID']."', '".$_GET['FromMbps']."', '".$tombps."','WhatsApp');");
                    echo json_encode("Your details have been captured and you will get a call from us soon.");
                }
                else echo json_encode("Thank you for using our service"); 
            }
            break;

        case 5:
            echo $questionsArray;
            break;

        default:
            echo json_encode("Sorry. Seems like something went wrong.");

    }
}
//echo $areaArray;

    //Receive the POST
    // $area     =$_GET['Area'];
    // $estate   =$_GET['Estate'];
    // $address   =$_GET['Address'];
    // $capacity   =$_GET['Capacity'];
    // $name   =$_GET['Name'];
    // $email   =$_GET['Email'];

    // //$stmt = $db->query("INSERT INTO `whatsapp` (`Area`) VALUES ('".$area."')");

    // $stmt = $db->query("INSERT INTO `whatsapp` (`Area`,`Estate`,`Address`,`Capacity`,`Name`,`Email`)
    //     VALUES ('".$area."', '".$estate."', '" .$address."', '" .$capacity."', '".$name."', '".$email."');");
?>