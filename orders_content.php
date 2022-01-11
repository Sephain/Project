<!-- ТЕСТОВАЯ ВЫБОРКА С БД (ТИПО СОТРУДНИКИ) -->
<?php
    session_start();
    require_once('php/connect.php');
    if (!$_SESSION['user']) { header('Location: ../index.php'); }
    require_once('php/connect-main.php');
    if (isset($_GET['order_id'])) {$order_id = $_GET['order_id']; }

    //find this order
    $find_order_text = "SELECT 
	`orders`.`id` AS id,
    `orders`.`number` AS ssumber,
    `vendor`.`name` AS vendor,
    `orders`.`date` AS ddate,
    `orders`.`receipt_date` AS rddate,
    CONCAT(`employee`.`first_name`, ' ',`employee`.`last_name`) AS emp
    FROM
        `orders`
    INNER JOIN `vendor` ON `vendor`.`id`=`orders`.`vendor_id`
    INNER JOIN `employee` ON `employee`.`id`=`orders`.`employee_id`
    WHERE `orders`.`id`='$order_id'
    ORDER BY `orders`.`id`
    ";
    $order_data = mysqli_fetch_assoc( mysqli_query($connect_main, $find_order_text));

    //add new mat
    if (isset($_POST['newmaterial'])) {
        $newm = $_POST['newmaterial'];

        $q_text = "INSERT INTO `materials` (`name`) VALUES ('$newm')";
        mysqli_query($connect_main, $q_text) or die(mysqli_error($connect_main));
        header("Location: ../orders_content.php?order_id=$order_id");
    }


    // query for deleting
    if (isset($_GET['del'])) {
        $id = ($_GET['del']);
        mysqli_query($connect_main, "DELETE FROM `orders_content` WHERE id=$id") or die(mysqli_error($connect_main));
        header("Location: ../orders_content.php?order_id=$order_id");
    }

    //query for select
    if (isset($_GET['page'])) { $page = $_GET['page']; }
    else { $page = 1; }

    
    $recordOnPage = 5; // количество записей на странице
    $startFrom = ($page - 1) * $recordOnPage;
    $select_text="SELECT 
	`orders_content`.`id` AS id,
    `materials`.`name` AS name,
    `orders_content`.`count` AS ccount,
    `orders_content`.`price_one` AS price,
    `orders`.`number` AS num
    FROM
        `orders_content`
    INNER JOIN `materials` ON `materials`.`id`=`orders_content`.`material_id`
    INNER JOIN `orders` ON `orders`.`id`=`orders_content`.`orders_id`
    WHERE `orders_content`.`orders_id`=$order_id
    ORDER BY `orders_content`.`id`
    LIMIT $startFrom,$recordOnPage";

    $select_query = mysqli_query($connect_main, $select_text);
    $new = mysqli_fetch_all($select_query);
    

    // pagination =)
    $count_query = mysqli_query($connect_main, "SELECT COUNT(*) as count FROM `orders_content`") or die(mysqli_error($connect_main));
    $count = mysqli_fetch_assoc($count_query)['count'];
    $pagesCount = ceil($count / $recordOnPage);

    //total cost
    $select_text="SELECT 
	`orders_content`.`id` AS id,
    `materials`.`name` AS name,
    `orders_content`.`count` AS ccount,
    `orders_content`.`price_one` AS price,
    `orders`.`number` AS num
    FROM
        `orders_content`
    INNER JOIN `materials` ON `materials`.`id`=`orders_content`.`material_id`
    INNER JOIN `orders` ON `orders`.`id`=`orders_content`.`orders_id`
    WHERE `orders_content`.`orders_id`=$order_id
    ORDER BY `orders_content`.`id`";

    $_SESSION['cost'] = 0;
    $cost = mysqli_fetch_all(mysqli_query($connect_main, $select_text));
    foreach ($cost as $i) {$_SESSION['cost'] += $i[2]*$i[3];}
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
    <title>Состав</title>
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

                <div class="mt-4 mb-4">
                    <h3><p>Приходная накладная № <?= $order_data['ssumber'] ?></p></h3>
                    <p>Поставщик: <?= $order_data['vendor'] ?></p>
                    <p>Сотрудник, принявший заказ: <?= $order_data['emp'] ?></p>
                    <p>Дата заказа: <?= $order_data['ddate'] ?></p>
                    <p>Дата получения: <?= $order_data['rddate'] ?></p>
                    <p>Общая сумма заказа: <b><?=$_SESSION['cost']?></b>   </p>
                    <hr>
                </div>

                <div class="row">
                    <div class="col-md-2 mb-4">
                        <a data-bs-toggle="modal" data-bs-target="#exampleModal"><button class="btn btn-primary">Добавить запись</button></a>
                    </div>
                    <div class="col-md-4 mb-4">
                        <form action="orders.php" method="POST">
                            <button class="btn btn-success">Закончить формирование заказа</button>
                        </form>
                    </div>
                </div>
                      
                    <?php 
                        if ($pagesCount != 0){
                            echo("                <table class=\"table table-bordered table-hover\">
                            <thead>
                                <tr>
                                    <th>№</th>
                                    <th>Материал</th>
                                    <th>Количество</th>
                                    <th>Цена за единицу</th>
                                    <th>Общая цена</th>
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
                        <td> <?= $item[3]*$item[2] ?>
                            <a href ="?order_id=<?=$order_id?>&del=<?= $item[0]?>" onclick="return confirm('Вы уверены, что хотите удалить эту запись? <?php echo($item[0]) ?>')"><img src="assets/pictures/any/delete.png" data-bs-toggle="tooltip" data-bs-placement="left" title="Удалить эту запись" alt="" width="20" height="20" class="d-inline-block float-end"></a>
                            <a href="edits/edit.php?utable=orders_content&id=<?= $item[0]?>&order_id=<?=$order_id?>"><img src="assets/pictures/any/edit.png" alt="" width="20" height="20" class="d-inline-block float-end"></a>
                        </td>
                        </tr>
                      <?php  }
                    ?>
                </table>
                         
            </div>
            <div class="row">
                
            </div>
        </div>
        
    </section>
   

    <footer>
        <div class="container-md pagin-at">
            <ul class="pagination justify-content-center ">
                <?php for ($i = 1; $i <= $pagesCount; $i++) { 
                    echo "<li class=\"page-item\" ><a class=\"page-link\" href =\"?order_id=$order_id&page=$i\">$i</a></li> ";
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
                <form action="processing/orders_content-add.php" method="POST" id="my_form">
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
                    <div class="mb-3">
                        <label for="three" class="form-label">Цена</label>
                        <input class="form-control" type="text" id="three" name="price">                   
                    </div>
                    <input type="text" name="order_id" visibility: hidden value="<?=$order_id?>">
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

    <script src="scripts/orders_content_fields_check.js"></script>
</body>
</html>