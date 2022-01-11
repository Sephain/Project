
<?php 
    session_start();
    if (!$_SESSION['user']) { header('Location: ../index.php'); }
    if (isset($_SESSION['login'])){$login = $_SESSION['login'];}
    if (isset($_SESSION['password'])){$password = $_SESSION['password'];}
    unset($_SESSION['password']); unset($_SESSION['login']);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/pictures/icon/icon.ico" rel="icon" type="image/png">
    <title>Регистрация</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles/main.css">
</head>
<body>
    <?php include('navbars/engineer.php');?>
        <div class="container justify-content-center block">
            <p class="text-center form-text"> Аккаунт успешно создан! Запишите полученные данные и передайте их сотруднику. Не обновляйте страницу до тех пор - данные исчезнут.</p>
            
            <p class="text-center">Логин (email): <?= $login?></p>
            <p class="text-center">Пароль: <?= $password?></p>
            
            
                <form action="employee.php" class="text-center"> <button class="btn btn-primary btn-reg" type="submit">Вернуться</button> </form>
            
        </div>


        

        
</body>
</html>