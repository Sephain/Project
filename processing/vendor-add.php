<?php 
    require_once('../php/connect-main.php');

    $name = $_POST['Name'];
    $adress = $_POST['Adress'];
    $contact = $_POST['Contacts'];
    $q_text = "INSERT INTO `vendor` (`name`, `adress`, `contacts`) VALUES ('$name', '$adress', '$contact')";
    print_r($q_text);
    mysqli_query($connect_main, $q_text) or die(mysqli_error($connect_main));
    header('Location: ../vendor.php');
?> 