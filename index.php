<?php
    require_once "helper.php";
    session_start();
    $test = 123;
    if(is_authenticated()) {
        
     
        header("Location: /web-programming/homepage.php");
        exit;
        
    
    }else{
        echo file_get_contents("registration.html");
    }
    
?>