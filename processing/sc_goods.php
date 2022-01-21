<?php
    session_start();
    require_once('../php/connect-main.php');
    
    $goods = $_POST['goods']; // айдишник материала в таблице материалы
    $count = $_POST['count'];
    $price = $_POST['price'];
    $service_list_id = $_POST['service_list_id'];

    $sdata = mysqli_fetch_assoc(mysqli_query($connect_main, "SELECT * FROM `service_list` WHERE `id`='$service_list_id'"));
    $emp = $sdata['employee_id'];
    $date = $sdata['date'];

    
    print_r($_POST);
    print_r($date);
    print_r($emp);
    mysqli_begin_transaction($connect_main);
    try {
        $data = mysqli_fetch_assoc(mysqli_query($connect_main, "SELECT * FROM `stock_balances` INNER JOIN `materials` ON `materials`.`id`=`stock_balances`.`material_id` WHERE `stock_balances`.`material_id`='$goods'"));
        $stock_count = $data['count'];


        if ($count > $stock_count) throw new mysqli_sql_exception();
        else{
            mysqli_query($connect_main, "INSERT INTO `service_provision` (`service_id`, `count`, `price`, `service_list_id`) VALUES ('$goods', '$count', '$price', '$service_list_id')") or die(mysqli_error($connect_main));
            mysqli_query($connect_main, "INSERT INTO `expenses` (`material_id`, `count`, `employee_id`, `date`, `service_id`) VALUES ('$goods', '$count', '$emp', '$date', '$service_list_id')") or die(mysqli_error($connect_main));
            mysqli_query($connect_main, "UPDATE `stock_balances` SET `count`=`count`-'$count' WHERE `material_id`='$goods'") or die(mysqli_error($connect_main)); 
            mysqli_commit($connect_main);
            header("Location: ../service_content.php?service_list_id=$service_list_id");
        }
    }
    catch(mysqli_sql_exception $exeption){ 
        $_SESSION['error'] = TRUE;
        mysqli_rollback($connect_main);
        header("Location: ../service_content.php?service_list_id=$service_list_id");
    }
?>