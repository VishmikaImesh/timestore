<?php

require(BASE."/config/connection.php");

$period = $_POST['revenuePeriod'];

$revenue_rs;
$date;

if ($period == 'week') {

    $revenue_rs = Database::search("SELECT DATE(`invoice_date`) as date,SUM(product_price*qty) AS total FROM `invoice` JOIN `invoice_items` ON `invoice`.`invoice_id`=`invoice_items`.`invoice_id`
    WHERE `invoice_date` >= CURDATE() - INTERVAL 7 DAY 
    AND `invoice_date` < CURDATE() + INTERVAL 1 DAY
    GROUP BY `date`
    order BY `date`");

} else {
    $revenue_rs = Database::search("SELECT DATE(`invoice_date`) as date,SUM(product_price*qty) AS total FROM `invoice` JOIN `invoice_items` ON `invoice`.`invoice_id`=`invoice_items`.`invoice_id`
    WHERE `invoice_date` >=DATE_FORMAT(NOW(),'%Y-%m-01')
    GROUP BY `date`
    order BY `date`");
}

$revenues = [];
$dates=[];

while ($revenue_data = $revenue_rs->fetch_assoc()) {
    $dates[] = $revenue_data['date'];
    $revenues[] = $revenue_data['total'];
}

echo json_encode([
    "dates"=>$dates,
    "revenues"=>$revenues
]);
