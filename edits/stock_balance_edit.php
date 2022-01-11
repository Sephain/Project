
<?php
    session_start();
    require_once('../php/connect.php');
    if (!$_SESSION['user']) { header('Location: ../index.php'); }
    require_once('../php/connect-main.php');

    $id = $_GET['id'];

    //add new mat
    if (isset($_POST['newmaterial'])) {
        $newm = $_POST['newmaterial'];

        $q_text = "INSERT INTO `materials` (`name`) VALUES ('$newm')";
        mysqli_query($connect_main, $q_text) or die(mysqli_error($connect_main));
        header("Location: ../edits/stock_balance_edit.php?id=$id");
    }

    $data = mysqli_fetch_assoc(mysqli_query($connect_main, "SELECT * FROM `stock_balances` WHERE `stock_balances`.`id`='$id'"));


    if (isset($_POST['bbtn'])){
        $material = $_POST['material'];
        $count = $_POST['count'];

        mysqli_query($connect_main, "UPDATE `stock_balances` SET `material_id`='$material', `count`='$count' WHERE `stock_balances`.`id`='$id'");
        header("Location: ../stock-balances.php");
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
                                    $emp_q = mysqli_query($connect_main, "SELECT * FROM `materials`");
                                    $res = mysqli_fetch_all($emp_q);
                                    foreach ($res as $item) {
                                        if ($item[0] == $data['material_id']) echo("<option selected value=$item[0]>$item[1]</option>");
                                        else echo("<option value=$item[0]>$item[1]</option>");
                                    }
                                ?>
                            </select>
                            <a href="" class="form-text" data-bs-toggle="modal" data-bs-target="#MaterialModal" data-bs-dismiss="modal">Добавить новый материал</a>
                        </div>
                        <div class="mb-3">
                            <label for="two" class="form-label">Количество</label>
                            <input class="form-control" type="text" id="two" name="count" value="<?=$data['count']?>">                   
                        </div>
                        <button type="submit" class="btn btn-primary" name="bbtn" id="btn_add">Изменить</button>
                        <div class="warn" id="warning"></div>
                    </form>
            </div>
        </div>
    </section>
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
</body>
</html>