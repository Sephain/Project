<div class="row">
    <div class="col-md-4">
        <p><b>Доходы</b></p>
        <div class="">Оказание услуг: <b><?= $serviceIncome['SUM']?> </b></div>
        <div class="">Продажа товаров: <b><?= $goodsIncome['SUM']?> </b></div>
        <div class="">Общий доход: <b> <?php if($totalIncome != 0) echo($totalIncome);?> </b></div>

        <p class="mt-4"><b>Расходы</b></p>
        <div class="">Заказы материалов у поставщиков: <b><?= $orderPrice?> </b></div>
        <div class="">Заработная плата: <b><?= $totalSalary?> </b></div>
        <div class="">Общие расходы: <b><?= $totalOutcome?> </b></div>

        <p class="mt-4"><b>Налоговая база</b></p>
        <div class="">Итоговая прибыль: <b><?= $totalIncome-$totalOutcome?> </b></div>
        <div class="">Налог (УСН): <b><?php if(($totalIncome-$totalOutcome)*0.15 > ($totalIncome*0.01)){$tax =($totalIncome-$totalOutcome)*0.15; echo($tax);}else{$tax = ($totalIncome*0.01); echo($tax);}?> </b></div>
        <div class="">Итоговая прибыль (с учетом налога УСН): <b><?= ($totalIncome-$totalOutcome)-$tax?> </b></div> 
    </div>
    <div class="col-md-2"></div>
    <div class="col-md-6">
        <div id ="hate"></div>
        <div class="chart1 ct-chart ct-minor-seventh" style="height: 70%;"></div>
    </div>
</div>


