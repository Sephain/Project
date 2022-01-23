<?php
    session_start();
    require_once('php/connect.php');
    if (!$_SESSION['user']) { header('Location: ../index.php'); }
    require_once('php/connect-main.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="assets/pictures/icon/icon.ico" rel="icon" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles/stl.css">   
    
    <title>Главная</title>
</head>
<body>
    <?php 
        switch($_SESSION['user']['position']){
            case 1: // администратор
                include('navbars/engineer.php');
                include('mainpages/engineer-main.php');
                break;

            case 2: // главный инженер
                include('navbars/engineer.php');
                include('mainpages/engineer-main.php');
                break;
            
            case 3: // кладовщик
                include('navbars/storekeeper.php');
                include('mainpages/storekeeper-main.php');
                break;

            case 4: // кассир
                include('navbars/cashier.php');
                include('mainpages/cashier-main.php');
                break;

            case 5: // бухгалтер
                include('navbars/accountant.php');
                include('mainpages/accountant-main.php');
                break;

    }?>



</body>
</html>