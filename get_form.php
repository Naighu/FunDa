<?php

    session_start();
    require_once "db.php";
    require_once "helper.php";
    if(!is_authenticated()){
        $jsonstr = array(success => false,message => "Login to view the content");
        echo json_encode($jsonstr);
        exit(0);
    }
    $email = $_SESSION["email"];
    $id = $_POST["id"];
    $sql = "SELECT * FROM forms WHERE id=$id and email ='$email';";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $forms = json_decode($row["form_data"]);        
        $jsonstr = array(success => true,message => "Form retervied successfully","data"=>  $forms);
        echo json_encode($jsonstr);
    }
?>