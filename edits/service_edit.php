<?php 
    session_start();
    require_once('../php/connect-main.php');

    $id = $_GET['id'];

    $data = mysqli_fetch_assoc(mysqli_query($connect_main, "SELECT
    `employee`.`id` as emp_id,
    `clients`.`id` as client_id,
    `service_list`.`date`as ddate
    FROM 
        `service_list`
    INNER JOIN `clients` ON `clients`.`id`=`service_list`.`client_id`
    INNER JOIN `employee` ON `employee`.`id`=`service_list`.`employee_id`
    WHERE `service_list`.`id`='$id'
    ") );


    if (isset($_POST['bbtn'])){
        
        $Employee = $_POST['Employee'];
        $Client = $_POST['Client'];
        $Date = $_POST['Date'];

        $q_text = "UPDATE `service_list` SET `employee_id`='$Employee', `client_id`='$Client', `date`='$Date' WHERE `service_list`.`id`='$id'";
        mysqli_query($connect_main, $q_text) or die(mysqli_error($connect_main));
        header('Location: ../service.php');
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
                <label for="one" class="form-label">Сотрудник</label>
                <select class="form-select" aria-label="Default select example" name="Employee" id="one">
                    <?php 
                        $emp_q = mysqli_query($connect_main, "SELECT * FROM `employee` INNER JOIN `position` ON `employee`.`position`=`position`.`id` WHERE `employee`.`position`='4' OR `employee`.`position`='6'");
                        $res = mysqli_fetch_all($emp_q);
                        foreach ($res as $item) {
                            if ($item[0] == $data['emp_id']) echo("<option selected value=$item[0]>$item[1] $item[2] - $item[8]</option>");  
                            else echo("<option value=$item[0]>$item[1] $item[2] - $item[8]</option>");
                        }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="two" class="form-label">Выберите клиента</label>
                <select class="form-select" aria-label="Default select example" name="Client" id="two">
                    <?php 
                        $emp_q = mysqli_query($connect_main, "SELECT * FROM `clients`");
                        $res = mysqli_fetch_all($emp_q);
                        foreach ($res as $item) {
                            if ($item[0] == $data['client_id']) echo("<option selected value=$item[0]>$item[1] $item[2]</option>");
                            else echo("<option value=$item[0]>$item[1] $item[2]</option>");
                        }
                    ?>
                </select>
                <a href="" class="form-text" data-bs-toggle="modal" data-bs-target="#ClientModal" data-bs-dismiss="modal">Добавить нового клиента</a>
            </div>
            <div class="mb-3">
                <label for="three" class="form-label">Дата оказания услуги</label>
                <input class="form-control" type="date" id="three" name="Date" value="<?=$data['ddate']?>">                   
            </div>
            <button type="submit" class="btn btn-primary" name="bbtn" id="btn_add">Изменить</button>
            <div class="warn" id="warning"></div>
        </form>
    </div>
</body>
</html>