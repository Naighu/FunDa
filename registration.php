<?php 
require_once "db.php";
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $type = $_POST["type"];
    if($type ==='login'){
        sleep(1);
        $sql = "SELECT name,email FROM users WHERE email='" . $email . "' and password = '".$password. "'";
        
        
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION["is_logged"] = time();
            $_SESSION["email"] = $row['email'];
            $_SESSION["name"] = $row['name'];

            $jsonstr = array("success" => TRUE, "message" => "Logged in successfully", "data" => array("name"=> $row['name'],"email"=> $row['email']));
            echo json_encode($jsonstr);
        }else{
            $jsonstr = array("success" => FALSE, "message" => "Invalid email or password", "data" => array());
            echo json_encode($jsonstr);
        }
        
        
    }elseif($type === 'signup'){
        sleep(1);
        $name = $_POST["name"];
    
        $sql = "INSERT INTO users(email,password,name) VALUES ('".$email."','".$password."','".$name."')";

        if ($conn->query($sql)) {
            $jsonstr = array("success" => TRUE, "message" => "Signed up successfully","data"=>array());
            echo json_encode($jsonstr);
          } else {
            $jsonstr = array("success" => FALSE, "message" => "Something went wrong", "data" => array());
            echo json_encode($jsonstr);
          }
    }

    $conn->close();
}

?>