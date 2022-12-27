<?php
    require_once "db.php";
    $sql = "CREATE TABLE users(id int PRIMARY kEY AUTO_INCREMENT,email varchar(100) unique,name varchar(50) NOT NULL, password varchar(20) NOT NULL);";
    if ($conn->query($sql)) {
        $sql = "CREATE TABLE forms(id int PRIMARY kEY AUTO_INCREMENT,form_data json,created timestamp , updated timestamp,email varchar(100) REFERENCES users(email));";
        if ($conn->query($sql)) {
            echo "Initialized successfully";
        }else{
            echo $conn->error;
        }
    }else{
        echo $conn->error;
    }


?>