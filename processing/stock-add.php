<?php
    require_once('../php/connect-main.php');

    $mat = $_POST['material'];
    $count = $_POST['count'];
    $q_text = "INSERT INTO `stock_balances` (`material_id`, `count`) VALUES ('$mat', '$count')";
    mysqli_query($connect_main, $q_text);
    header('Location: ../stock-balances.php');
?>