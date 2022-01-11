<?php
    session_start();
    require_once 'connect.php'; // connect to db

    $name = $_POST['name'];
    $last_name = $_POST['last_name'];
    $position = $_POST['position'];
    $email = $_POST['email'];
    echo($name);

    if ($name == '' || $last_name == '' || $position == '' || $email == '') {
        echo('Заполнены не все поля');
        echo('<br>');
        echo('<a href="../index.php" class="form-text">Войти</a>');
    }
    else{
        
        $password = 'qwerty123'; // random pass
        $password = md5($password); // hash

        // write all scenarious !!

        mysqli_query($connect, // query for inserting new user
        "INSERT INTO 
            `users`(`id`, `name`, `last_name`, `position`, `email`, `password`) 
        VALUES 
            (NULL,'$name','$last_name','$position','$email','$password')
        ");
      
        function creates_pass($length){ // generate random password
            $password = '';
            $arr = array(
                'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 
                'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 
                'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 
                'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 
                '1', '2', '3', '4', '5', '6', '7', '8', '9', '0'
            );

            for ($i = 0; $i < $length; $i++) {
                $password .= $arr[random_int(0, count($arr) - 1)];
            }
            return $password;
        }
        
        header('Location: ../main.php'); // go to page
    }
?>