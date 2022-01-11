<?php 
    require_once('../php/connect-main.php');

    $Employee = $_POST['Employee'];
    $Client = $_POST['Client'];
    $Date = $_POST['Date'];

    $q_text = "INSERT INTO `service_list` (`employee_id`, `client_id`, `date`) 
    VALUES ('$Employee', '$Client', '$Date')";
    mysqli_query($connect_main, $q_text) or die(mysqli_error($connect_main));
    header('Location: ../service.php');
?> 