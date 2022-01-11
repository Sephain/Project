<?php
    require_once('../php/connect-main.php');

    $service = $_POST['Service'];
    $count = $_POST['Count'];
    $price = $_POST['Price'];
    $service_list_id = $_POST['service_list_id'];
    print_r($_POST);
    $q_text = "INSERT INTO `service_provision` (`service_id`, `count`, `price`, `service_list_id`) VALUES ('$service', '$count', '$price', '$service_list_id')" or die(mysqli_error(($connect_main)));
    mysqli_query($connect_main, $q_text);
    header("Location: ../service_content.php?service_list_id=$service_list_id");
?>