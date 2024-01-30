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
    $stmt = $db->query("SELECT * FROM customers WHERE (MobileNumber LIKE '".$phoneNumber."' OR MobileNumber LIKE substring('".$phoneNumber."', -9)) LIMIT 1");
    $stmt->execute();
    
    $userAvailable = $stmt->fetch(PDO::FETCH_ASSOC);
    $firstName = ($userAvailable) ? $userAvailable['FirstName'] : '';

    if (!$textArray[0] || $textArray[sizeof($textArray)-1]=="0")
    {
        $response = "CON Welcome " . $firstName;
        $response .= "\nSelect an option.\n";
        $response .= " 1. Get Home Internet\n";
        $response .= " 2. Pay\n";
        $response .= " 3. Report a Case\n";
        $response .= " 4. Change Plan\n";						
        $response .= " 5. Speak with one of us.\n";
                                                                                                                                                    

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
                $package = array(
                "1"=>"5Mbps (KES 2,395)",
                "2"=>"10Mbps (KES 3,354)",
                "3"=>"25Mbps (KES 4,982)",
                "4"=>"50Mbps (KES 6,554)",
                "5"=>"100Mbps (KES 11,499)");

                $location = array(
                "1"=>"Embakasi",
                "2"=>"Runda",
                "3"=>"Syokimau");

                if (!$textArray[1])
                {
                    $response = "CON Select a package.\n";
                    foreach($package as $key=>$value)
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
                    $capacity = substr($package[$textArray[1]], 0, strpos($package[$textArray[1]], " (KES"));
                    $response = "CON Get Home Internet: ";
                    $response .= $capacity ."\n";

                    if (!$textArray[2])
                    {
                        $response = "CON Address Location.\n";
                        foreach($location as $key=>$value)
                        {
                            $response .=  " " . $key . ". " . $value . "\n";
                        }

                        header('Content-type: text/plain');
                        echo $response;
                    }
                    else
                    {
                        if (!$textArray[3] && !$userAvailable)
                        {
                            $response .= "Provide your full name.\n";
                            $response .= "\n 0. Back to Main Menu\n";

                            header('Content-type: text/plain');
                            echo $response;
                        }
                        else
                        {
                            if (!$userAvailable)
                            {
                                //Record Customer Details
                                $fullName = explode(" ", $textArray[3], 2);
                                $stmt = $db->query("INSERT INTO `customer_details` (`FirstName`,`LastName`,`MobileNumber`)
                                VALUES ('".$fullName[0]."', '".$fullName[1]."', '" .$phoneNumber."');");
                                //$stmt->execute();

                                $stmt = $db->query("SELECT * FROM customer_details WHERE (MobileNumber LIKE '".$phoneNumber."' OR MobileNumber LIKE substring('".$phoneNumber."', -9)) LIMIT 1");
                                $stmt->execute();
                                
                                $userAvailable = $stmt->fetch(PDO::FETCH_ASSOC);
                            }

                            //Record Get Internet Details
                            $stmt = $db->query("INSERT INTO `get_internet` (`CustomerID`,`Location`,`Capacity`)
                            VALUES ('".$userAvailable['CustomerID']."', '".$location[$textArray[2]]."', '" .$capacity."');");
                            //$stmt->execute();

                            $response = "END Thank you ";
                            $response .= $userAvailable['FirstName'].".\n";
                            $response .= "Your details have been captured.\n";
                            $response .= "You will get a call from us to schedule the date of installation.\n";

                            header('Content-type: text/plain');
                            echo $response;
                        }
                    }
                    
                }
                break;

            case "2":
                if (!$textArray[1])
                {
                    $response = "CON Pay for:\n";
                    $response .= " 1. Other Account\n";
                    if ($userAvailable)
                    {
                        $response .= " 2. My Account\n";
                    }
                    $response .= "\n 0. Back to Main Menu\n";

                    header('Content-type: text/plain');
                    echo $response;
                }
                else
                {
                    if ($textArray[1] == "1")
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
                                $stmt = $db->query("SELECT * FROM customers WHERE haik_Ref LIKE '%".$textArray[2]."%' LIMIT 1");
                                $stmt->execute();
                                
                                $accountAvailable = $stmt->fetch(PDO::FETCH_ASSOC);

                                if ($accountAvailable)
                                {
                                    $response = "CON Confirm payment of the account no.".$accountAvailable['haik_Ref']."\n belonging to ";
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
                    elseif ($textArray[1] == "2")
                    {
                        if (!$textArray[2])
                        {
                            $response = "CON Confirm payment of the account no.".$userAvailable['haik_Ref']."\n belonging to ";
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
                        if (!$textArray[2])
                        {
                            $response = "CON Report ".$cases[$textArray[1]]." for:\n";
                            $response .= " 1. Other Account\n";
                            if ($userAvailable)
                            {
                                $response .= " 2. My Account\n";
                            }
                            $response .= "\n 0. Back to Main Menu\n";

                            header('Content-type: text/plain');
                            echo $response;
                        }
                        else
                        {
                            if ($textArray[2] == "1")
                            {
                                if (!$textArray[3])
                                {
                                    $response = "CON Input the account number:\n";
                                    $response .= "\n 0. Back to Main Menu\n";
                                    echo $response;
                                }
                                else
                                {
                                    $stmt = $db->query("SELECT * FROM customers WHERE haik_Ref LIKE '%".$textArray[3]."%' LIMIT 1");
                                    $stmt->execute();
                                        
                                    $accountAvailable = $stmt->fetch(PDO::FETCH_ASSOC);

                                    if (!$textArray[4])
                                    {
                                        if ($accountAvailable)
                                        {
                                            $response = "CON Report ".$cases[$textArray[1]]." for the account no.".$accountAvailable['haik_Ref']."\n belonging to ";
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
                                            $stmt = $db->query("INSERT INTO `cases_reported` (`haik_Ref`,`reported_case`)
                                                VALUES ('".$accountAvailable['haik_Ref']."', '".$cases[$textArray[1]]."');");

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
                            elseif ($textArray[2] == "2")
                            {
                                if (!$textArray[3])
                                {
                                    $response = "CON Report ".$cases[$textArray[1]]." for the account no.".$userAvailable['haik_Ref']."\n belonging to ";
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
                                        $stmt = $db->query("INSERT INTO `cases_reported` (`haik_Ref`,`reported_case`)
                                        VALUES ('".$userAvailable['haik_Ref']."', '".$cases[$textArray[1]]."');");

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
                $offered_package = array("5Mbps","10Mbps","25Mbps","50Mbps","100Mbps");

                $readCapacity = "IF(`PrismPackageName` LIKE \"%100%MBPS%\", '100Mbps',
                                    IF(`PrismPackageName` LIKE \"%60%MBPS%\", '60Mbps',
										IF(`PrismPackageName` LIKE \"%50%MBPS%\", '50Mbps',
                                            IF(`PrismPackageName` LIKE \"%40%MBPS%\", '40Mbps',
											    IF(`PrismPackageName` LIKE \"%25%MBPS%\", '25Mbps',
												    IF(`PrismPackageName` LIKE \"%10%MBPS%\", '10Mbps',
													    IF(`PrismPackageName` LIKE \"%5%MBPS%\", '5Mbps',
                                                            IF(`PrismPackageName` LIKE \"%3%MBPS%\", '3Mbps',
														        'N/A'))))))))";

                if (!$textArray[1])
                {
                    $response = "CON Change Plan for:\n";
                    $response .= " 1. Other Account\n";
                    if ($userAvailable)
                    {
                        $response .= " 2. My Account\n";
                    }
                    $response .= "\n 0. Back to Main Menu\n";

                    header('Content-type: text/plain');
                    echo $response;
                }
                else
                {
                    if ($textArray[1] == "1")
                    {
                        if (!$textArray[2])
                        {
                            $response = "CON Input the account number:\n";
                            $response .= "\n 0. Back to Main Menu\n";
                            echo $response;
                        }
                        else
                        {
                            $stmt = $db->query("SELECT *,".$readCapacity." as capacity FROM customers WHERE haik_Ref LIKE '%".$textArray[2]."%' LIMIT 1");
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

                            if (!$textArray[3])
                            {
                                if ($accountAvailable)
                                {
                                    $response = "CON Change plan of the account no.".$accountAvailable['haik_Ref']." belonging to ";
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
                                if (!$textArray[4])
                                {
                                    $response = "CON Change Plan for account no.".$accountAvailable['haik_Ref']." from ";
                                    $response .= $accountAvailable['capacity']." to ".$notmypackage[$textArray[3]].":\n";
                                    $response .= " 1. Yes\n";
                                    $response .= " 2. No\n";
                                    $response .= "\n 0. Back to Main Menu\n";

                                    echo $response;
                                }
                                else
                                {
                                    if ($textArray[4] == "1")
                                    {
                                        $stmt = $db->query("INSERT INTO `plan_change` (`haik_Ref`,`from_mbps`,`to_mbps`)
                                            VALUES ('".$accountAvailable['haik_Ref']."', '".$accountAvailable['capacity']."', '".$notmypackage[$textArray[3]]."');");
                                        
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
                    elseif ($textArray[1] == "2")
                    {
                        $stmt = $db->query("SELECT *,".$readCapacity." as capacity FROM customers WHERE haik_Ref LIKE '%".$userAvailable['haik_Ref']."%' LIMIT 1");
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

                        if (!$textArray[2])
                        {
                            $response = "CON Change Plan of the account no.".$userAvailable['haik_Ref']." belonging to ";
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
                            if (!$textArray[3])
                            {
                                $response = "CON Change Plan for account no.".$userAvailable['haik_Ref']." from ";
                                $response .= $userAvailable['capacity']." to ".$notmypackage[$textArray[2]].":\n";
                                $response .= " 1. Yes\n";
                                $response .= " 2. No\n";
                                $response .= "\n 0. Back to Main Menu\n";

                                echo $response;
                            }
                            else
                            {
                                if ($textArray[3] == "1")
                                {
                                    $stmt = $db->query("INSERT INTO `plan_change` (`haik_Ref`,`from_mbps`,`to_mbps`)
                                        VALUES ('".$userAvailable['haik_Ref']."', '".$userAvailable['capacity']."', '".$notmypackage[$textArray[2]]."');");
                                
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
                break;

            default:
                if (!$textArray[1])
                {
                    $response = "CON Would you like to speak with one of us?\n";
                    $response .= " 1. Yes\n";
                    $response .= " 2. No\n";
                    $response .= "\n 0. Back to Main Menu\n";
                    echo $response;
                }
                else
                {
                    if ($textArray[1] == "1")
                    {
                        if ($userAvailable)
                        {
                            $stmt = $db->query("INSERT INTO `chat` (`CustomerID`,`FirstName`,`LastName`,`MobileNumber`)
                                VALUES ('".$userAvailable['CustomerID']."', '".$userAvailable['FirstName']."','".$userAvailable['LastName']."', '".$phoneNumber."');");

                            $response = "END Our representative will reach out to you shortly.";
                            echo $response;
                        }
                        else
                        {
                            if (!$textArray[2])
                            {
                                $response = "CON Input your full name.\n";
                                $response .= "\n 0. Back to Main Menu\n";
                                echo $response;
                            }
                            else
                            {
                                $fullName = explode(" ", $textArray[2], 2);
                                $stmt = $db->query("INSERT INTO `chat` (`FirstName`,`LastName`,`MobileNumber`)
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
}
     

?>
