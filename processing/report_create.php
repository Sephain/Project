<?php
    session_start();
    require_once('../php/connect.php');
    if (!$_SESSION['user']) { header('Location: ../index.php'); }
    require_once('../php/connect-main.php');
            
    mysqli_begin_transaction($connect_main);
    try{
        if ($_POST['startPeriod'] == NULL or $_POST['endPeriod'] == NULL) {throw new mysqli_sql_exception('Заполните поля периода!');}
        $startPeriod = $_POST['startPeriod'];
        $endPeriod=$_POST['endPeriod'];
        // ДОХОДЫ
        // товар
        $productPrice = mysqli_query($connect_main, "SELECT
        SUM(`price` * `count`) AS price
        FROM
            `service_provision`
        INNER JOIN `service` ON `service_provision`.`service_id` = `service`.`id`
        INNER JOIN `service_list` ON `service_list`.`id`=`service_provision`.`service_list_id`
        WHERE
        `service`.`category_id` = 2 AND `service_list`.`date` >= '$startPeriod' AND `service_list`.`date` <= '$endPeriod'");
        $productPrice = mysqli_fetch_assoc($productPrice);

        // услуга
        $servicePrice = mysqli_query($connect_main, "SELECT
        SUM(`price` * `count`) AS price
        FROM
            `service_provision`
        INNER JOIN `service` ON `service_provision`.`service_id` = `service`.`id`
        INNER JOIN `service_list` ON `service_list`.`id`=`service_provision`.`service_list_id`
        WHERE
        `service`.`category_id` = 1 AND `service_list`.`date` >= '$startPeriod' AND `service_list`.`date` <= '$endPeriod'") or die(mysqli_error($connect_main));
        $servicePrice = mysqli_fetch_assoc($servicePrice);

        $totalIncome = $productPrice['price']+$servicePrice['price'];

        // РАСХОДЫ
        // заказы у поставщиков
        $orderPrice = mysqli_query($connect_main, "SELECT SUM(`count`*`price_one`) AS price FROM `orders_content` INNER JOIN `orders` ON `orders_content`.`orders_id`=`orders`.`id` WHERE `orders`.`date`>='$startPeriod' AND `orders`.`date`<='$endPeriod'");
        $orderPrice = mysqli_fetch_assoc($orderPrice);

        // заработная плата сотрудников
        $salary = mysqli_query($connect_main, "SELECT SUM(`salary`*1.3) AS salary FROM `employee`");
        $salary = mysqli_fetch_assoc($salary);

        $totalOutcome = $orderPrice['price'] + $salary['salary'];
        mysqli_commit($connect_main);
    }
    catch(mysqli_sql_exception $e){
        $_SESSION['error'] = True;
        mysqli_rollback($connect_main);
    }
    
    header("Location: ../reports.php")
?>