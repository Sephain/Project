<?php
    $connect_main = mysqli_connect('127.0.0.1', 'root', '', 'photo');

    if (!$connect_main){
        die('Error connect to Database');
    }
?>