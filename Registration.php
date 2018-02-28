<?php
$username = $password = $confirm_password = $email = "";
$username_err = $password_err = $confirm_password_err = $email_err = "";
$username_taken ="";
$email_taken ="";
require_once 'config.php'; 

function validateEmail($email){
    $var = $email;
    require_once 'config.php';
    $sql = "SELECT * FROM User_Info WHERE email = '$var'";
    $result = $conn->query($sql);
    return mysql_num_rows($result) != 0;
}

function validateUser ($user){
    require_once 'config.php';
    $sql = "SELECT * FROM User_Info WHERE username = '$user'";
    $result = $conn -> query($sql);
    return ($result -> num_rows > 0) ;
}

?>

<h1>Registration Page!</h1>