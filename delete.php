<?php
    session_start();
    require_once "db.php";
    require_once "helper.php";

    if(!is_authenticated()) {
        $jsonstr = array(success => false,message => "Login to delete the content");
        echo json_encode($jsonstr);
        exit(0);
    }
    $email = $_SESSION["email"];
    $form_id = $_POST["form_id"];
    $sql = "DELETE FROM forms WHERE id=$form_id and email='$email';";

    if($conn->query($sql) == true) {
        $jsonstr = array(success => true,message => "Form deleted successfully");
        
    }else{
        $jsonstr = array(success => false,message => "Something went wrong",error => $conn->error);
        
    }
    echo json_encode($jsonstr);


?>