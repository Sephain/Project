<!-- ОКНО АВТОРИЗАЦИИ -->
<?php 
    session_start();
    if ($_SESSION['user']) { header('Location: ../employee.php'); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Логин</title>
    <link href="assets/pictures/icon/icon.ico" rel="icon" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles/main.css">
    <script src="scripts/ShowPassword.js"></script>
</head>
<body>
    <section>
        <div class="login-window">
            <h3 class="center-text">Войти</h3>
    
            <form action="php/auth.php" method="POST" id="my_form">
                <div class="mb-3">
                    <label for="one" class="form-label">Логин</label>
                    <input name="login" type="text" class="form-control" id="one" aria-describedby="emailHelp" placeholder="Введите пароль">
                    
                </div>
                <div class="mb-3">
                    <label for="two" class="form-label">Пароль</label>
                    <div class="input-group">
                        <input name="password" type="password" class="form-control" placeholder="Пароль" id="two">
                        <span class="input-group-text" id="basic-addon2"><a href="#" class="password-control" onclick="return show_hide_password(this);"></a></span>
                    </div>
                    
                </div>
                <div id="accountHelp" class="form-text">Если у вас нет аккаунта или вы потеряли к нему доступ, пожалуйста, обратитесь к администратору.</div>
                
                <div class="center-text">
                    <button type="submit" class="btn btn-primary" id="btn_add">Войти</button>
                </div>
                <div class="warn text-center" id="warning"></div>
            </form>
        </div>  
    </section>
    <script src="scripts/login_fields_check.js"></script>
</body>
</html>