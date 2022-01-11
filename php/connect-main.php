<?php
    $connect_main = mysqli_connect('localhost', 'root', '', 'photo');

    if (!$connect_main){
        die('Error connect to Database');
    }
?>