<?php 
    session_start();
    require_once('../php/connect-main.php');
    require_once('../php/connect.php');
    $name = $_POST['Name'];
    $l_name = $_POST['Last_name'];
    $m_name = $_POST['Middle_name'];
    $email = $_POST['email'];
    $adress = $_POST['Adress'];
    $contact = $_POST['Contacts'];
    $position = $_POST['Position'];
    $salary = $_POST['Salary'];

    $q_text = "INSERT INTO `employee` (`first_name`, `last_name`, `middle_name`, `adress`, `contacts`, `position`, `salary`) VALUES ('$name', '$l_name', '$m_name', '$adress', '$contact', '$position', '$salary')";
    mysqli_query($connect_main, $q_text) or die(mysqli_error($connect_main));

    if ($position <= 5){
        $password = creates_pass(8);
        $_SESSION['login'] = $email;
        $_SESSION['password'] = $password;
        $password = md5($password);
        // print_r($_SESSION);
        $id_text = "SELECT `id` AS id FROM `employee` ORDER BY `id` DESC LIMIT 1";
        // print_r($_POST);
        $select_q = mysqli_fetch_assoc(mysqli_query($connect_main, $id_text));
        $select_id = $select_q['id'];
    
        $user_text = "INSERT INTO `users` (`employee_id`, `position`, `email`, `password`) VALUES ('$select_id', '$position', '$email', '$password')";
        mysqli_query($connect, $user_text) or die(mysqli_error($connect));
        
    
        header('Location: ../registration.php');
    }
    else{
        header('Location: ../employee.php');
    }
    
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
?> 