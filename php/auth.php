<?php
    session_start();
    require_once 'connect.php';

    $login = $_POST['login'];
    $password = $_POST['password'];
    $password = md5($password);

    $check = mysqli_query($connect, "SELECT * FROM `users` WHERE `email` = '$login' AND `password` = '$password'"); // search user with specified login (email at the moment) and password

    $user = mysqli_fetch_assoc($check);

    

    if (mysqli_num_rows($check) > 0) { // if rows are more then zero then we found user 
        $_SESSION['user'] = [
            "position" => $user['position']
        ];
        header('Location: ../mainpage.php');
        
    }
    else {
        header('Location: ../index.php');
    }
?>