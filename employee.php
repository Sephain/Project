
<?php
    session_start();
    require_once('php/connect.php');
    if (!$_SESSION['user']) { header('Location: ../index.php'); }
    require_once('php/connect-main.php');
    // print_r($_SESSION['user']['position']);
    
    // query for deleting
    if (isset($_GET['del'])) {
        $id = ($_GET['del']);
        mysqli_query($connect_main, "DELETE FROM `employee` WHERE id=$id") or die(mysqli_error($connect_main));
        mysqli_query($connect, "DELETE FROM `users` WHERE `employee_id`=$id") or die(mysqli_error($connect));
    }

    //query for select
    if (isset($_GET['page'])) { $page = $_GET['page']; }
    else { $page = 1; }
    $recordOnPage = 15; // количество записей на странице
    $startFrom = ($page - 1) * $recordOnPage;
    $select_query = mysqli_query($connect_main, "SELECT * FROM `employee` INNER JOIN `position` ON `employee`.`position`=`position`.`id` LIMIT $startFrom,$recordOnPage") or die($connect_main);
    $new = mysqli_fetch_all($select_query);
    //print_r($new);
    
    // pagination =)
    $count_query = mysqli_query($connect_main, "SELECT COUNT(*) as count FROM `employee`") or die(mysqli_error($connect));
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
    <title>Сотрудники</title>
</head>
<body>
    <?php include('navbars/engineer.php');?>



    <section>
        <div class="container-md">
            <div class="mt-4 mb-4">
                <h3><p>Раздел "Сотрудники"</p></h3>
                <p class="fst-italic">Здесь представлен список всех сотрудников, работающих в нашем фотоцентре. </p>
                <p class="fst-italic">Вы можете добавить нового сотрудника в список, и если ему по работе необходимо использовать сайт, ему также создастся аккаунт.</p>
                <hr>
            </div>
            <div class="mt-4 mb-4">
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <a data-bs-toggle="modal" data-bs-target="#exampleModal"><button class="btn btn-primary">Добавить нового сотрудника</button></a>
                    </div>
                </div>
   
                    <?php
                        if ($pagesCount != 0) {
                            echo("<table class=\"table table-bordered table-hover\">
                            <thead>
                                <tr>
                                    <th>№</th>
                                    <th>ФИО</th>
                                    <th>Адрес</th>
                                    <th>Контакты</th>
                                    <th>Должность</th>
                                    <th>Оклад</th>
                                </tr>
                            </thead>");
                        }
                        else{
                            echo("<p class=\"form-text text-center\">Пока здесь нет ни одной записи!</p>");
                        }
                        foreach($new as $item){ ?>
                        <tr>
                        <td> <?= $item[0] ?></td>
                        <td> <?php echo($item[2] . ' ' . $item[1] . ' ' . $item[3]); ?></td>
                        <td> <?= $item[4] ?></td>
                        <td> <?= $item[5] ?></td>
                        <td> <?= $item[9] ?></td>
                        <td> <?= $item[7] ?>
                            <a href ="?del=<?= $item[0]?>" onclick="return confirm('Вы уверены, что хотите удалить эту запись? <?php echo($item[0]) ?>')"><img src="assets/pictures/any/delete.png" data-bs-toggle="tooltip" data-bs-placement="left" title="Удалить эту запись" alt="" width="20" height="20" class="d-inline-block float-end"></a>
                            <a href="edits/employee_edit.php?id=<?= $item[0] ?>"><img src="assets/pictures/any/edit.png" alt="" width="20" height="20" class="d-inline-block float-end"></a>
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

    <!-- ADD Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавление нового сотрудника</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"> 

                <form action="processing/employee-add.php" method="POST" id="my_form">
                    <div class="mb-3">
                        <label for="one" class="form-label">Имя</label>
                        <input class="form-control" type="text" id="one" name="Name">                   
                    </div>
                    <div class="mb-3">
                        <label for="two" class="form-label">Фамилия</label>
                        <input class="form-control" type="text" id="two" name="Last_name">                   
                    </div>
                    <div class="mb-3">
                        <label for="middle" class="form-label">Отчество</label>
                        <input class="form-control" type="text" id="middle" name="Middle_name">                   
                    </div>
                    <div class="mb-3">
                        <label for="three" class="form-label">Email</label>
                        <input class="form-control" type="text" id="three" name="email">                   
                    </div>
                    <div class="mb-3">
                        <label for="four" class="form-label">Адрес</label>
                        <input class="form-control" type="text" id="four" name="Adress">                   
                    </div>
                    <div class="mb-3">
                        <label for="five" class="form-label">Контакты</label>
                        <input class="form-control" type="text" id="five" name="Contacts">                   
                    </div>
                    <div class="mb-3">
                        <label for="six" class="form-label">Выберите должность</label>
                        <select class="form-select" aria-label="Default select example" name="Position" id="six">   
                        <?php 
                            $emp_q = mysqli_query($connect_main, "SELECT * FROM `position` WHERE `id` != '1'");
                            $res = mysqli_fetch_all($emp_q);
                            foreach ($res as $item) {
                                echo("<option value=$item[0]>$item[1]</option>");
                            }
                        ?>
                        </select>           
                    </div>
                    <div class="mb-3">
                        <label for="seven" class="form-label">Оклад</label>
                        <input class="form-control" type="text" id="seven" name="Salary">                   
                    </div>
                    <button type="submit" class="btn btn-primary" name="bbtn" id="btn_add">Добавить</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <div class="warn" id="warning"></div>
                </form>

            </div>
            </div>
        </div>
    </div>
    <script src="scripts/employee_fields_check.js"></script>
</body>
</html>