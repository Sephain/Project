<?php 
    require_once('../php/connect-main.php');
    $service_id = $_POST['service'];
    $temp = mysqli_fetch_assoc(mysqli_query($connect_main, "SELECT
	`service_list`.`date` as date
    FROM
    `service_list`
    INNER JOIN `service_provision` ON `service_provision`.`service_list_id`=`service_list`.`id`
    WHERE `service_provision`.`id`=$service_id")); 
    $date=$temp['date'];

    $q_text = "INSERT INTO `waste_list` (`service_id`, `date`) 
    VALUES ('$service_id', '$date')";
    mysqli_query($connect_main, $q_text) or die(mysqli_error($connect_main));
    header('Location: ../waste_list.php');
?> 