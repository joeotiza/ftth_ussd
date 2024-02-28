<?php

if(isset($_GET['Area'])){
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

    //Receive the POST
    $area     =$_GET['Area'];
    $estate   =$_GET['Estate'];
    $address   =$_GET['Address'];
    $capacity   =$_GET['Capacity'];
    $name   =$_GET['Name'];
    $email   =$_GET['Email'];

    //$stmt = $db->query("INSERT INTO `whatsapp` (`Area`) VALUES ('".$area."')");

    $stmt = $db->query("INSERT INTO `whatsapp` (`Area`,`Estate`,`Address`,`Capacity`,`Name`,`Email`)
        VALUES ('".$area."', '".$estate."', '" .$address."', '" .$capacity."', '".$name."', '".$email."');");
}
?>