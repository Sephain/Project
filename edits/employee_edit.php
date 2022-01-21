
<?php
    session_start();
    require_once('../php/connect.php');
    if (!$_SESSION['user']) { header('Location: ../index.php'); }
    require_once('../php/connect-main.php');

    $id = $_GET['id'];

    $select_text="SELECT * FROM `employee`  INNER JOIN `position` ON `employee`.`position`=`position`.`id` WHERE `employee`.`id`=$id";
    $select_query = mysqli_query($connect_main, $select_text);
    $select = mysqli_fetch_assoc($select_query);

    if (isset($_POST['bbtn'])){
        print_r($_POST);
        $name = $_POST['Name'];
        $last_name = $_POST['Last_name'];
        $middle_name = $_POST['middle_name'];
        $adress = $_POST['Adress'];
        $contacts = $_POST['Contacts'];
        $position = $_POST['Position'];
        $salary = $_POST['Salary'];

        mysqli_query($connect_main, "UPDATE `employee` 
        SET `first_name`='$name', `last_name`='$last_name', `middle_name`='$middle_name', `adress`='$adress', `contacts`='$contacts', `position`='$position', `salary`='$salary' 
        WHERE `employee`.`id`=$id");
        header("Location: ../employee.php");
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
                        <label for="one" class="form-label">Имя</label>
                        <input class="form-control" type="text" id="one" name="Name" value="<?=$select['first_name']?>">                   
                    </div>
                    <div class="mb-3">
                        <label for="two" class="form-label">Фамилия</label>
                        <input class="form-control" type="text" id="two" name="Last_name" value="<?=$select['last_name']?>">                   
                    </div>
                    <div class="mb-3">
                        <label for="two" class="form-label">Отчество</label>
                        <input class="form-control" type="text" id="two" name="middle_name" value="<?=$select['middle_name']?>">                   
                    </div>
                    <div class="mb-3">
                        <label for="four" class="form-label">Адрес</label>
                        <input class="form-control" type="text" id="four" name="Adress" value="<?=$select['adress']?>">                   
                    </div>
                    <div class="mb-3">
                        <label for="five" class="form-label">Контакты</label>
                        <input class="form-control" type="text" id="five" name="Contacts" value="<?=$select['contacts']?>">                   
                    </div>
                    <div class="mb-3">
                        <label for="six" class="form-label">Выберите должность</label>
                        <select class="form-select" aria-label="Default select example" name="Position" id="six" value="<?=$select['position']?>">   
                        <option selected value="<?=$select['position']?>" ></option>
                        <?php 
                            $emp_q = mysqli_query($connect_main, "SELECT * FROM `position` WHERE `id` != '1'");
                            $res = mysqli_fetch_all($emp_q);
                            $v = $select['position'];
                            foreach ($res as $item) {
                                if ($item[1] != $select['position']) {
                                    echo("<option value=$item[0]>$item[1]</option>");
                                }
                                else{
                                    echo("<option selected value=$item[0]>$item[1]</option>");
                                }
                                
                            }
                        ?>
                        </select>           
                    </div>
                    <div class="mb-3">
                        <label for="seven" class="form-label">Оклад</label>
                        <input class="form-control" type="text" id="seven" name="Salary" value="<?=$select['salary']?>">                   
                    </div>
                    <button type="submit" class="btn btn-primary" name="bbtn" id="btn_add">Изменить</button>
                    <div class="warn" id="warning"></div>
                </form>
        </div>
    </section>

</body>
</html>