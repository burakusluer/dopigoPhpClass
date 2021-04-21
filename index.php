<?php
include_once "dopigo.php";
$dopigoAPI=new dopigo();
$data_products=$dopigoAPI->getProducts();
$data_orders=$dopigoAPI->getOrders();


//<editor-fold desc="product">
//$product_list = dopigo::dopigo_product_parser($data_products->results);//unutma ürünleride orderlarıda parse eden
// fonksiyon esasen ->results ı parametre olarak alıyor
///intellisense yardımı alabilmen üçün
//foreach ($product_list as $item) {
//    if (is_a($item,"Dopigo_product")){
//        echo "<pre>";
//        print_r($item);
//        echo "</pre>";

//        echo "<br/>".$item->getName();


//    }
//}
///intellisense yardımı alabilmen üçün

//echo "<pre>";
//print_r($data_orders);
//echo "</pre>";
//</editor-fold>

$parsedOrders=dopigo::dopigo_order_parser($data_orders->results);
echo gettype($parsedOrders);
echo "<pre>";
print_r($parsedOrders);
echo "</pre>";