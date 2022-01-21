<!-- СОДЕРЖАНИЕ ЗАКАЗОВ -->
<?php
    session_start();
    require_once('../php/connect.php');
    if (!$_SESSION['user']) { header('Location: ../index.php'); }
    require_once('../php/connect-main.php');
    if (isset($_GET['order_id'])) {$order_id = $_GET['order_id']; }
    $id = $_GET['id'];

    //add new mat
    if (isset($_POST['newmaterial'])) {
        $newm = $_POST['newmaterial'];

        $q_text = "INSERT INTO `materials` (`name`) VALUES ('$newm')";
        mysqli_query($connect_main, $q_text) or die(mysqli_error($connect_main));
        header("Location: ../edit.php?utable=orders_content&id=<?= $item[0]?>&order_id=<?=$order_id?>");
    }

    $select_text="SELECT 
	`orders_content`.`id` AS id,
    `materials`.`name` AS name,
    `orders_content`.`count` AS ccount,
    `orders_content`.`price_one` AS price,
    `orders`.`number` AS num,
    `materials`.`id` AS matid
    FROM
        `orders_content`
    INNER JOIN `materials` ON `materials`.`id`=`orders_content`.`material_id`
    INNER JOIN `orders` ON `orders`.`id`=`orders_content`.`orders_id`
    WHERE `orders_content`.`id`='$id'";

    $select_query = mysqli_query($connect_main, $select_text);
    $select = mysqli_fetch_assoc($select_query);
    $mat_id = $select['matid'];

    if (isset($_POST['bbtn'])){
        $newMatId=$_POST['newMatId'];
        $count=$_POST['count'];
        $price=$_POST['price'];      

        if ($newMatId != $mat_id) // если был выбран новый материал
        {
            $stockUpdate="UPDATE `stock_balances` SET `count`=`count`-'$count' WHERE `stock_balances`.`material_id`='$mat_id'"; // вычитаем ошибочный приход количество у старого материала
            mysqli_query($connect_main, $stockUpdate);

            $existQuery = "SELECT * 
            FROM 
            `stock_balances` 
            WHERE `stock_balances`.`material_id`='$newMatId'";
            $exists=mysqli_num_rows(mysqli_query($connect_main, $existQuery)); // проверяем, есть ли у нас новый материал уже на складе (если ли такая строка)

            if ($exists != 0) {$update_text="UPDATE `stock_balances` SET `count`=`count`+'$count' WHERE `stock_balances`.`material_id`='$newMatId'";} // если есть - апдейт, иначе инсерт
            else {$update_text="INSERT INTO `stock_balances` (`material_id`, `count`) VALUES ('$newMatId', '$count')";}
            mysqli_query($connect_main, $update_text);       
        }
        else{ // если были изменены другие характеристики
            $change = $count - $select['ccount'];
            $stockUpdate="UPDATE `stock_balances` SET `count`=`count`+'$change' WHERE `stock_balances`.`material_id`='$mat_id'";
            mysqli_query($connect_main, $stockUpdate);
        }

        $u_text="UPDATE `orders_content` SET `material_id`='$newMatId', `count`='$count', `price_one`='$price' WHERE `orders_content`.`id`='$id' ";
        mysqli_query($connect_main, $u_text) or die(mysqli_error($connect_main));
        header("Location: ../orders_content.php?order_id=$order_id");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="../assets/pictures/icon/icon.ico" rel="icon" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles/stl.css">   
    <title>Изменение</title>
</head>
<body>

<?php 
            switch($_SESSION['user']['position']){
                case 1: // администратор
                    include('../navbars/engineer.php');
                    break;
    
                case 2: // главный инженер
                    include('../navbars/engineer.php');
                    break;
                
                case 3: // кладовщик
                    include('../navbars/storekeeper.php');
                    break;
    
                case 4: // кассир
                    include('../navbars/cashier.php');
                    break;
    
                case 5: // бухгалтер
                    include('../navbars/accountant.php');
                    break;
    
            }
    ?>


    <section>
        <!-- order content -->
        <div class="container-md">
            <div class="mt-4 mb-4">
            <form action="" method="POST">
                    <div class="mb-3">
                        <label for="srv" class="form-label">Выберите материал</label>
                        <select class="form-select" aria-label="Default select example" name="newMatId" id="srv" >     
                            <?php 
                                $mat = $select['name'];
                                $emp_q = mysqli_query($connect_main, "SELECT * FROM `materials`");
                                $res = mysqli_fetch_all($emp_q);
                                foreach ($res as $item) {
                                    if ($item[1] != $mat)
                                        echo("<option value=\"$item[0]\">$item[0] $item[1]</option>");
                                    else{
                                        echo("<option selected value=\"$item[0]\">$item[0] $item[1]</option>");
                                    }
                                }
                            ?>
                        </select>
                        <a href="" class="form-text" data-bs-toggle="modal" data-bs-target="#MaterialModal" data-bs-dismiss="modal">Добавить новый материал</a>
                    </div>
                    <div class="mb-3">
                        <label for="two" class="form-label">Количество</label>
                        <input class="form-control" type="text" id="two" name="count" value="<?=$select['ccount']?>">                   
                    </div>
                    <div class="mb-3">
                        <label for="three" class="form-label">Цена</label>
                        <input class="form-control" type="text" id="three" name="price" value="<?=$select['price']?>">                   
                    </div>
                    <button type="submit" class="btn btn-primary" name="bbtn">Сохранить изменения</button>
                </form>
            </div>
        </div>
    </section>

        <!-- НОВЫЙ МАТЕРИАЛ -->
    <div class="modal fade" id="MaterialModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавление расходов</h5>
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
</body>
</html>