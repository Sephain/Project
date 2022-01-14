<!-- ТЕСТОВАЯ ВЫБОРКА С БД (ТИПО СОТРУДНИКИ) -->
<?php
    session_start();
    require_once('php/connect.php');
    if (!$_SESSION['user']) { header('Location: ../index.php'); }
    require_once('php/connect-main.php');

    //add new client
    if (isset($_POST['client-add'])) {
        $name = $_POST['name'];
        $last_name = $_POST['last_name'];
        $m_name = $_POST['middle_name'];
        $contacts = $_POST['contacts'];

        $q_text = "INSERT INTO `clients` (`first_name`, `last_name`, `middle_name`, `contacts`) VALUES ('$name', '$last_name', '$m_name', '$contacts')";
        mysqli_query($connect_main, $q_text) or die(mysqli_error($connect_main));
        header("Location: ../clients.php");
    }
    
    // query for deleting
    if (isset($_GET['del'])) {
        $id = ($_GET['del']);
        mysqli_query($connect_main, "DELETE FROM `clients` WHERE id=$id") or die(mysqli_error($connect_main));
    }

    //query for select
    if (isset($_GET['page'])) { $page = $_GET['page']; }
    else { $page = 1; }

    $recordOnPage = 10; // количество записей на странице
    $startFrom = ($page - 1) * $recordOnPage;
    $select_text = "SELECT * FROM `clients`
    ORDER BY `id`
    LIMIT $startFrom,$recordOnPage";

    $select_query = mysqli_query($connect_main, $select_text) or die(mysqli_error($connect_main));
    $new = mysqli_fetch_all($select_query);
    

    // pagination =)
    $count_query = mysqli_query($connect_main, "SELECT COUNT(*) as count FROM `clients`") or die(mysqli_error($connect_main));
    $count = mysqli_fetch_assoc($count_query)['count'];
    $pagesCount = ceil($count / $recordOnPage);


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
    <title>Клиенты</title>
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
    
            }
    ?>


    <section>
        <div class="container-md">
            <div class="mt-4 mb-4">
                <h3><p>Раздел "Клиенты"</p></h3>
                <p class="fst-italic">Здесь представлены бланки оказания услуг нашим клиентам. </p>
                <p class="fst-italic">Вы можете добавить ноывй бланк в список, либо ознакомиться и отредактировать уже существующий.</p>
                <hr>
            </div>
            <div class="mt-4 mb-4">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <a data-bs-toggle="modal" data-bs-target="#clientModal"><button class="btn btn-primary">Добавить нового клиента</button></a>
                    </div>

                </div>
                    
                    <?php
                    if ($pagesCount != 0){
                        echo("                <table class=\"table table-bordered table-hover\">
                        <thead>
                            <tr>
                                <th>№</th>
                                <th>ФИО</th>
                                <th>Контакты</th>
                            </tr>
                        </thead>");
                    }
                    else{
                        echo("<p class=\"form-text text-center\">Пока здесь нет ни одной записи!</p>");
                    }
                        foreach($new as $item){ ?>
                        <tr>
                        <td> <?= $item[0] ?></td>
                        <td> <?php echo($item[2]. ' '.$item[1].' '.$item[3]); $item[1] ?></td>
                        <td> <?= $item[4] ?>
                            <a href ="?del=<?= $item[0]?>" onclick="return confirm('Вы уверены, что хотите удалить эту запись? <?php echo($item[0]) ?>')"><img src="assets/pictures/any/delete.png" data-bs-toggle="tooltip" data-bs-placement="left" title="Удалить эту запись" alt="" width="20" height="20" class="d-inline-block float-end"></a>
                            <a href="edits/clients_edit.php?id= <?=$item[0] ?>"><img src="assets/pictures/any/edit.png" alt="" width="20" height="20" class="d-inline-block float-end"></a>
                        </td>
                        </tr>
                      <?php  }
                    ?>
                </table>
    
            </div>
        </div>
    </section>

    <footer>
        <div class="container-md pagin-at">
            <ul class="pagination justify-content-center ">
                <?php for ($i = 1; $i <= $pagesCount; $i++) { 
                    echo "<li class=\"page-item\"><a class=\"page-link\" href =\"?page=$i\">$i</a></li> ";
                } ?>
            </ul>
        </div>
    </footer>

    <!-- НОВЫЙ КЛИЕНТ -->
    <div class="modal fade" id="clientModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавить нового клиента</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"> 
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="one" class="form-label">Имя</label>
                        <input class="form-control" type="text" id="one" name="name">                   
                    </div>
                    <div class="mb-3">
                        <label for="two" class="form-label">Фамилия</label>
                        <input class="form-control" type="text" id="two" name="last_name">                   
                    </div>
                    <div class="mb-3">
                        <label for="mid" class="form-label">Отчество</label>
                        <input class="form-control" type="text" id="mid" name="middle_name">                   
                    </div>
                    <div class="mb-3">
                        <label for="three" class="form-label">Контактный телефон</label>
                        <input class="form-control" type="text" id="three" name="contacts">                   
                    </div>
                    <button type="submit" class="btn btn-primary" name="client-add">Добавить</button>
                </form>
            </div>
            </div>
        </div>
    </div>
    <script src="scripts/service_fields_check.js"></script>
</body>
</html>