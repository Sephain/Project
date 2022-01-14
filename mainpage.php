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
                break;

            case 2: // главный инженер
                include('navbars/engineer.php');
                break;
            
            case 3: // кладовщик
                include('navbars/storekeeper.php');
                break;

            case 4: // кассир
                include('navbars/cashier.php');
                break;

            case 5: // бухгалтер
                include('navbars/accountant.php');
                break;

    }?>

    <div class="container-md mt-4 mb-4">
        <h3 class="mb-4">Добро пожаловать!</h3>

        <i>Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt labore, quas laborum mollitia fugiat soluta non deserunt dolorum, ipsa dignissimos iure, magni optio voluptas doloremque magnam saepe minus delectus? At?</i>
        <hr>
        <div class="container justify-content-center">
            <div class="row mt-4 mb-4">
                <div class="col-md-4">
                    <div class="card my-card" style="width: 20rem; margin-left:auto; margin-right:auto">       
                        <div class="card-header"><img src="assets/pictures/any/user.png" alt="" width="20" height="20" class="d-inline-block"><b>  Управление учетными записями</b></div>              
                        <div class="card-body">
                        
                            <p class="card-text">Здесь представлен список всех сотрудников, работающих на предприятии, а также управление учетными записями</p>
                            <a href="employee.php" class="btn btn-primary">Перейти</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card my-card" style="width: 20rem; margin-left:auto; margin-right:auto ">    
                    <div class="card-header"><img src="assets/pictures/any/service-blank.png" alt="" width="20" height="20" class="d-inline-block"><b>  Бланки оказания услуг</b></div>                 
                        <div class="card-body">
                            <p class="card-text">Здесь находятся все бланки оказанных клиентам услуг, а также бланки проданных товаров</p>
                            <a href="service.php" class="btn btn-primary">Перейти</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card my-card" style="width: 20rem; margin-left:auto; margin-right:auto">   
                        <div class="card-header"><img src="assets/pictures/any/vendor.png" alt="" width="20" height="20" class="d-inline-block"><b>  Список поставщиков</b></div>               
                        <div class="card-body">
                            <p class="card-text">Раздел, в котором представлен список всех поставщиков, с которыми сотрудничает фотоцентр</p>
                            <a href="vendor.php" class="btn btn-primary">Перейти</a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mt-4 mb-4">
                <div class="col-md-4">
                    <div class="card my-card" style="width: 20rem; margin-left:auto; margin-right:auto">       
                        <div class="card-header"><img src="assets/pictures/any/material.png" alt="" width="20" height="20" class="d-inline-block"><b>  Учет материалов</b></div>              
                        <div class="card-body">
                        
                            <p class="card-text">Здесь представлены все приходные накладные и бланки заказов, а также материалы, которые имеются на складе в данный момент</p>
                            <a href="stock-balances.php" class="btn btn-primary">Перейти</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card my-card" style="width: 20rem; margin-left:auto; margin-right:auto ">    
                    <div class="card-header"><img src="assets/pictures/any/waste.png" alt="" width="20" height="20" class="d-inline-block"><b>  Утилизация отходов</b></div>                 
                        <div class="card-body">
                            <p class="card-text">Здесь находятся все списки токсичных отходов, которые были утилизированы и переданы соответствующим органам</p>
                            <a href="waste_list.php" class="btn btn-primary">Перейти</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card my-card" style="width: 20rem; margin-left:auto; margin-right:auto">   
                        <div class="card-header"><img src="assets/pictures/any/account.png" alt="" width="20" height="20" class="d-inline-block"><b>  Бухгалтерия</b></div>               
                        <div class="card-body">
                            <p class="card-text">Здесь находятся доходно-расходные отчетности и иные документы, описывающие итоги деятельности фотоцентра</p>
                            <a href="reports.php" class="btn btn-primary">Перейти</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- <div class="container my-container">
        <div class="row row-cols-1 row-cols-md-3 g-3">
            <div class="col">
                <div class="card " style="width: 20rem; margin-left:auto; margin-right:auto">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                </div>
                </div>
            </div>
            <div class="col">
                <div class="card" style="width: 20rem; margin-left:auto; margin-right:auto">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                </div>
                </div>
            </div>
            <div class="col">
                <div class="card" style="width: 20rem; margin-left:auto; margin-right:auto">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content.</p>
                </div>
                </div>
            </div>

        </div>

        <div class="row row-cols-1 row-cols-md-3 g-3 mt-4">
            <div class="col">
                <div class="card">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content.</p>
                </div>
                </div>
            </div>

        </div>
    </div> -->
</body>
</html>