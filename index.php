<?php
include_once "dopigo.php";
$dopigoAPI = new dopigo();
//$data_products=$dopigoAPI->getProducts();
//$data_orders=$dopigoAPI->getOrders();


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

//<editor-fold desc="order">
//$parsedOrders=dopigo::dopigo_order_parser($data_orders->results);
//echo gettype($parsedOrders);
//echo "<pre>";
//print_r($parsedOrders);
//echo "</pre>";
//</editor-fold>

//<editor-fold desc="samples">
//echo "<pre>";
//print_r((new dopigo())->checkCustomer((new DopigoCustomer(16854934))->getId()));//returns bu yolla yoklarsan raw result alırsın
//echo "</pre>
//";
//echo "<br>";
//
//echo "<pre>";
//print_r((new DopigoCustomer(16854934))->dopigo_check_user_exists());// boolean sonuç döndürür
//
//echo "<br>";
//
//
//echo "<pre>";
//print_r(gettype((new DopigoCustomer(168549347))->dopigo_check_user_exists()));// olumsuz sonuc
//echo "</pre>";
//
//
//</editor-fold>



