<?php 
    session_start();
    require_once('../php/connect-main.php');

    $id = $_GET['id'];
    $list_id = $_GET['list_id'];
    print_r($list_id);
    $data = mysqli_fetch_assoc(mysqli_query($connect_main, "SELECT * FROM `service`
    WHERE `id`='$id'"));

    if (isset($_POST['bbtn'])){
        $service = $_POST['service_name'];
        $price = $_POST['Price'];
        
        mysqli_query($connect_main, "UPDATE `service` SET `name`='$service', `price`='$price' WHERE `id`='$id'");
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
                <input type="text" class="form-control" name="service_name" id="one" value="<?=$data['name']?>">
            </div>
            <div class="mb-3">
                <label for="six" class="form-label">Цена за единицу</label>
                <input class="form-control" type="text" id="three" name="Price" value="<?=$data['price']?>">                   
            </div>
            <input type="text" id="four" name="service_list_id" visibility: hidden value="<?=$service_list_id?>">  
            <button type="submit" class="btn btn-primary" name="bbtn" id="btn_add">Изменить</button>
            <div class="warn" id="warning"></div>
        </form>
    </div>
</body>
</html>