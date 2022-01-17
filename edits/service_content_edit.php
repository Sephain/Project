<?php 
    session_start();
    require_once('../php/connect-main.php');

    $id = $_GET['id']; // service_provision id
    $service_list_id = $_GET['service_list_id']; 

    // данные товара или услуги до изменения (старые данные)
    $old_q = mysqli_query($connect_main, 
    "SELECT
    `materials`.`id` as mat_id,
    `materials`.`name` AS name,
    `materials`.`category_id` as cat_id,
    `service_provision`.`price` as price,
    `service_provision`.`count` as provision_count 
    FROM
        `materials`
    INNER JOIN `service_provision` ON `service_provision`.`service_id`=`materials`.`id`
    WHERE `service_provision`.`id` = $id") or die(mysqli_error($connect_main));
    $old_data = mysqli_fetch_assoc($old_q);

    $old_material_id = $old_data['mat_id'];
    $old_name = $old_data['name'];
    $old_category = $old_data['cat_id'];
    $old_provision_count = $old_data['provision_count'];
    //$old_stock_count = $old_data['count']; // !!!
    $old_price = $old_data['price'];

    if (isset($_POST['bbtn'])){
        $new_material_id = $_POST['Service'];
        $new_count = $_POST['Count'];
        $new_price = $_POST['Price'];
        
        $new_data = mysqli_fetch_assoc(mysqli_query($connect_main, "SELECT `category_id` as cat_id, `count` as count FROM `materials` INNER JOIN `stock_balances` ON `stock_balances`.`material_id`=`materials`.`id` WHERE `materials`.`id`=$new_material_id"));
        $new_category = $new_data['cat_id'];
        $stock_new_count = $new_data['count'];

        try{
            if ($old_category == $new_category){ // услуга на услугу или товар на товар (категория НЕ меняется)
                if ($new_category == 1){ // если это услуга на услугу
                    
                }
                else{ // если это товар на товар
                    // $old_stock_count = mysqli_fetch_assoc(mysqli_query($connect_main, ""));
                    if ($new_count > $stock_new_count) throw new mysqli_sql_exception();
                    else{
                        mysqli_query($connect_main, "UPDATE `stock_balances` SET `count`=`count`+'$old_provision_count' WHERE `material_id`='$old_material_id'") or die(mysqli_error($connect_main));
                        mysqli_query($connect_main, "UPDATE `stock_balances` SET `count`=`count`-'$new_count' WHERE `material_id`='$new_material_id'") or die(mysqli_error($connect_main));
                    }
                }
            }
            else{ // товар на услугу или услугу на товар (категория МЕНЯЕТСЯ)
                if ($old_category > $new_category){ // товар на услугу
                    mysqli_query($connect_main, "UPDATE `stock_balances` SET `count`=`count`+'$old_provision_count' WHERE `material_id`='$old_material_id'");
                }
                else { // услуга на товар
                    if ($new_count > $stock_new_count) throw new mysqli_sql_exception();
                    else{
                        mysqli_query($connect_main, "UPDATE `stock_balances` SET `count`=`count`-'$new_count' WHERE `material_id`='$new_material_id'");
                    }
                }
            }
            mysqli_query($connect_main, "UPDATE `service_provision` SET `service_id`='$new_material_id', `count`='$new_count', `price`='$new_price' WHERE `service_provision`.`id`='$id'");
            header("Location: ../service_content.php?service_list_id=$service_list_id");
        }
        catch(mysqli_sql_exception $e){
            mysqli_rollback($connect_main);
            $_SESSION['error'] = True;
            header("Location: ../service_content.php?service_list_id=$service_list_id");
        }
    }

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
    <div class="container-md mt-4 mb-4">
        <form action="" method="POST" id="my_form">
            <div class="mb-3">
                <label for="one" class="form-label">Услуга</label>
                <select class="form-select" aria-label="Default select example" name="Service" id="one">
                    <?php 
                        $emp_q = mysqli_query($connect_main, "SELECT * FROM `materials`");
                        $res = mysqli_fetch_all($emp_q);
                        foreach ($res as $item) {
                            if ($item[0] == $old_material_id) echo("<option selected value=$item[0]>$item[1]</option>");
                            else echo("<option value=$item[0]>$item[1]</option>");
                        }
                    ?>
                </select>
                <a href="" class="form-text" data-bs-toggle="modal" data-bs-target="#ServiceModal" data-bs-dismiss="modal">Добавить новую услугу</a>
            </div>
            <div class="mb-3">
                <label for="three" class="form-label">Количество</label>
                <input class="form-control" type="text" id="two" name="Count" value="<?=$old_provision_count?>">                   
            </div>
            <div class="mb-3">
                <label for="six" class="form-label">Цена</label>
                <input class="form-control" type="text" id="three" name="Price" value="<?=$old_price?>">                   
            </div>
            <input type="text" id="four" name="service_list_id" visibility: hidden value="<?=$service_list_id?>">  
            <button type="submit" class="btn btn-primary" name="bbtn" id="btn_add">Изменить</button>
            <div class="warn" id="warning"></div>
        </form>
    </div>
</body>
</html>