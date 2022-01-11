<?php
    $connect = mysqli_connect('localhost', 'root', '', 'accounts');

    if (!$connect){
        die('Error connect to Database');
    }
?>