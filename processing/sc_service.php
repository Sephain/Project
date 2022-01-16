<?php
    require_once('../php/connect-main.php');
    print_r($_POST);

    $service = $_POST['service'];
    $count = $_POST['count'];
    $price = $_POST['price'];
    $service_list_id = $_POST['service_list_id'];

    $q_text = "INSERT INTO `service_provision` (`service_id`, `count`, `price`, `service_list_id`) VALUES ('$service', '$count', '$price', '$service_list_id')";
    mysqli_query($connect_main, $q_text);
    header("Location: ../service_content.php?service_list_id=$service_list_id");
?>