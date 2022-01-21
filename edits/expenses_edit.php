
<?php
    session_start();
    require_once('../php/connect.php');
    if (!$_SESSION['user']) { header('Location: ../index.php'); }
    require_once('../php/connect-main.php');

    $id = $_GET['id'];
    
    $data = mysqli_fetch_all(mysqli_query($connect_main, "SELECT * FROM `expenses` WHERE `expenses`.`id`='$id'"));

    if (isset($_POST['bbtn'])){
        $old_mat = $data[0][1];
        $old_count=$data[0][2];
        $mat = $_POST['material'];
        $count = $_POST['count'];
        $employee = $_POST['employee'];
        $date = $_POST['date'];
        $purpose = $_POST['purpose'];
    
        mysqli_begin_transaction($connect_main);
        try{
            $c=mysqli_fetch_assoc(mysqli_query($connect_main, "SELECT `count`, `material_id` as mat_id FROM `stock_balances` WHERE `stock_balances`.`material_id`='$mat'"));
            if ($c['count'] < $count) throw new mysqli_sql_exception();
            if ($old_mat != $mat) { // если материал разный
                mysqli_query($connect_main, "UPDATE `stock_balances` SET `count`=`count`+'$old_count' WHERE `stock_balances`.`material_id`='$old_mat'"); // вернуть старому материалу
                mysqli_query($connect_main, "UPDATE `stock_balances` SET `count`=`count`-'$count' WHERE `stock_balances`.`material_id`='$mat'"); // отнять у нового материала
            }
            else{ // если меняется только количество
                mysqli_query($connect_main, "UPDATE `stock_balances` SET `count`=`count`+'$old_count' WHERE `stock_balances`.`material_id`='$mat'");
                mysqli_query($connect_main, "UPDATE `stock_balances` SET `count`=`count`-'$count' WHERE `stock_balances`.`material_id`='$mat'");                
            }
            $add_text = "UPDATE `expenses` SET `material_id`='$mat', `count`='$count', `employee_id`='$employee', `date`='$date', `purpose`='$purpose'  WHERE `expenses`.`id`='$id'";
            mysqli_query($connect_main, $add_text);
            mysqli_commit($connect_main);
            header('Location: ../expenses.php');
        }
        catch(mysqli_sql_exception $exeption){ 
            $_SESSION['error'] = TRUE;
            mysqli_rollback($connect_main);
            header('Location: ../expenses.php');
        }
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

        <div class="container-md">
            <div class="mt-4 mb-4">
                <form action="" method="POST" id="my_form">
                    <div class="mb-3">
                        <label for="one" class="form-label">Выберите материал</label>
                        <select class="form-select" aria-label="Default select example" name="material" id="one">
                            <?php 
                                $emp_q = mysqli_query($connect_main, "SELECT * FROM `materials` INNER JOIN `stock_balances` ON `materials`.`id`=`stock_balances`.`material_id` WHERE `stock_balances`.`count` != 0");
                                $res = mysqli_fetch_all($emp_q);
                                foreach ($res as $item) {
                                    if ($data[0][1]==$item[0])  echo("<option selected value=$item[0]>$item[1]</option>");
                                    else echo("<option value=$item[0]>$item[1]</option>");
                                }
                            ?>
                        </select>
                        
                    </div>
                    <div class="mb-3">
                        <label for="two" class="form-label">Количество</label>
                        <input class="form-control" type="text" id="two" name="count" value="<?=$data[0][2]?>">                   
                    </div>
                    <div class="mb-3">
                        <label for="three" class="form-label">Кому было выдано</label>
                        <select class="form-select" aria-label="Default select example" name="employee" id="three">
                            <?php 
                                $emp_q = mysqli_query($connect_main, "SELECT * FROM `employee`");
                                $res = mysqli_fetch_all($emp_q);
                                foreach ($res as $item) {
                                    if ($item[0] == $data[0][3]) echo("<option selected value=$item[0]>$item[1] $item[2]</option>");
                                    else echo("<option value=$item[0]>$item[1] $item[2]</option>");
                                }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="four" class="form-label">Дата выдачи</label>
                        <input class="form-control" type="date" id="four" name="date" value="<?=$data[0][4]?>">                   
                    </div>
                    <div class="mb-3">
                        <label for="five" class="form-label">Назначение</label>
                        <input class="form-control" type="text" id="five" name="purpose" value="<?=$data[0][5]?>">                   
                    </div>
                    <button type="submit" class="btn btn-primary" name="bbtn" id="btn_add">Изменить</button>
                    <div class="warn" id="warning"></div>
                    
                </form>
            </div>
    </section>

</body>
</html>