<?php
    require_once('../php/connect-main.php');

    $material = $_POST['material'];
    $count = $_POST['count'];
    $price = $_POST['price'];
    $order_id = $_POST['order_id'];
    $existQuery = "SELECT * 
    FROM 
    `stock_balances` 
    INNER JOIN `materials` ON `stock_balances`.`material_id`=`materials`.`id`
    WHERE `materials`.`id`='$material'";
    $exists=mysqli_num_rows(mysqli_query($connect_main, $existQuery));

    if ($exists != 0) {$update_text="UPDATE `stock_balances` SET `count`=`count`+'$count' WHERE `stock_balances`.`material_id`='$material'";}
    else {$update_text="INSERT INTO `stock_balances` (`material_id`, `count`) VALUES ('$material', '$count')";}
    mysqli_query($connect_main, $update_text);


    $q_text = "INSERT INTO `orders_content` (`material_id`, `count`, `price_one`, `orders_id`) VALUES ('$material', '$count', '$price', '$order_id')";
    mysqli_query($connect_main, $q_text) or die(mysqli_error($connect_main));
    header("Location: ../orders_content.php?order_id=$order_id");
?>