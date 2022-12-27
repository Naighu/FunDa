<?php
    session_start();
    require_once "db.php";

   if($_SERVER["REQUEST_METHOD"] == "POST"){
    $form_data = json_encode($_POST["form_data"]);
   
    $email = $_SESSION["email"];

    sleep(1);
    if($_POST["form_id"] != ""){
        $id = $_POST["form_id"];
        $sql = "UPDATE forms SET form_data= '${form_data}',updated=now() WHERE email='$email' and id=$id";
    }else{
        $sql = "INSERT INTO forms (email,form_data,created,updated) VALUES('${email}','${form_data}', now(),now())";
    }
    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        $jsonstr = array("success" => TRUE, "message" => "Form saved", "data" => array("id"=> $last_id));
        echo json_encode($jsonstr);
    }else{
        
        $jsonstr = array("success" => FALSE, "message" => "Could not able to create forms","error" => $conn->error, "data" => array());
        echo json_encode($jsonstr);
    }

   }
?>