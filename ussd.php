<?php

/**
  * USSD Based Mobile Money Application  
  *
  * @category PHP
  *
  * @author  Basil Ndonga <basilndonga@gmail.com>
  *
  * @version 1.0.0
  *
  * For enquies dont hesitate to contact me on +254 728 986 084
  *
  **/


 //Ensure ths code runs only after a POST from AT
if(!empty($_POST) && !empty($_POST['phoneNumber'])){
    /* Configure Database */
    $conn = 'mysql:dbname=ftth_ussd;host=127.0.0.1;'; //database name
    $user = 'root'; // your mysql user 
    $password = ''; // your mysql password

    //  Create a PDO instance that will allow you to access your database
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
    require_once('AfricasTalkingGateway.php');
    require_once('config.php');

    //Receive the POST from AT
    $sessionId     =$_POST['sessionId'];
    $serviceCode   =$_POST['serviceCode'];
    $phoneNumber   =$_POST['phoneNumber'];
    $text          =$_POST['text'];
    if (($pos = strrpos($text, "*0*")) !== FALSE) {
        $text = substr($text, $pos+3);
    }

    //Explode the text to get the value of the each interaction - think 1*1
    $textArray=explode('*', $text);

    //Check if the user is in the db
    $stmt = $db->query("SELECT *, SUBSTRING_INDEX(SUBSTRING_INDEX(`Customer Name`, ' ', 1), ' ', -1) AS FirstName,
    TRIM( SUBSTR(`Customer Name`, LOCATE(' ', `Customer Name`)) ) AS LastName FROM `customers` WHERE `Contact Number` LIKE CONCAT('%',substring('".$phoneNumber."', -9)) LIMIT 1");
    $stmt->execute();
    
    $userAvailable = $stmt->fetch(PDO::FETCH_ASSOC);
    $firstName = ($userAvailable) ? $userAvailable['FirstName'] : '';

    $package = array(
        "1"=>array('capacity'=>"5Mbps" ,'price'=>"(KES 2,395)"),
        "2"=>array('capacity'=>"10Mbps",'price'=>"(KES 3,354)"),
        "3"=>array('capacity'=>"25Mbps",'price'=>"(KES 4,982)"),
        "4"=>array('capacity'=>"50Mbps",'price'=>"(KES 6,554)"),
        "5"=>array('capacity'=>"100Mbps",'price'=>"(KES 11,499)"));

    $questions = array
    (
        "1" => array
                (
                    "question" => "Question 1?",
                    "answer" => "Answer to question one.",
                ),
        "2" => array
                (
                    "question" => "Question 2?",
                    "answer" => "Answer to question two.",
                ),
        "3" => array
                (
                    "question" => "Question three?",
                    "answer" => "Answer to question three.",
                ),
        "4" => array
                (
                    "question" => "Question four?",
                    "answer" => "Answer to question four.",
                ),
        "5" => array
                (
                    "question" => "Question five?",
                    "answer" => "Answer to question five.",
                )
    );

    if (!$textArray[0] || $textArray[sizeof($textArray)-1]=="0")
    {
        $response = "CON Welcome " . $firstName;
        $response .= "\nSelect an option.\n";
        $response .= " 1. Get Home Internet\n";
        $response .= " 2. Pay\n";
        $response .= " 3. Report a Case\n";
        $response .= " 4. Manage Account\n";						
        $response .= " 5. FAQs\n";
                                                                                                                                                    

        // Print the response onto the page so that our gateway can read it
        header('Content-type: text/plain');
        echo $response;	
        //break;
    }

    else
    {
        switch ($textArray[0])
        {
            case "1":
                $area = array(
                "1"=>array(
                    'name'=>"Runda",
                    'estate'=>array(
                        1=>"Edenville Phase 1",
                        2=>"Rosslyn Valley",
                        3=>"Runda Estate",
                        4=>"The Horseshoe Village",
                        5=>"OTHER")
                    ),
                "2"=>array(
                    'name'=>"Greatwall",
                    'estate'=>array(
                        1=>"Great Wall Apartments Phase 1",
                        2=>"Great Wall Apartments Phase 2",
                        3=>"Great Wall Apartments Phase 3",
                        4=>"Wema Villas, Athi River",
                        5=>"OTHER")
                    ),
                "3"=>array(
                    'name'=>"Embakasi",
                    'estate'=>array(
                        1=>"Amani Court",
                        2=>"Church Court",
                        3=>"Afya Court",
                        4=>"Tumaini Court",
                        5=>"Aviation/NSSF",
                        6=>"Kwa Ndege",
                        7=>"OTHER")
                    ),
                "4"=>array(
                    'name'=>"Westlands",
                    'estate'=>array(
                        1=>"Muthithi Road",
                        2=>"Taarifa Road",
                        3=>"Mideya Gardens",
                        4=>"OTHER")
                    ),
                "5"=>array(
                    'name'=>"Gigiri",
                    'estate'=>array(
                        1=>"Village Road",
                        2=>"Gigiri Drive",
                        3=>"Warwick Centre",
                        4=>"OTHER")
                    ),
                "6"=>array(
                    'name'=>"Kileleshwa",
                    'estate'=>array(
                        1=>"Gatundu Road",
                        2=>"Mandera Road",
                        3=>"Gichugu Road",
                        4=>"OTHER")
                    ),
                "7"=>array(
                    'name'=>"Kilimani",
                    'estate'=>array(
                        1=>"Naivasha Road",
                        2=>"OTHER")
                    ),
                "8"=>array(
                    'name'=>"Ngong Road",
                    'estate'=>array(
                        1=>"Ngong Road",
                        2=>"OTHER")
                    ),
                "9"=>array(
                    'name'=>"Karen",
                    'estate'=>array(
                        1=>"Sandalwood Waterfront",
                        2=>"OTHER")
                    ),
                "10"=>array(
                    'name'=>"Kitusuru",
                    'estate'=>array(
                        1=>"Kirawa Road",
                        2=>"OTHER")
                    ),
                "11"=>array(
                    'name'=>"Kikuyu",
                    'estate'=>array(
                        1=>"Liberty Suites Muthiga",
                        2=>"OTHER")
                    ),
                "12"=>array(
                    'name'=>"Mwimuto",
                    'estate'=>array(
                        1=>"Solomon Stump",
                        2=>"Getathuru Road",
                        3=>"The Aviv Kitusuru",
                        4=>"OTHER")
                    ),
                "13"=>array(
                    'name'=>"Kiambu",
                    'estate'=>array(
                        1=>"Ndenderu-Banana Link",
                        2=>"OTHER")
                    ),
                "14"=>array(
                    'name'=>"Ruiru",
                    'estate'=>array(
                        1=>"Sahara Ridge Estate",
                        2=>"OTHER")
                    ),
                "15"=>array(
                    'name'=>"Thika",
                    'estate'=>array(
                        1=>"Imani Estate (Delmonte)",
                        2=>"OTHER")
                    ),
                "16"=>array(
                    'name'=>"Kawangware",
                    'estate'=>array(
                        1=>"Gitanga Road",
                        2=>"Macharia Road",
                        2=>"OTHER")
                    ));

                if (!$textArray[1])
                {
                    $response = "CON Select an Area.\n";
                    foreach($area as $key=>$value)
                    {
                        if ($key > 6) continue;//ignore areas with index > 6
                        $response .=  " " . $key . ". " . $value['name'] . "\n";
                    }
                    $response .= " 98. MORE\n";						
                    $response .= "\n 0. Back to Main Menu\n";
                    
                    // Print the response onto the page so that our gateway can read it
                    header('Content-type: text/plain');
                    echo $response;
                }
                else
                {
                    $continue = 0;
                    
                    if ($textArray[1] == 98)
                    {
                        $continue = 1;
                        if ($textArray[2] == 98) $continue=2;
                        if ($textArray[2] == 98 && !$textArray[3])
                        {
                            // $continue = 2;
                            $response = "CON Select an Area.\n";
                            foreach($area as $key=>$value)
                            {
                                if ($key <= 12) continue;//ignore areas with index <=12
                                $response .=  " " . $key . ". " . $value['name'] . "\n";
                            }						
                            $response .= "\n 0. Back to Main Menu\n";
                                
                            // Print the response onto the page so that our gateway can read it
                            header('Content-type: text/plain');
                            echo $response;
                            break;
                        }
                        elseif(!$textArray[2])
                        {
                                
                            $response = "CON Select an Area.\n";
                            foreach($area as $key=>$value)
                            {
                                if ($key <= 6 || $key > 12) continue;//ignore areas with index <6 and >12
                                $response .=  " " . $key . ". " . $value['name'] . "\n";
                            }
                            $response .= " 98. MORE\n";					
                            $response .= "\n 0. Back to Main Menu\n";
                                
                            // Print the response onto the page so that our gateway can read it
                            header('Content-type: text/plain');
                            echo $response;
                            break;
                        }
                    }
                    // else
                    {
                        if (!$textArray[$continue+2])
                        //enter address
                        {
                            $response = "CON Select Estate/Court/Road.\n";
                            foreach($area[$textArray[$continue+1]]['estate'] as $key=>$value)
                            {
                                $response .=  " " . $key . ". " . $value . "\n";
                            }				
                            $response .= "\n 0. Back to Main Menu\n";
                                
                            // Print the response onto the page so that our gateway can read it
                            header('Content-type: text/plain');
                            echo $response;
                        }
                        else
                        {
                            if (!$textArray[$continue+3])
                            {
                                $response = "CON Enter further Address details(Block/House No/Description).\n";
                                $response .= "\n 0. Back to Main Menu\n";

                                header('Content-type: text/plain');
                                echo $response;
                            }
                            else
                            {
                                if (!$textArray[$continue+4])
                                {
                                    $response = "CON Select a Package.\n";
                                    //$response .= $area[$textArray[$continue+1]]['estate'][$textArray[$continue+2]]."\n";
                                    foreach($package as $key=>$value)
                                    {
                                        $response .=  " " . $key . ". " . $value['capacity']." ".$value['price'] . "\n";
                                    }						
                                    $response .= "\n 0. Back to Main Menu\n";

                                    header('Content-type: text/plain');
                                    echo $response;
                                }
                                else
                                {
                                    $capacity = $package[$textArray[$continue+4]]['capacity'];
                                    if (!$textArray[$continue+5] && !$userAvailable)
                                    {
                                        $response = "CON Provide your full name.\n";
                                        $response .= "\n 0. Back to Main Menu\n";

                                        header('Content-type: text/plain');
                                        echo $response;
                                    }
                                    else
                                    {
                                        if (!$userAvailable)
                                        {
                                            //Record Customer Details
                                            $fullName = explode(" ", $textArray[$continue+5], 2);
                                            $stmt = $db->query("INSERT INTO `customer_details` (`FirstName`,`LastName`,`Contact Number`)
                                            VALUES ('".$fullName[0]."', '".$fullName[1]."', '" .$phoneNumber."');");
                                            //$stmt->execute();

                                            $stmt = $db->query("SELECT * FROM customer_details WHERE `Contact Number` LIKE CONCAT('%',substring('".$phoneNumber."', -9)) LIMIT 1");
                                            $stmt->execute();
                                            
                                            $userAvailable = $stmt->fetch(PDO::FETCH_ASSOC);
                                        }
                                        
                                        //Record Get Internet Details
                                        $stmt = $db->query("INSERT INTO `get_internet` (`Customer ID`,`Area`,`Address`,`Capacity`)
                                        VALUES ('".$userAvailable['Customer ID']."', '".$area[$textArray[$continue+1]]['name']."', '".$area[$textArray[$continue+1]]['estate'][$textArray[$continue+2]].", ".$textArray[$continue+3]."', '" .$capacity."');");
                                        //$stmt->execute();
                                        //$textArray[$continue+1]['estate'][$textArray[$continue+2]].

                                        $response = "END Thank you ";
                                        $response .= $userAvailable['FirstName'].".\n";
                                        $response .= "Your details have been captured.\n";
                                        $response .= "You will get a call from us to schedule the date of installation.\n";

                                        header('Content-type: text/plain');
                                        echo $response;
                                    }
                                }   
                            } 
                        }
                    }                  
                }
                break;

            case "2":
                $counter = ($userAvailable) ? 2 : 1 ;
                if (!$textArray[1])
                {
                    $counterDisplay = 1;
                    $response = "CON Pay for:\n";
                    if ($userAvailable)
                    {
                        $response .= " ".$counterDisplay++ . ". ".$userAvailable['Correlation ID']."\n";
                    }
                    $response .= " ".$counterDisplay. ". Other Account\n";
                    $response .= "\n 0. Back to Main Menu\n";

                    header('Content-type: text/plain');
                    echo $response;
                }
                else
                {
                    if ($textArray[1] == $counter)
                    {
                        if (!$textArray[2])
                        {
                            $response = "CON Input the account number:\n";
                            $response .= "\n 0. Back to Main Menu\n";
                            echo $response;
                        }
                        else
                        {
                            if (!$textArray[3])
                            {
                                $stmt = $db->query("SELECT *, SUBSTRING_INDEX(SUBSTRING_INDEX(`Customer Name`, ' ', 1), ' ', -1) AS FirstName,
                                TRIM( SUBSTR(`Customer Name`, LOCATE(' ', `Customer Name`)) ) AS LastName FROM customers WHERE `Correlation ID` LIKE '%".$textArray[2]."%' LIMIT 1");
                                $stmt->execute();
                                
                                $accountAvailable = $stmt->fetch(PDO::FETCH_ASSOC);

                                if ($accountAvailable)
                                {
                                    $response = "CON Confirm payment of the account no.".$accountAvailable['Correlation ID']."\n belonging to ";
                                    $response .= $accountAvailable['FirstName']." ".$accountAvailable['LastName'].".\n";
                                    $response .= " 1. Yes\n";
                                    $response .= " 2. No\n";
                                    $response .= "\n 0. Back to Main Menu\n";
                                    echo $response;
                                }
                                else
                                {
                                    $response = "END The specified account could not be found in our records.";
                                    echo $response;
                                }                                
                            }
                            else
                            {
                                if ($textArray[3] == "1")
                                {
                                    $response = "END You can pay to me via the MPESA means and...";
                                    echo $response;
                                }
                                else
                                {
                                    $response = "END Thank you for accessing our services.";
                                    echo $response;
                                }
                            }
                        }
                        
                    }
                    elseif ($textArray[1] == "1")
                    {
                        if (!$textArray[2])
                        {
                            $response = "CON Confirm payment of the account no.".$userAvailable['Correlation ID']."\n belonging to ";
                            $response .= $userAvailable['FirstName']." ".$userAvailable['LastName'].".\n";
                            $response .= " 1. Yes\n";
                            $response .= " 2. No\n";
                            $response .= "\n 0. Back to Main Menu\n";
                            echo $response;
                        }
                        else
                        {
                            if ($textArray[2] == "1")
                            {
                                $response = "END You can pay to me via the MPESA means and...";
                                echo $response;
                            }
                            else
                            {
                                $response = "END Thank you for accessing our services.";
                                echo $response;
                            }
                        }
                    }
                }
                break;

            case "3":
                $cases = array(
                    "1"=>"Service Outage",
                    "2"=>"Slow Internet",
                    "3"=>"Poor Signal Strength",
                    "4"=>"No service despite payment"
                );
                
                if (!$textArray[1])
                {
                    $response = "CON Select the case to report:\n";
                    foreach($cases as $key=>$value)
                    {
                        $response .=  " " . $key . ". " . $value . "\n";
                    }
                    $response .= "\n 0. Back to Main Menu\n";
                    echo $response;
                }
                else
                {
                    if ($textArray[1])
                    {
                        $counter = ($userAvailable) ? 2 : 1 ;
                        if (!$textArray[2])
                        {
                            $counterDisplay = 1;
                            $response = "CON Report ".$cases[$textArray[1]]." for:\n";
                            if ($userAvailable)
                            {
                                $response .= " ".$counterDisplay++ . ". ".$userAvailable['Correlation ID']."\n";
                            }
                            $response .= " ".$counterDisplay. ". Other Account\n";
                            $response .= "\n 0. Back to Main Menu\n";

                            header('Content-type: text/plain');
                            echo $response;
                        }
                        else
                        {
                            if ($textArray[2] == $counter)
                            {
                                if (!$textArray[3])
                                {
                                    $response = "CON Input the account number:\n";
                                    $response .= "\n 0. Back to Main Menu\n";
                                    echo $response;
                                }
                                else
                                {
                                    $stmt = $db->query("SELECT *, SUBSTRING_INDEX(SUBSTRING_INDEX(`Customer Name`, ' ', 1), ' ', -1) AS FirstName,
                                    TRIM( SUBSTR(`Customer Name`, LOCATE(' ', `Customer Name`)) ) AS LastName FROM customers WHERE `Correlation ID` LIKE '%".$textArray[3]."%' LIMIT 1");
                                    $stmt->execute();
                                        
                                    $accountAvailable = $stmt->fetch(PDO::FETCH_ASSOC);

                                    if (!$textArray[4])
                                    {
                                        if ($accountAvailable)
                                        {
                                            $response = "CON Report ".$cases[$textArray[1]]." for the account no.".$accountAvailable['Correlation ID']."\n belonging to ";
                                            $response .= $accountAvailable['FirstName']." ".$accountAvailable['LastName'].".\n";
                                            $response .= " 1. Yes\n";
                                            $response .= " 2. No\n";
                                            $response .= "\n 0. Back to Main Menu\n";
                                            echo $response;
                                        }
                                        else
                                        {
                                            $response = "END The specified account could not be found in our records.";
                                            echo $response;
                                        }                                
                                    }
                                    else
                                    {
                                        if ($textArray[4] == "1")
                                        {
                                            $stmt = $db->query("INSERT INTO `cases_reported` (`Correlation ID`,`reported_case`)
                                                VALUES ('".$accountAvailable['Correlation ID']."', '".$cases[$textArray[1]]."');");

                                            $response = "END Your response has been captured.\n";
                                            $response .= "Kindly be patient as our Engineers restore the service.\n";
                                            $response .= "You'll receive a confirmation once it is up.\n";
                                            echo $response;
                                        }
                                        else
                                        {
                                            $response = "END Thank you for accessing our services.";
                                            echo $response;
                                        }
                                    }
                                }
                                
                            }
                            elseif ($textArray[2] == "1")
                            {
                                if (!$textArray[3])
                                {
                                    $response = "CON Report ".$cases[$textArray[1]]." for the account no.".$userAvailable['Correlation ID']."\n belonging to ";
                                    $response .= $userAvailable['FirstName']." ".$userAvailable['LastName'].".\n";
                                    $response .= " 1. Yes\n";
                                    $response .= " 2. No\n";
                                    $response .= "\n 0. Back to Main Menu\n";
                                    echo $response;
                                }
                                else
                                {
                                    if ($textArray[3] == "1")
                                    {
                                        $stmt = $db->query("INSERT INTO `cases_reported` (`Correlation ID`,`reported_case`)
                                        VALUES ('".$userAvailable['Correlation ID']."', '".$cases[$textArray[1]]."');");

                                        $response = "END Your response has been captured.\n";
                                        $response .= "Kindly be patient as our Engineers restore the service.\n";
                                        $response .= "You'll receive a confirmation once it is up.\n";
                                        echo $response;
                                    }
                                    else
                                    {
                                        $response = "END Thank you for accessing our services.";
                                        echo $response;
                                    }
                                }
                            }
                        }
                    }
                }
                break;

            case "4":
                $offered_package = array();
                foreach($package as $key=>$value)
                {
                    array_push($offered_package,$value['capacity']);
                }

                $readCapacity = "IF(`GPONPlan` LIKE \"%100%MBPS%\", '100Mbps',
                                    IF(`GPONPlan` LIKE \"%60%MBPS%\", '60Mbps',
										IF(`GPONPlan` LIKE \"%50%MBPS%\", '50Mbps',
                                            IF(`GPONPlan` LIKE \"%40%MBPS%\", '40Mbps',
											    IF(`GPONPlan` LIKE \"%25%MBPS%\", '25Mbps',
												    IF(`GPONPlan` LIKE \"%10%MBPS%\", '10Mbps',
													    IF(`GPONPlan` LIKE \"%5%MBPS%\", '5Mbps',
                                                            IF(`GPONPlan` LIKE \"%3%MBPS%\", '3Mbps',
														        'N/A'))))))))";

                if (!$textArray[1])
                {
                    $response = "CON 1. Check service status\n";
                    $response .= " 2. Change Plan\n";
                    $response .= "\n 0. Back to Main Menu\n";

                    header('Content-type: text/plain');
                    echo $response;
                }
                else
                {
                    if ($textArray[1] == 1)
                    {
                        $counter = ($userAvailable) ? 2 : 1 ;
                        if (!$textArray[2])
                        {
                            $counterDisplay = 1;
                            $response = "CON Check status for:\n";
                            if ($userAvailable)
                            {
                                $response .= " ".$counterDisplay++ . ". ".$userAvailable['Correlation ID']."\n";
                            }
                            $response .= " ".$counterDisplay. ". Other Account\n";
                            $response .= "\n 0. Back to Main Menu\n";

                            header('Content-type: text/plain');
                            echo $response;
                        }
                        else
                        {
                            if ($textArray[2] == $counter)
                            {
                                if (!$textArray[3])
                                {
                                    $response = "CON Input the account number:\n";
                                    $response .= "\n 0. Back to Main Menu\n";
                                    echo $response;
                                }
                                else
                                {
                                    $stmt = $db->query("SELECT *, SUBSTRING_INDEX(SUBSTRING_INDEX(`Customer Name`, ' ', 1), ' ', -1) AS FirstName,
                                    TRIM( SUBSTR(`Customer Name`, LOCATE(' ', `Customer Name`)) ) AS LastName,".$readCapacity." as capacity FROM customers WHERE `Correlation ID` LIKE '%".$textArray[3]."%' LIMIT 1");
                                    $stmt->execute();
                                        
                                    $accountAvailable = $stmt->fetch(PDO::FETCH_ASSOC);

                                    if (($key = array_search($accountAvailable['capacity'], $offered_package)) !== false) {
                                        unset($offered_package[$key]);
                                    }
                                    $i = 1;
                                    foreach($offered_package as $mbps)
                                    {
                                        $notmypackage[$i++] = $mbps;
                                    }

                                    if (!$textArray[4])
                                    {
                                        if ($accountAvailable)
                                        {
                                            $response = "CON Change plan of the account no.".$accountAvailable['Correlation ID']." belonging to ";
                                            $response .= $accountAvailable['FirstName']." ".$accountAvailable['LastName']." from ".$accountAvailable['capacity']." to:\n";
                                            foreach($notmypackage as $key=>$value)
                                            {
                                                $response .=  " " . $key . ". " . $value . "\n";
                                            }
                                            $response .= "\n 0. Back to Main Menu\n";
                                            echo $response;
                                        }
                                        else
                                        {
                                            $response = "END The specified account could not be found in our records.";
                                            echo $response;
                                        }                                
                                    }
                                    else
                                    {
                                        if (!$textArray[5])
                                        {
                                            $response = "CON Change Plan for account no.".$accountAvailable['Correlation ID']." from ";
                                            $response .= $accountAvailable['capacity']." to ".$notmypackage[$textArray[4]].":\n";
                                            $response .= " 1. Yes\n";
                                            $response .= " 2. No\n";
                                            $response .= "\n 0. Back to Main Menu\n";

                                            echo $response;
                                        }
                                        else
                                        {
                                            if ($textArray[5] == "1")
                                            {
                                                $stmt = $db->query("INSERT INTO `plan_change` (`Correlation ID`,`from_mbps`,`to_mbps`)
                                                    VALUES ('".$accountAvailable['Correlation ID']."', '".$accountAvailable['capacity']."', '".$notmypackage[$textArray[4]]."');");
                                                
                                                $response = "END Your details have been captured and you will get a call from us soon.";
                                                echo $response;
                                            }
                                            else
                                            {
                                                $response = "END Thank you for accessing our services.";
                                                echo $response;
                                            }
                                            
                                        }
                                    }
                                }
                                
                            }
                            elseif ($textArray[2] == "1")
                            {
                                $stmt = $db->query("SELECT *, SUBSTRING_INDEX(SUBSTRING_INDEX(`Customer Name`, ' ', 1), ' ', -1) AS FirstName,
                                TRIM( SUBSTR(`Customer Name`, LOCATE(' ', `Customer Name`)) ) AS LastName,".$readCapacity." as capacity FROM customers WHERE `Correlation ID` LIKE '%".$userAvailable['Correlation ID']."%' LIMIT 1");
                                $stmt->execute();
                                        
                                $userAvailable = $stmt->fetch(PDO::FETCH_ASSOC);

                                if (($key = array_search($userAvailable['capacity'], $offered_package)) !== false) {
                                    unset($offered_package[$key]);
                                }
                                $i = 1;
                                foreach($offered_package as $mbps)
                                {
                                    $notmypackage[$i++] = $mbps;
                                }

                                if (!$textArray[3])
                                {
                                    $response = "CON Change Plan of the account no.".$userAvailable['Correlation ID']." belonging to ";
                                    $response .= $userAvailable['FirstName']." ".$userAvailable['LastName']." from ".$userAvailable['capacity']." to:\n";
                                    foreach($notmypackage as $key=>$value)
                                    {
                                        $response .=  " " . $key . ". " . $value . "\n";
                                    }
                                    $response .= "\n 0. Back to Main Menu\n";
                                    echo $response;
                                }
                                else
                                {
                                    if (!$textArray[4])
                                    {
                                        $response = "CON Change Plan for account no.".$userAvailable['Correlation ID']." from ";
                                        $response .= $userAvailable['capacity']." to ".$notmypackage[$textArray[3]].":\n";
                                        $response .= " 1. Yes\n";
                                        $response .= " 2. No\n";
                                        $response .= "\n 0. Back to Main Menu\n";

                                        echo $response;
                                    }
                                    else
                                    {
                                        if ($textArray[4] == "1")
                                        {
                                            $stmt = $db->query("INSERT INTO `plan_change` (`Correlation ID`,`from_mbps`,`to_mbps`)
                                                VALUES ('".$userAvailable['Correlation ID']."', '".$userAvailable['capacity']."', '".$notmypackage[$textArray[3]]."');");
                                        
                                            $response = "END Your details have been captured and you will get a call from us soon.";
                                            echo $response;
                                        }
                                        else
                                        {
                                            $response = "END Thank you for accessing our services.";
                                            echo $response;
                                        }
                                        
                                    }
                                }
                            }
                        }
                    }
                    elseif ($textArray[1] == 2)
                    {
                        $counter = ($userAvailable) ? 2 : 1 ;
                        if (!$textArray[2])
                        {
                            $counterDisplay = 1;
                            $response = "CON Change Plan for:\n";
                            if ($userAvailable)
                            {
                                $response .= " ".$counterDisplay++ . ". ".$userAvailable['Correlation ID']."\n";
                            }
                            $response .= " ".$counterDisplay. ". Other Account\n";
                            $response .= "\n 0. Back to Main Menu\n";

                            header('Content-type: text/plain');
                            echo $response;
                        }
                        else
                        {
                            if ($textArray[2] == $counter)
                            {
                                if (!$textArray[3])
                                {
                                    $response = "CON Input the account number:\n";
                                    $response .= "\n 0. Back to Main Menu\n";
                                    echo $response;
                                }
                                else
                                {
                                    $stmt = $db->query("SELECT *, SUBSTRING_INDEX(SUBSTRING_INDEX(`Customer Name`, ' ', 1), ' ', -1) AS FirstName,
                                    TRIM( SUBSTR(`Customer Name`, LOCATE(' ', `Customer Name`)) ) AS LastName,".$readCapacity." as capacity FROM customers WHERE `Correlation ID` LIKE '%".$textArray[3]."%' LIMIT 1");
                                    $stmt->execute();
                                        
                                    $accountAvailable = $stmt->fetch(PDO::FETCH_ASSOC);

                                    if (($key = array_search($accountAvailable['capacity'], $offered_package)) !== false) {
                                        unset($offered_package[$key]);
                                    }
                                    $i = 1;
                                    foreach($offered_package as $mbps)
                                    {
                                        $notmypackage[$i++] = $mbps;
                                    }

                                    if (!$textArray[4])
                                    {
                                        if ($accountAvailable)
                                        {
                                            $response = "CON Change plan of the account no.".$accountAvailable['Correlation ID']." belonging to ";
                                            $response .= $accountAvailable['FirstName']." ".$accountAvailable['LastName']." from ".$accountAvailable['capacity']." to:\n";
                                            foreach($notmypackage as $key=>$value)
                                            {
                                                $response .=  " " . $key . ". " . $value . "\n";
                                            }
                                            $response .= "\n 0. Back to Main Menu\n";
                                            echo $response;
                                        }
                                        else
                                        {
                                            $response = "END The specified account could not be found in our records.";
                                            echo $response;
                                        }                                
                                    }
                                    else
                                    {
                                        if (!$textArray[5])
                                        {
                                            $response = "CON Change Plan for account no.".$accountAvailable['Correlation ID']." from ";
                                            $response .= $accountAvailable['capacity']." to ".$notmypackage[$textArray[4]].":\n";
                                            $response .= " 1. Yes\n";
                                            $response .= " 2. No\n";
                                            $response .= "\n 0. Back to Main Menu\n";

                                            echo $response;
                                        }
                                        else
                                        {
                                            if ($textArray[5] == "1")
                                            {
                                                $stmt = $db->query("INSERT INTO `plan_change` (`Correlation ID`,`from_mbps`,`to_mbps`)
                                                    VALUES ('".$accountAvailable['Correlation ID']."', '".$accountAvailable['capacity']."', '".$notmypackage[$textArray[4]]."');");
                                                
                                                $response = "END Your details have been captured and you will get a call from us soon.";
                                                echo $response;
                                            }
                                            else
                                            {
                                                $response = "END Thank you for accessing our services.";
                                                echo $response;
                                            }
                                            
                                        }
                                    }
                                }
                                
                            }
                            elseif ($textArray[2] == "1")
                            {
                                $stmt = $db->query("SELECT *, SUBSTRING_INDEX(SUBSTRING_INDEX(`Customer Name`, ' ', 1), ' ', -1) AS FirstName,
                                TRIM( SUBSTR(`Customer Name`, LOCATE(' ', `Customer Name`)) ) AS LastName,".$readCapacity." as capacity FROM customers WHERE `Correlation ID` LIKE '%".$userAvailable['Correlation ID']."%' LIMIT 1");
                                $stmt->execute();
                                        
                                $userAvailable = $stmt->fetch(PDO::FETCH_ASSOC);

                                if (($key = array_search($userAvailable['capacity'], $offered_package)) !== false) {
                                    unset($offered_package[$key]);
                                }
                                $i = 1;
                                foreach($offered_package as $mbps)
                                {
                                    $notmypackage[$i++] = $mbps;
                                }

                                if (!$textArray[3])
                                {
                                    $response = "CON Change Plan of the account no.".$userAvailable['Correlation ID']." belonging to ";
                                    $response .= $userAvailable['FirstName']." ".$userAvailable['LastName']." from ".$userAvailable['capacity']." to:\n";
                                    foreach($notmypackage as $key=>$value)
                                    {
                                        $response .=  " " . $key . ". " . $value . "\n";
                                    }
                                    $response .= "\n 0. Back to Main Menu\n";
                                    echo $response;
                                }
                                else
                                {
                                    if (!$textArray[4])
                                    {
                                        $response = "CON Change Plan for account no.".$userAvailable['Correlation ID']." from ";
                                        $response .= $userAvailable['capacity']." to ".$notmypackage[$textArray[3]].":\n";
                                        $response .= " 1. Yes\n";
                                        $response .= " 2. No\n";
                                        $response .= "\n 0. Back to Main Menu\n";

                                        echo $response;
                                    }
                                    else
                                    {
                                        if ($textArray[4] == "1")
                                        {
                                            $stmt = $db->query("INSERT INTO `plan_change` (`Correlation ID`,`from_mbps`,`to_mbps`)
                                                VALUES ('".$userAvailable['Correlation ID']."', '".$userAvailable['capacity']."', '".$notmypackage[$textArray[3]]."');");
                                        
                                            $response = "END Your details have been captured and you will get a call from us soon.";
                                            echo $response;
                                        }
                                        else
                                        {
                                            $response = "END Thank you for accessing our services.";
                                            echo $response;
                                        }
                                        
                                    }
                                }
                            }
                        }
                    }
                }
                break;

            case "5":
                if (!$textArray[1])
                {
                    $response = "CON What would you like to know:\n";
                    foreach($questions as $key=>$value)
                    {
                        $response .=  " " . $key . ". " . $value['question'] . "\n";
                    }
                    $response .= " ".strval(sizeof($questions)+1).". Speak with one of us\n";						
                    $response .= "\n 0. Back to Main Menu\n";
                    
                    // Print the response onto the page so that our gateway can read it
                    header('Content-type: text/plain');
                    echo $response;
                }
                else
                {
                    if ($textArray[1] <= sizeof($questions))
                    {
                        $response = "END ".$questions[$textArray[1]]['answer']."\n";
                        echo $response;
                    }
                    if ($textArray[1] == sizeof($questions)+1)
                    {
                        if (!$textArray[2])
                        {
                            $response = "CON Would you like to speak with one of us?\n";
                            $response .= " 1. Yes\n";
                            $response .= " 2. No\n";
                            $response .= "\n 0. Back to Main Menu\n";
                            echo $response;
                        }
                        else
                        {
                            if ($textArray[2] == "1")
                            {
                                if ($userAvailable)
                                {
                                    $stmt = $db->query("INSERT INTO `chat` (`Customer ID`,`FirstName`,`LastName`,`Contact Number`)
                                        VALUES ('".$userAvailable['Customer ID']."', '".$userAvailable['FirstName']."','".$userAvailable['LastName']."', '".$phoneNumber."');");

                                    $response = "END Our representative will reach out to you shortly.";
                                    echo $response;
                                }
                                else
                                {
                                    if (!$textArray[3])
                                    {
                                        $response = "CON Input your full name.\n";
                                        $response .= "\n 0. Back to Main Menu\n";
                                        echo $response;
                                    }
                                    else
                                    {
                                        $fullName = explode(" ", $textArray[3], 2);
                                        $stmt = $db->query("INSERT INTO `chat` (`FirstName`,`LastName`,`Contact Number`)
                                            VALUES ('".$fullName[0]."','".$fullName[1]."', '".$phoneNumber."');");

                                        $response = "END Our representative will reach out to you shortly.";
                                        echo $response;
                                    }  
                                }
                            }
                            else
                            {
                                $response = "END Thank you for accessing our services.";
                                echo $response;
                            }
                        } 
                    }  
                }
                break;

            default:
                $response = "END Invalid Choice";
                echo $response;
                break;
                           
        }
    }
}
?>
