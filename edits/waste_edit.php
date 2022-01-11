
<?php
    session_start();
    require_once('../php/connect.php');
    if (!$_SESSION['user']) { header('Location: ../index.php'); }
    require_once('../php/connect-main.php');

    $id = $_GET['id'];
    
    $data = mysqli_fetch_assoc(mysqli_query($connect_main, "SELECT * FROM `waste` WHERE `waste`.`id`='$id'"));

    if (isset($_POST['bbtn'])){
        $name = $_POST['name'];
        $amount = $_POST['count'];
        $measure = $_POST['measure'];
        $waste_list_id = $_POST['waste_list_id'];
        print_r($_POST);
        mysqli_query($connect_main, "UPDATE `waste` SET `name`='$name', `amount`='$amount', `measure`='$measure', `waste_list_id`='$waste_list_id' WHERE `waste`.`id`='$id'") or die(mysqli_error($connect_main));
        header("Location: ../waste_content.php?waste_list_id=$waste_list_id");
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
                        <label for="one" class="form-label">Название</label>
                        <input class="form-control" type="text" id="one" name="name" value="<?=$data['name']?>">                   
                    </div>
                    <div class="mb-3">
                        <label for="three" class="form-label">Количество</label>
                        <input class="form-control" type="text" id="two" name="count" value="<?=$data['amount']?>">                   
                    </div>
                    <div class="mb-3">
                        <label for="six" class="form-label">Измерение</label>
                        <input class="form-control" type="text" id="three" name="measure" value="<?=$data['measure']?>">                   
                    </div>
                    <input type="text" id="four" name="waste_list_id" visibility: hidden value="<?=$data['waste_list_id']?>">  
                    <button type="submit" class="btn btn-primary" name="bbtn" id="btn_add">Изменить</button>
                    <div class="warn" id="warning"></div>
                </form>
            </div>
        </div>
    </section>

</body>
</html>