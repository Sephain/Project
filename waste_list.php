<!-- ТЕСТОВАЯ ВЫБОРКА С БД (ТИПО СОТРУДНИКИ) -->
<?php
    session_start();
    require_once('php/connect.php');
    if (!$_SESSION['user']) { header('Location: ../index.php'); }
    require_once('php/connect-main.php');

    // query for deleting
    if (isset($_GET['del'])) {
        $id = ($_GET['del']);
        mysqli_query($connect_main, "DELETE FROM `waste_list` WHERE id=$id") or die(mysqli_error($connect_main));
    }

    //query for select
    if (isset($_GET['page'])) { $page = $_GET['page']; }
    else { $page = 1; }

    $recordOnPage = 15; // количество записей на странице
    $startFrom = ($page - 1) * $recordOnPage;
    $select_text="SELECT
	`waste_list`.`id` as id,
    `waste_list`.`service_id` as service_id,
    `service_list`.`date` as date
    FROM 
        `service_provision`
    INNER JOIN `waste_list` ON `waste_list`.`service_id`=`service_provision`.`id`
    INNER JOIN `service_list` ON `service_list`.`id`=`service_provision`.`service_list_id`
    ORDER BY `waste_list`.`id`
    LIMIT $startFrom,$recordOnPage";
    $select_query = mysqli_query($connect_main, $select_text) or die(mysqli_error($connect_main));
    $new = mysqli_fetch_all($select_query);
    

    // pagination =)

    $count_query = mysqli_query($connect_main, "SELECT COUNT(*) as count FROM `waste_list`") or die(mysqli_error($connect));
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
    <title>Отходы</title>
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
                <h3><p>Раздел "Утилизация отходов"</p></h3>
                <p class="fst-italic">Здесь представлен список отходов, возникающих после оказания услуг. </p>
                <p class="fst-italic">Вы можете добавить новый бланк утилизации отходов либо ознакомиться с существующими и изменить их.</p>
                <hr>
            </div>
            <div class="mt-4 mb-4">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <a data-bs-toggle="modal" data-bs-target="#exampleModal"><button class="btn btn-primary">Добавить запись</button></a>
                    </div>

                </div>
                

                    
                    <?php
                    if($pagesCount!=0){
                        echo("                <table class=\"table table-bordered table-hover\">
                        <thead>
                            <tr>
                                <th>№</th>
                                <th>Номер оказания услуги</th>
                                <th>Дата оказания услуги</th>
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
                        <td> <?= $item[2] ?>
                            <a href ="?del=<?= $item[0]?>" onclick="return confirm('Вы уверены, что хотите удалить эту запись? <?php echo($item[0]) ?>')"><img src="assets/pictures/any/delete.png" data-bs-toggle="tooltip" data-bs-placement="left" title="Удалить эту запись" alt="" width="20" height="20" class="d-inline-block float-end"></a>
                            <a href="edits/waste_list_edit.php?id=<?= $item[0] ?>"><img src="assets/pictures/any/edit.png" alt="" width="20" height="20" class="d-inline-block float-end"></a>
                            <a href="waste_content.php?waste_list_id=<?= $item[0] ?>"><img src="assets/pictures/any/view.png" title="Просмотреть содержание" alt="" width="20" height="20" class="d-inline-block float-end"></a>
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
                <h5 class="modal-title" id="exampleModalLabel">Добавление отходов</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"> 
                <form action="processing/waste_list-add.php" method="POST" id="my_form">
                    <div class="mb-3">
                        <label for="one" class="form-label">Выберите услугу</label>
                        <select class="form-select" aria-label="Default select example" name="service" id="one">
                            <?php 
                                $emp_q = mysqli_query($connect_main, "SELECT 
                                `service_provision`.`id` as id,
                                `materials`.`name` as name,
                                `service_list`.`date` as date
                                FROM 
                                `service_provision`
                                LEFT JOIN `waste_list` ON `waste_list`.`service_id`=`service_provision`.`id`
                                INNER JOIN `service_list` ON `service_list`.`id`=`service_provision`.`service_list_id`
                                INNER JOIN `materials` ON `materials`.`id`=`service_provision`.`service_id`
                                WHERE `waste_list`.`service_id` is NULL");
                                $res = mysqli_fetch_all($emp_q);
                                foreach ($res as $item) {
                                    echo("<option value=$item[0]>$item[0] - $item[1], $item[2]</option>");
                                }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" name="bbtn" id="btn_add">Добавить</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <div class="warn" id="warning"></div>
                </form>
            </div>
            </div>
        </div>
    </div>
    <script src="scripts/waste_list_fields_check.js"></script>
</body>
</html>