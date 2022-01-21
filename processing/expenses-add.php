<?php
    session_start();
    require_once('../php/connect-main.php');

    $mat = $_POST['material'];
    $count = $_POST['count'];
    $employee = $_POST['employee'];
    $date = $_POST['date'];
    $purpose = $_POST['purpose'];

    mysqli_begin_transaction($connect_main);
    try{
        $c=mysqli_fetch_assoc(mysqli_query($connect_main, "SELECT `count` FROM `stock_balances` WHERE `stock_balances`.`material_id`='$mat'"));
        if ($c['count'] < $count) throw new mysqli_sql_exception();
        $update_text="UPDATE `stock_balances` SET `count`=`count`-'$count' WHERE `stock_balances`.`material_id`='$mat'";
        mysqli_query($connect_main, $update_text); 

        $add_text = "INSERT INTO `expenses` (`material_id`, `count`, `employee_id`, `date`, `purpose`) VALUES ('$mat', '$count', '$employee', '$date', '$purpose')";
        mysqli_query($connect_main, $add_text);
        
        mysqli_commit($connect_main);
        header('Location: ../expenses.php');
    }
    catch(mysqli_sql_exception $exeption){ 
        $_SESSION['error'] = TRUE;
        mysqli_rollback($connect_main);
        header('Location: ../expenses.php');
    }
?>