<?php
    require_once('../php/connect-main.php');

    $number = $_POST['number'];
    $date = $_POST['date'];
    $r_date = $_POST['r_date'];
    $vendor = $_POST['vendor'];
    $employee = $_POST['emp'];
    $q_text = "INSERT INTO `orders` (`number`, `vendor_id`, `date`, `receipt_date`, `employee_id`) VALUES ('$number', '$vendor', '$date', '$r_date', '$employee')";
    mysqli_query($connect_main, $q_text) or die(mysqli_error($connect_main));
    header('Location: ../orders.php');
?>