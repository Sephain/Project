
<?php


    session_start();
    require_once('php/connect.php');
    if (!$_SESSION['user']) { header('Location: ../index.php'); }
    require_once('php/connect-main.php');

        if (isset($_POST['bbtn'])) {
            
            mysqli_begin_transaction($connect_main);
            try{
                if ($_POST['startPeriod'] == NULL or $_POST['endPeriod'] == NULL) {throw new mysqli_sql_exception('Заполните поля периода!');}
                $startPeriod = $_POST['startPeriod'];
                $endPeriod=$_POST['endPeriod'];

                $now = DateTime::createFromFormat("Y-m-d", $startPeriod); // начало
                $date = DateTime::createFromFormat("Y-m-d", $endPeriod); // конец
                $interval = $now->diff($date);

                $days = $interval->d;
                $months = $interval->m;
                $years = $interval->y;
                
                $labels =[$startPeriod];
                $incomeSeries = array_fill(0, $days, null);
                $outcomeSeries = array_fill(0, $days, null);

                $ad_counter = -1;
                $main_counter = -1;
                $i = 0;
                while ($labels[$i] != $endPeriod) {
                    $labels[$i+1] = date('Y-m-d', strtotime($labels[$i] .' +1 day'));
                    $i++;
                    if (substr($labels[$i], 8, 2)=='20'){$ad_counter = $i;}
                    if (substr($labels[$i], 8, 2)=='5'){$main_counter = $i;}
                }

                // Доходы 
                $serviceIncome = mysqli_fetch_assoc(mysqli_query($connect_main, "SELECT
                SUM(
                    `service_provision`.`count` * `service_provision`.`price`
                ) AS SUM
                FROM
                    `service_provision`
                INNER JOIN `materials` ON `materials`.`id` = `service_provision`.`service_id`
                INNER JOIN `service_list` ON `service_list`.`id`=`service_provision`.`service_list_id`
                WHERE
                    `materials`.`category_id` = 1 AND `service_list`.`date` >= '$startPeriod' AND `service_list`.`date` <= '$endPeriod'
                "));


                $goodsIncome = mysqli_fetch_assoc(mysqli_query($connect_main, "SELECT
                SUM(
                    `service_provision`.`count` * `service_provision`.`price`
                ) AS SUM
                FROM
                    `service_provision`
                INNER JOIN `materials` ON `materials`.`id`=`service_provision`.`service_id`
                INNER JOIN `service_list` ON `service_list`.`id`=`service_provision`.`service_list_id`
                WHERE `materials`.`category_id`!=1 AND `service_list`.`date` >= '$startPeriod' AND `service_list`.`date` <= '$endPeriod'
                "));

                $incomeTotal = mysqli_fetch_all(mysqli_query($connect_main, 
                "SELECT
                SUM(`service_provision`.`count`*`service_provision`.`price`) as SUM,
                `service_list`.`date` as date
                FROM
                    `service_provision`
                INNER JOIN `service_list` ON `service_provision`.`service_list_id`=`service_list`.`id`
                WHERE `service_list`.`date` >= '$startPeriod' AND `service_list`.`date` <= '$endPeriod'
                GROUP BY `service_list`.`date`"
                ));

                $totalIncome = 0;
                foreach($incomeTotal as $item) {
                    $totalIncome += $item[0];
                }

                // Расходы
                // Заказы
                $outcomeTotal = mysqli_fetch_all(mysqli_query($connect_main, "SELECT

                SUM(`orders_content`.`count`*`orders_content`.`price_one`) as SUM,
                `orders`.`date` as date
                FROM
                    `orders_content`
                INNER JOIN `orders` ON `orders`.`id`=`orders_content`.`orders_id`
                WHERE `orders`.`date` >= '$startPeriod' AND `orders`.`date` <= '$endPeriod' 
                GROUP BY `orders`.`date`"
                ));

                $orderPrice = 0;
                foreach($outcomeTotal as $item) {
                    $orderPrice += $item[0];
                }

                // Зарплата
                $salaryTotal=mysqli_fetch_assoc(mysqli_query($connect_main, "SELECT SUM(`salary`)*1.3 as salary FROM `employee`"));
                $advance_salary = $salaryTotal['salary']*0.4;
                $main_salary = $salaryTotal['salary']-$advance_salary;

                foreach($incomeTotal as $item){
                    $ind = array_search($item[1], $labels);
                    $incomeSeries[$ind] = $item[0];
                }

                foreach($outcomeTotal as $item){
                    $ind = array_search($item[1], $labels);                   
                    $outcomeSeries[$ind] = $item[0];
                }
                
                $totalSalary = 0;
                if ($ad_counter != -1){$outcomeSeries[$ad_counter] += $advance_salary; $totalSalary +=$advance_salary;}
                if ($main_counter != -1){$outcomeSeries[$main_counter] += $main_salary;$totalSalary +=$main_salary;}

                $totalOutcome = 0;
                $totalOutcome = $orderPrice + $totalSalary;
                for ($i=1;$i<count($labels)-1;$i++){
                    $labels[$i] = "";
                }
                $data_ar = [$labels, $incomeSeries, $outcomeSeries];
                $data = json_encode($data_ar);
                file_put_contents('data.json', $data);

                mysqli_commit($connect_main);
            }
            catch(mysqli_sql_exception $e){
                $_SESSION['error'] = True;
                mysqli_rollback($connect_main);
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
    <link rel="stylesheet" href="http://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
    <script src="http://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles/stl.css">   
    <link rel="stylesheet" href="styles/graph.css">  
    
    <title>Отчетность</title>
</head>
<body>

<?php 
            switch($_SESSION['user']['position']){
                case 1: // администратор
                    include('navbars/engineer.php');
                    break;
    
                case 2: // главный инженер
                    include('navbars/engineer.php');
                    break;
                
                case 3: // кладовщик
                    include('navbars/storekeeper.php');
                    break;
    
                case 4: // кассир
                    include('navbars/cashier.php');
                    break;
    
                case 5: // бухгалтер
                    include('navbars/accountant.php');
                    break;
    
            }
    ?>

    <section>
        <div class="container-md mt-4 mb-4">
            <h3>Отчет по финансовым результатам</h3>
            <div class="mt-4 mb-4">
                <p class="fst-italic">Здесь вы можете ознакомиться с итогами деятельности предприятия за выбранный период времени. </p>
                <hr>
            </div>
            <form action="" method="post">
                <div class="row">
                    <div class="col-md-4">
                        <label for="startPeriod" class="form-label">Начало периода</label>
                        <input type="date" class="form-control" id="startPeriod" name="startPeriod" value="<?=$startPeriod?>">
                    </div>
                    <div class="col-md-4">
                        <label for="endPeriod" class="form-label">Конец периода</label>
                        <input type="date" class="form-control" id="endPeriod" name="endPeriod" value="<?=$endPeriod?>">
                    </div>
                    <div class="container mt-4 mb-4">
                        <button class="btn btn-primary" type="submit" name="bbtn">Рассчитать</button>
                        <?php if (isset($_POST['bbtn']) and !isset($_SESSION['error'])){echo("<button class=\"btn btn-primary\" name=\"button\" id=\"btn-chart\">Сформировать график</button>");}?>                        
                    </div>
                    
                </div>
            </form>
            
            <?php if (isset($_SESSION['error'])) {echo("<div class=\"warn text-center\" id=\"warning\">Пожалуйста, заполните поля дат!</div>"); unset($_SESSION['error']);} else {if (isset($_POST['bbtn']) and $_POST['startPeriod'] != NULL){include('php/create_report.php');}}?>

        </div>
    </section>
    
    <script src="scripts/chart.js"></script>
    <script src="scripts/chartist-plugin-legend.js"></script>
</body>
</html>