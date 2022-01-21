<!-- ТЕСТОВАЯ ВЫБОРКА С БД (ТИПО СОТРУДНИКИ) -->
<?php
    session_start();
    require_once('php/connect.php');
    if (!$_SESSION['user']) { header('Location: ../index.php'); }
    require_once('php/connect-main.php');
    if (isset($_GET['waste_list_id'])) {$waste_list_id = $_GET['waste_list_id']; }

    // find this service lsit
    $find_waste_list_text="SELECT `waste_list`.`id` AS id, `waste_list`.`date` AS date, `waste_list`.`service_id` AS service_id, CONCAT(`employee`.`last_name`, ' ', `employee`.`first_name`, ' ', `employee`.`middle_name`) AS name FROM `waste_list` INNER JOIN `employee` ON `employee`.`id`=`waste_list`.`employee_id` WHERE `waste_list`.`id`=$waste_list_id";
    $waste_list_data = mysqli_fetch_assoc( mysqli_query($connect_main, $find_waste_list_text));

    // query for deleting
    if (isset($_GET['del'])) {
        $id = ($_GET['del']);
        mysqli_query($connect_main, "DELETE FROM `waste` WHERE `id`=$id") or die(mysqli_error($connect_main));
        header("Location: ../waste_content.php?waste_list_id=$waste_list_id");
    }

    //query for select
    if (isset($_GET['page'])) { $page = $_GET['page']; }
    else { $page = 1; }

    $recordOnPage = 10; // количество записей на странице
    $startFrom = ($page - 1) * $recordOnPage;
    $select_text = "SELECT `id`, `name`, `amount`, `measure` FROM `waste` WHERE `waste_list_id`=$waste_list_id LIMIT $startFrom,$recordOnPage";

    $select_query = mysqli_query($connect_main, $select_text) or die(mysqli_error($connect_main));
    $new = mysqli_fetch_all($select_query);
    
    // pagination =)
    $count_query = mysqli_query($connect_main, "SELECT COUNT(*) as count FROM `waste` WHERE `waste_list_id`=$waste_list_id") or die(mysqli_error($connect_main));
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
    <title>Акт утилизации отходов</title>
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
                    <h3><p>Акт утилизации отходов № <?= $waste_list_data['id'] ?></p></h3>
                    <p>Дата формирования бланка: <?= $waste_list_data['date'] ?></p>
                    <p>Номер оказанной услуги: <?= $waste_list_data['service_id'] ?></p>
                    <p>Сотрудник, ответственный за утилизацию: <?= $waste_list_data['name'] ?></p>
                    <hr>
                </div>
            <div class="mt-4 mb-4">
                <div class="row">
                    <div class="col-md-5 mb-4">
                        <a data-bs-toggle="modal" data-bs-target="#exampleModal"><button class="btn btn-primary">Добавить запись</button></a>
                        <a href="waste_list.php"><button class="btn btn-success">Закончить формирование бланка</button></a>
                    </div>
                </div>
                

                    
                    <?php
                    if ($pagesCount != 0){
                        echo("                <table class=\"table table-bordered table-hover\">
                        <thead>
                            <tr>
                                <th>№</th>
                                <th>Название</th>
                                <th>Количество</th>
                                <th>Измерение</th>
                            </tr>
                        </thead>");
                    }
                    else{
                        echo("<p class=\"form-text text-center\">Пока здесь нет ни одной записи!</p>");
                    }
                        foreach($new as $item){ ?>
                        <tr>
                        <td> <?= $item[0] ?></td>
                        <td> <?= $item[1] ?></td>
                        <td> <?= $item[2] ?></td>
                        <td> <?= $item[3] ?>
                            <a href ="?waste_list_id=<?=$waste_list_id?>&del=<?= $item[0]?>" onclick="return confirm('Вы уверены, что хотите удалить эту запись? <?php echo($item[0]) ?>')"><img src="assets/pictures/any/delete.png" data-bs-toggle="tooltip" data-bs-placement="left" title="Удалить эту запись" alt="" width="20" height="20" class="d-inline-block float-end"></a>
                            <a href="edits/waste_edit.php?id=<?= $item[0] ?>"><img src="assets/pictures/any/edit.png" alt="" width="20" height="20" class="d-inline-block float-end"></a>
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
                    echo "<li class=\"page-item\"><a class=\"page-link\" href =\"?waste_list_id=$waste_list_id&page=$i\">$i</a></li> ";
                } ?>
            </ul>
        </div>
    </footer>

    <!-- ADD Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавление отходов</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"> 
                <form action="processing/waste-add.php" method="POST" id="my_form">
                    <div class="mb-3">
                        <label for="one" class="form-label">Название</label>
                        <input class="form-control" type="text" id="one" name="name">                   
                    </div>
                    <div class="mb-3">
                        <label for="three" class="form-label">Количество</label>
                        <input class="form-control" type="text" id="two" name="count">                   
                    </div>
                    <div class="mb-3">
                        <label for="six" class="form-label">Измерение</label>
                        <input class="form-control" type="text" id="three" name="measure">                   
                    </div>
                    <input type="text" id="four" name="waste_list_id" visibility: hidden value="<?=$waste_list_id?>">  
                    <button type="submit" class="btn btn-primary" name="bbtn" id="btn_add">Добавить</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <div class="warn" id="warning"></div>
                </form>
            </div>
            </div>
        </div>
    </div>
    <script src="scripts/waste_fields_check.js"></script>
</body>
</html>