<?php 
    session_start();
    require_once('../php/connect-main.php');

    $id = $_GET['id'];

    $data = mysqli_fetch_assoc(mysqli_query($connect_main, "SELECT
    `service_provision`.`id` as id,
    `service`.`id` as service_id,
    `service_provision`.`count` as ccount,
    `service_provision`.`price` as price,
    `service_list`.`id` as list_id
    FROM 
        `service_provision`
    INNER JOIN `service` ON `service`.`id`=`service_provision`.`service_id`
    INNER JOIN `service_list` ON `service_list`.`id`=`service_provision`.`service_list_id`
    WHERE `service_provision`.`id`='$id'
    ") );

    $list_id = $data['list_id'];
    if (isset($_POST['bbtn'])){
        $service = $_POST['Service'];
        $count = $_POST['Count'];
        $price = $_POST['Price'];
        $order_id = $_POST['order_id'];
        
        mysqli_query($connect_main, "UPDATE `service_provision` SET `service_id`='$service', `count`='$count', `price`='$price' WHERE `service_provision`.`id`='$id'");
        header("Location: ../service_content.php?service_list_id=$list_id");
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
                        $emp_q = mysqli_query($connect_main, "SELECT * FROM `service`");
                        $res = mysqli_fetch_all($emp_q);
                        foreach ($res as $item) {
                            if ($item[0] == $data['service_id']) echo("<option selected value=$item[0]>$item[1]</option>");
                            else echo("<option value=$item[0]>$item[1]</option>");
                        }
                    ?>
                </select>
                <a href="" class="form-text" data-bs-toggle="modal" data-bs-target="#ServiceModal" data-bs-dismiss="modal">Добавить новую услугу</a>
            </div>
            <div class="mb-3">
                <label for="three" class="form-label">Количество</label>
                <input class="form-control" type="text" id="two" name="Count" value="<?=$data['ccount']?>">                   
            </div>
            <div class="mb-3">
                <label for="six" class="form-label">Цена</label>
                <input class="form-control" type="text" id="three" name="Price" value="<?=$data['price']?>">                   
            </div>
            <input type="text" id="four" name="service_list_id" visibility: hidden value="<?=$service_list_id?>">  
            <button type="submit" class="btn btn-primary" name="bbtn" id="btn_add">Изменить</button>
            <div class="warn" id="warning"></div>
        </form>
    </div>
</body>
</html>