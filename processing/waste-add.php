<?php
    require_once('../php/connect-main.php');
    session_start();

    $name = $_POST['name'];
    $amount = $_POST['count'];
    $measure = $_POST['measure'];
    $waste_list_id = $_POST['waste_list_id'];
    print_r($_POST);
    mysqli_query($connect_main, "INSERT INTO `waste` (`name`, `amount`, `measure`, `waste_list_id`) VALUES ('$name', '$amount', '$measure', '$waste_list_id')") or die(mysqli_error($connect_main));
    header("Location: ../waste_content.php?waste_list_id=$waste_list_id");
?>