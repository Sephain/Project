
<?php
    session_start();
    require_once('php/connect.php');
    if (!$_SESSION['user']) { header('Location: ../index.php'); }
    require_once('php/connect-main.php');
    
    // query for deleting
    if (isset($_GET['del'])) {
        $id = ($_GET['del']);
        mysqli_query($connect_main, "DELETE FROM `expenses` WHERE id=$id") or die(mysqli_error($connect_main));
    }

    //query for select
    if (isset($_GET['page'])) { $page = $_GET['page']; }
    else { $page = 1; }

    $recordOnPage = 15; // количество записей на странице
    $startFrom = ($page - 1) * $recordOnPage;
    $select_text="SELECT 
	`expenses`.`id` AS id,
    `materials`.`name` AS material,
    `expenses`.`count` AS counts,
    CONCAT(`employee`.`first_name`, ' ',`employee`.`last_name`) AS name,
    `expenses`.`date` AS ddate,
    `expenses`.`purpose` AS purpose
    FROM	
        `expenses`
    INNER JOIN `materials` ON `materials`.`id`=`expenses`.`material_id`
    INNER JOIN `employee` ON `employee`.`id`=`expenses`.`employee_id`
    ORDER BY `expenses`.`id`
    LIMIT $startFrom,$recordOnPage";
    $select_query = mysqli_query($connect_main, $select_text);
    $new = mysqli_fetch_all($select_query);
    

    // pagination =)
    $count_query = mysqli_query($connect_main, "SELECT COUNT(*) as count FROM `expenses`") or die(mysqli_error($connect));
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
    <title>Расходы</title>
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
                <h3><p>Раздел "Расход материалов"</p></h3>
                <p class="fst-italic">Здесь представлен список всех расходов материалов со склада. </p>
                <p class="fst-italic">Вы можете добавить новую позицию либо изменить существующие.</p>
                <hr>
            </div>
            <div class="mt-4 mb-4">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <a data-bs-toggle="modal" data-bs-target="#exampleModal"><button class="btn btn-primary">Добавить запись</button></a>
                    </div>
                    <?php if(isset($_SESSION['error'])){echo("<div class=\"warn\" id=\"warning\">На складе недостаточно материалов!</div>"); unset($_SESSION['error']);}?>
                </div>
                
                    
                    
                    <?php
                        if ($pagesCount != 0){
                            echo("                <table class=\"table table-bordered table-hover\">
                            <thead>
                                <tr>
                                    <th>№</th>
                                    <th>Материал</th>
                                    <th>Количество</th>
                                    <th>Кому выдано</th>
                                    <th>Дата выдачи</th>
                                    <th>Цель</th>
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
                        <td> <?= $item[3] ?></td>
                        <td> <?= $item[4] ?></td>
                        <td> <?= $item[5] ?>
                            <a href ="?del=<?= $item[0]?>" onclick="return confirm('Вы уверены, что хотите удалить эту запись? <?php echo($item[0]) ?>')"><img src="assets/pictures/any/delete.png" data-bs-toggle="tooltip" data-bs-placement="left" title="Удалить эту запись" alt="" width="20" height="20" class="d-inline-block float-end"></a>
                            <a href="edits/expenses_edit.php?id=<?= $item[0] ?>"><img src="assets/pictures/any/edit.png" alt="" width="20" height="20" class="d-inline-block float-end"></a>
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
                <h5 class="modal-title" id="exampleModalLabel">Добавление расходов</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"> 
                <form action="processing/expenses-add.php" method="POST" id="my_form">
                    <div class="mb-3">
                        <label for="one" class="form-label">Выберите материал</label>
                        <select class="form-select" aria-label="Default select example" name="material" id="one">
                            <?php 
                                $emp_q = mysqli_query($connect_main, "SELECT * FROM `materials` INNER JOIN `stock_balances` ON `materials`.`id`=`stock_balances`.`material_id` WHERE `stock_balances`.`count` != 0");
                                $res = mysqli_fetch_all($emp_q);
                                foreach ($res as $item) {
                                    echo("<option value=$item[0]>$item[1]</option>");
                                }
                            ?>
                        </select>
                        
                    </div>
                    <div class="mb-3">
                        <label for="two" class="form-label">Количество</label>
                        <input class="form-control" type="text" id="two" name="count">                   
                    </div>
                    <div class="mb-3">
                        <label for="three" class="form-label">Кому было выдано</label>
                        <select class="form-select" aria-label="Default select example" name="employee" id="three">
                            <?php 
                                $emp_q = mysqli_query($connect_main, "SELECT * FROM `employee`");
                                $res = mysqli_fetch_all($emp_q);
                                foreach ($res as $item) {
                                    echo("<option value=$item[0]>$item[1] $item[2]</option>");
                                }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="four" class="form-label">Дата выдачи</label>
                        <input class="form-control" type="date" id="four" name="date">                   
                    </div>
                    <div class="mb-3">
                        <label for="five" class="form-label">Назначение</label>
                        <input class="form-control" type="text" id="five" name="purpose">                   
                    </div>
                    <button type="submit" class="btn btn-primary" name="bbtn" id="btn_add">Добавить</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <div class="warn" id="warning"></div>
                   
                </form>
            </div>
            </div>
        </div>
    </div>
<script src="scripts/expenses_field_check.js"></script>
</body>
</html>