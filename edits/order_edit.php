<?php 
    session_start();
    require_once('../php/connect-main.php');

    $id = $_GET['id'];

    $data = mysqli_fetch_all(mysqli_query($connect_main, "SELECT * FROM `orders` WHERE `orders`.`id`='$id'") );


    if (isset($_POST['bbtn'])){

        $number = $_POST['number'];
        $date = $_POST['date'];
        $r_date = $_POST['r_date'];
        $vendor = $_POST['vendor'];
        $employee = $_POST['emp'];

        $q_text = "UPDATE `orders` SET `number`='$number', `vendor_id`='$vendor', `date`='$date', `receipt_date`='$r_date', `employee_id`='$employee' WHERE `orders`.`id`='$id'";
        mysqli_query($connect_main, $q_text) or die(mysqli_error($connect_main));
        header('Location: ../orders.php');
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
                <label for="one" class="form-label">Номер заказа</label>
                <input class="form-control" type="text" id="one" name="number" value="<?= $data[0][1]?>">                   
            </div>
            <div class="mb-3">
                <label for="two" class="form-label">Выберите поставщика</label>
                <select class="form-select" aria-label="Default select example" name="vendor" id="two">

                    <?php 
                        $emp_q = mysqli_query($connect_main, "SELECT * FROM `vendor`");
                        $res = mysqli_fetch_all($emp_q);
                        foreach ($res as $item) {
                            if ($item[0] == $data[0][2]) echo("<option selected value=$item[0]>$item[1]</option>");
                            else echo("<option value=$item[0]>$item[1]</option>");
                        }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="three" class="form-label">Дата заказа</label>
                <input class="form-control" type="date" id="three" name="date"  value="<?= $data[0][3]?>">                   
            </div>
            <div class="mb-3">
                <label for="four" class="form-label">Дата получения</label>
                <input class="form-control" type="date" id="four" name="r_date" value="<?= $data[0][4]?>">                   
            </div>
            <div class="mb-3">
                <label for="five" class="form-label">Выберите сотрудника</label>
                <select class="form-select" aria-label="Default select example" name="emp" id="five">
                    <?php 
                        $emp_q = mysqli_query($connect_main, "SELECT * FROM `employee` INNER JOIN `position` ON `employee`.`position`=`position`.`id` WHERE `position`.`id`='3'") or die(mysqli_error($connect_main));
                        $res = mysqli_fetch_all($emp_q);
                        foreach ($res as $item) {
                            if ($item == $data[0][5]) echo("<option selected value=$item[0]>$item[1] $item[2]</option>");
                            else echo("<option value=$item[0]>$item[1] $item[2]</option>");
                        }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="bbtn" id="btn_add">Изменить</button>

            <div class="warn" id="warning"></div>
        </form>
    </div>
</body>
</html>