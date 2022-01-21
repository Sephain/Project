<!-- ТЕСТОВАЯ ВЫБОРКА С БД (ТИПО СОТРУДНИКИ) -->
<?php
    session_start();
    require_once('php/connect.php');
    if (!$_SESSION['user']) { header('Location: ../index.php'); }
    require_once('php/connect-main.php');
    if (isset($_GET['service_list_id'])) {$service_list_id = $_GET['service_list_id']; }

    // price lists
    $service_price = mysqli_fetch_all(mysqli_query($connect_main, "SELECT `materials`.`id` as mat_id, `materials`.`name` as name, `materials`.`price` as price FROM  `materials` WHERE `materials`.`category_id`=1"));
    $goods_price = mysqli_fetch_all(mysqli_query($connect_main, "SELECT `materials`.`id` as mat_id, `materials`.`name` as name, `materials`.`price` as price FROM  `materials` WHERE `materials`.`category_id`=2"));

    // find this service lsit
    $find_service_text="SELECT
    `service_list`.`id` as id,
	CONCAT(`employee`.`last_name`, ' ', `employee`.`first_name`, ' ',`employee`.`middle_name`) as name,
    CONCAT(`clients`.`last_name`, ' ', `clients`.`first_name`, ' ', `clients`.`middle_name`) as client_name,
    `service_list`.`date`as ddate
    FROM 
        `service_list`
    INNER JOIN `clients` ON `clients`.`id`=`service_list`.`client_id`
    INNER JOIN `employee` ON `employee`.`id`=`service_list`.`employee_id`
    WHERE `service_list`.`id`=$service_list_id";
    $service_data = mysqli_fetch_assoc( mysqli_query($connect_main, $find_service_text));

    //add new service
    if (isset($_POST['service-add'])){
        $serviceName = $_POST['newservice'];
        $category = $_POST['category'];
        $price = $_POST['price'];

        mysqli_query($connect_main, "INSERT INTO `materials` (`name`, `price`, `category_id`) VALUES ('$serviceName', '$price', '$category')") or die(mysqli_error($connect_main));
        header("Location: ../service_content.php?service_list_id=$service_list_id");
    }

    // query for deleting
    if (isset($_GET['del'])) {
        $id = ($_GET['del']);
        mysqli_query($connect_main, "DELETE FROM `service_provision` WHERE `id`=$id") or die(mysqli_error($connect_main));
        header("Location: ../service_content.php?service_list_id=$service_list_id");
    }

    if (isset($_GET['mat_del'])) {
        $mat_id = $_GET['mat_del'];
        mysqli_query($connect_main, "DELETE FROM `materials` WHERE `id`=$mat_id") or die(mysqli_error($connect_main));
        header("Location: ../service_content.php?service_list_id=$service_list_id");
    }

    //query for select
    if (isset($_GET['page'])) { $page = $_GET['page']; }
    else { $page = 1; }

    $recordOnPage = 10; // количество записей на странице
    $startFrom = ($page - 1) * $recordOnPage;
    $select_text = "SELECT `service_provision`.`id` as id,
    `materials`.`name` as name,
    `service_provision`.`count` as count,
    `service_provision`.`price` as price,
    `service_list`.`id` as list_id
    FROM `service_provision`
    INNER JOIN `materials` ON `materials`.`id`=`service_provision`.`service_id`
    INNER JOIN `service_list` ON `service_list`.`id`=`service_provision`.`service_list_id`
    WHERE `service_list`.`id`=$service_list_id
    ORDER BY `service_provision`.`id`
    LIMIT $startFrom, $recordOnPage";

    $select_query = mysqli_query($connect_main, $select_text) or die(mysqli_error($connect_main));
    $new = mysqli_fetch_all($select_query);
    

    // pagination =)
    $count_query = mysqli_query($connect_main, "SELECT COUNT(*) as count FROM `service_provision` WHERE `service_list_id`=$service_list_id") or die(mysqli_error($connect_main));
    $count = mysqli_fetch_assoc($count_query)['count'];
    $pagesCount = ceil($count / $recordOnPage);

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
    <title>Акт об оказании услуг</title>
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
                    <h3><p>Акт об оказании услуг № <?= $service_data['id'] ?></p></h3>
                    <p>ФИО сотрудника, оказавший услугу: <?= $service_data['name'] ?></p>
                    <p>ФИО клиента: <?= $service_data['client_name'] ?></p>
                    <p>Дата оказания услуг: <?= $service_data['ddate'] ?></p>
                    <p>Общая стоимость: <b><?=$_SESSION['cost']?></b></p>
                    <hr>
                </div>
            <div class="mt-4 mb-4">
                <div class="row">
                    <div class="col-md-5 mb-4">
                        <a data-bs-toggle="modal" data-bs-target="#addModal"><button class="btn btn-primary">Добавить запись</button></a>
                        <a href="service.php"><button class="btn btn-success">Закончить формирование бланка</button></a>
                    </div>
                    <div class="col-md-7 mb-4">
                        <a data-bs-toggle="modal" data-bs-target="#priceList"><button class="btn btn-secondary float-end">Открыть прайс-лист</button></a>
                    </div>
                    <?php if(isset($_SESSION['error'])){echo("<div class=\"warn\" id=\"warning\">На складе недостаточно материалов!</div>"); unset($_SESSION['error']);}?>
                </div>
                

                    
                    <?php
                    if ($pagesCount != 0){
                        echo("                <table class=\"table table-bordered table-hover\">
                        <thead>
                            <tr>
                                <th>№</th>
                                <th>Название услуги</th>
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
                            <a href ="?service_list_id=<?=$service_list_id?>&del=<?= $item[0]?>" onclick="return confirm('Вы уверены, что хотите удалить эту запись? <?php echo($item[0]) ?>')"><img src="assets/pictures/any/delete.png" data-bs-toggle="tooltip" data-bs-placement="left" title="Удалить эту запись" alt="" width="20" height="20" class="d-inline-block float-end"></a>
                            <a href="edits/service_content_edit.php?id=<?= $item[0] ?>&service_list_id=<?=$service_list_id?>"><img src="assets/pictures/any/edit.png" alt="" width="20" height="20" class="d-inline-block float-end"></a>
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
                    echo "<li class=\"page-item\"><a class=\"page-link\" href =\"?service_list_id=$service_list_id&page=$i\">$i</a></li> ";
                } ?>
            </ul>
        </div>
    </footer>



    <!-- good/service add -->

    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Добавить новый товар или услугу</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"> 
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Услуги</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Товары</button>
                        </li>
                    </ul>
                    
                        <div class="tab-content mt-2" id="myTabContent">
                            <!-- service -->
                            <div class="tab-pane fade show active mb-3" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <form action="processing/sc_service.php" method="POST">
                                    <div class="mb-3">
                                        <label for="one" class="form-label">Услуга</label>
                                        <select class="form-select" name="service" id="one" >
                                            <?php 
                                            $emp_q = mysqli_query($connect_main, "SELECT * FROM `materials` WHERE `materials`.`category_id`=1");
                                            $res = mysqli_fetch_all($emp_q);
                                            foreach ($res as $item) {
                                                echo("<option value=$item[0]>$item[1]</option>");
                                            }?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="three" class="form-label">Количество</label>
                                        <input class="form-control" type="text" id="two" name="count">                   
                                    </div>
                                    <div class="mb-3">
                                        <label for="six" class="form-label">Цена</label>
                                        <input class="form-control" type="text" id="three" name="price">                   
                                    </div>
                                    <input type="text" id="four" name="service_list_id" visibility: hidden value="<?=$service_list_id?>">
                                    <button type="submit" class="btn btn-primary" name="bbtn" id="btn-add">Добавить</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                                </form>
                            </div>

                            <!-- goods -->
                            <div class="tab-pane fade mb-3" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <form action="processing/sc_goods.php" method="POST">
                                    <div class="mb-3">
                                        <label for="one" class="form-label">Товар</label>
                                        <select class="form-select" name="goods" id="one" >
                                            <?php 
                                            $emp_q = mysqli_query($connect_main, "SELECT
                                            *
                                        FROM
                                            `materials`
                                        INNER JOIN `stock_balances` ON `stock_balances`.`material_id`=`materials`.`id`
                                        WHERE `stock_balances`.`count` != 0 AND `materials`.`category_id` != 1");
                                            $res = mysqli_fetch_all($emp_q);
                                            foreach ($res as $item) {
                                                echo("<option value=$item[0]>$item[1]</option>");
                                            }?>
                                        </select>
                                    </div>    
                                    <div class="mb-3">
                                        <label for="three" class="form-label">Количество</label>
                                        <input class="form-control" type="text" id="two" name="count">                   
                                    </div>
                                    <div class="mb-3">
                                        <label for="six" class="form-label">Цена</label>
                                        <input class="form-control" type="text" id="three" name="price">                   
                                    </div>
                                    <input type="text" id="four" name="service_list_id" visibility: hidden value="<?=$service_list_id?>">  
                                    <button type="submit" class="btn btn-primary" name="bbtn" id="goods_add">Добавить</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                                </form>
                            </div>

                        </div>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- НОВАЯ УСЛУГА -->
    <div class="modal fade" id="ServiceModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Добавить новую услугу</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"> 
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="two" class="form-label">Название</label>
                            <input class="form-control" type="text" id="two" name="newservice">                                          
                        </div>
                        <div class="mb-3">
                            <label for="one" class="form-label">Категория</label>
                            <select class="form-select" aria-label="Default select example" name="category" id="one">
                                <?php 
                                    $emp_q = mysqli_query($connect_main, "SELECT * FROM `category`");
                                    $res = mysqli_fetch_all($emp_q);
                                    foreach ($res as $item) {
                                        echo("<option value=$item[0]>$item[1]</option>");
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="pp" class="form-label">Цена</label>
                            <input class="form-control" type="text" id="pp" name="price">                                          
                        </div>
                        <button type="submit" class="btn btn-primary" name="service-add">Добавить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- price list -->
    <div class="modal fade modal-dialog-scrollable " id="priceList" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Прайс-лист</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"> 
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#service" type="button" role="tab" aria-controls="home" aria-selected="true">Услуги</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#goods" type="button" role="tab" aria-controls="profile" aria-selected="false">Товары</button>
                        </li>
                    </ul>
                    <div class="tab-content mt-2" id="myTabContent">
                        <!-- прайс услуги -->
                        <div class="tab-pane fade show active" id="service" role="tabpanel" aria-labelledby="home-tab">
                            <div>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Название услуги</th>
                                            <th>Цена за единицу</th>
                                        </tr>
                                    </thead>
                                    <?php foreach($service_price as $item) {?>
                                        <tr>
                                            <td><?= $item[1]?></td>
                                            <td> <?= $item[2]?> 
                                            <a href ="?service_list_id=<?=$service_list_id?>&mat_del=<?= $item[0]?>" onclick="return confirm('Вы уверены, что хотите удалить эту запись? <?php echo($item[0]) ?>')"><img src="assets/pictures/any/delete.png" data-bs-toggle="tooltip" data-bs-placement="left" title="Удалить эту запись" alt="" width="20" height="20" class="d-inline-block float-end"></a>
                                            <a href="edits/price_edits.php?id=<?= $item[0] ?>&list_id=<?=$service_list_id?>"><img src="assets/pictures/any/edit.png" alt="" width="20" height="20" class="d-inline-block float-end"></a>
                                            </td>
                                        </tr>
                                    <?php }?>
                                </table>
                            </div>
                            <a data-bs-toggle="modal" data-bs-target="#ServiceModal"><button class="btn btn-primary">Добавить</button></a>
                        </div>
                        
                        <!-- прайс товары -->
                        <div class="tab-pane fade" id="goods" role="tabpanel" aria-labelledby="profile-tab">
                            <div>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Название товара</th>
                                            <th>Цена за единицу</th>
                                        </tr>
                                    </thead>
                                    <?php foreach($goods_price as $item) {?>
                                        <tr>
                                            <td><?= $item[1]?></td>
                                            <td> <?= $item[2]?> 
                                            <a href ="?service_list_id=<?=$service_list_id?>&mat_del=<?= $item[0]?>" onclick="return confirm('Вы уверены, что хотите удалить эту запись? <?php echo($item[0]) ?>')"><img src="assets/pictures/any/delete.png" data-bs-toggle="tooltip" data-bs-placement="left" title="Удалить эту запись" alt="" width="20" height="20" class="d-inline-block float-end"></a>
                                            <a href="edits/priceg_edits.php?id=<?= $item[0] ?>&list_id=<?=$service_list_id?>"><img src="assets/pictures/any/edit.png" alt="" width="20" height="20" class="d-inline-block float-end"></a>
                                            </td>
                                        </tr>
                                    <?php }?>
                                </table>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>


    
    <script src="scripts/service_content_fields_check.js"></script>
</body>
</html>