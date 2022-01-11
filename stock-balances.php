<!-- ТЕСТОВАЯ ВЫБОРКА С БД (ТИПО СОТРУДНИКИ) -->
<?php
    session_start();
    require_once('php/connect.php');
    if (!$_SESSION['user']) { header('Location: ../index.php'); }
    require_once('php/connect-main.php');
    $_SESSION['bool'] = false;

    //add new mat
    if (isset($_POST['newmaterial'])) {
        $newm = $_POST['newmaterial'];

        $q_text = "INSERT INTO `materials` (`name`) VALUES ('$newm')";
        mysqli_query($connect_main, $q_text) or die(mysqli_error($connect_main));
        header("Location: ../stock-balances.php");
    }
   
    // query for deleting
    if (isset($_GET['del'])) {
        $id = ($_GET['del']);
        mysqli_query($connect_main, "DELETE FROM `stock_balances` WHERE id=$id") or die(mysqli_error($connect_main));
    }

    //query for select
    if (isset($_GET['page'])) { $page = $_GET['page']; }
    else { $page = 1; }

    $recordOnPage = 10; // количество записей на странице
    $startFrom = ($page - 1) * $recordOnPage;
    $select_text="SELECT `stock_balances`.`id` AS 'id', `materials`.`name` AS 'material_name', `stock_balances`.`count` AS 'count'
    FROM 
	    `materials` INNER JOIN `stock_balances`
    ON 
	    `materials`.`id`=`stock_balances`.`material_id`
    -- WHERE `stock_balances`.`count` != 0
    ORDER BY `stock_balances`.`id`
    LIMIT $startFrom,$recordOnPage";
    $select_query = mysqli_query($connect_main, $select_text);
    $new = mysqli_fetch_all($select_query);
    

    // pagination =)
    $count_query = mysqli_query($connect_main, "SELECT COUNT(*) as count FROM `stock_balances`") or die(mysqli_error($connect));
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
    <script src="https://kit.fontawesome.com/a016363d36.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles/stl.css">   
    <title>Материалы в наличии</title>
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
                <h3><p>Раздел "Материалы в наличии"</p></h3>
                <p class="fst-italic">Здесь представлен список всех материалов, которыми располагает фотоцентр на данный момент. </P>
                <p class="fst-italic">Обратите внимание, если на складе будет недостаточно какого-го либо материала или он вовсе зкончился, рядом с ним появится характерный значок восклицательного знака.</p>
                <hr>
            </div>
            <div class="mt-4 mb-4">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <a data-bs-toggle="modal" data-bs-target="#exampleModal"><button class="btn btn-primary">Добавить материал</button></a>
                    </div>
                    
                </div>
                

                
                    <?php
                    if($pagesCount != 0){
                        echo("                <table class=\"table table-bordered table-hover\">
                        <thead>
                            <tr>
                                <th>№</th>
                                <th>Материал</th>
                                <th>Количество</th>
                            </tr>
                        </thead>");
                    }
                    else{
                        echo("<p class=\"form-text text-center\">Пока здесь нет ни одной записи!</p>");
                    }
                    
                        foreach($new as $item){ ?>
                        <tr>
                        <td> <?= $item[0] ?></td>
                        <td> <?php if ($item[2]<10) {echo("<span class=\"fa fa-exclamation-circle text-danger float-end\" title=\"Нехватка материала!\"></span>");} echo($item[1]); ?></td>
                        <td> <?php echo($item[2]); ?>
                            <a href ="?del=<?= $item[0]?>" onclick="return confirm('Вы уверены, что хотите удалить эту запись? <?php echo($item[0]) ?>')"><img src="assets/pictures/any/delete.png" data-bs-toggle="tooltip" data-bs-placement="left" title="Удалить эту запись" alt="" width="20" height="20" class="d-inline-block float-end"></a>
                            <a href="edits/stock_balance_edit.php?id=<?= $item[0] ?>"><img src="assets/pictures/any/edit.png" alt="" width="20" height="20" class="d-inline-block float-end"></a>
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
                <h5 class="modal-title" id="exampleModalLabel">Добавление нового материала</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"> 
                <form action="processing/stock-add.php" method="POST" id="my_form">
                    <div class="mb-3">
                        <label for="one" class="form-label">Выберите материал</label>
                        <select class="form-select" aria-label="Default select example" name="material" id="one">
                            <?php 
                                $emp_q = mysqli_query($connect_main, "SELECT * FROM `materials`");
                                $res = mysqli_fetch_all($emp_q);
                                foreach ($res as $item) {
                                    echo("<option value=$item[0]>$item[1]</option>");
                                }
                            ?>
                        </select>
                        <a href="" class="form-text" data-bs-toggle="modal" data-bs-target="#MaterialModal" data-bs-dismiss="modal">Добавить новый материал</a>
                    </div>
                    <div class="mb-3">
                        <label for="two" class="form-label">Количество</label>
                        <input class="form-control" type="text" id="two" name="count">                   
                    </div>
                    <button type="submit" class="btn btn-primary" name="bbtn" id="btn_add">Добавить</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <div class="warn" id="warning"></div>
                </form>
            </div>
            </div>
        </div>
    </div>


        <!-- НОВЫЙ МАТЕРИАЛ -->
    <div class="modal fade" id="MaterialModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавить новый материал</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"> 
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="two" class="form-label">Название материала</label>
                        <input class="form-control" type="text" id="two" name="newmaterial">                   
                    </div>
                    <button type="submit" class="btn btn-primary" name="bbtn-add">Добавить</button>
                </form>
            </div>
            </div>
        </div>
    </div>
    <script src="scripts/stock_fields_check.js"></script>
</body>
</html>