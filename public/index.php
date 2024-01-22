<?php

declare(strict_types=1);

// //"Root\\Html\\": "src/"

ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');
ini_set('log_errors', 'On');
ini_set('html_errors', 'On');
// error_reporting(\E_ALL | \E_STRICT );
error_reporting(\E_ALL & ~\E_NOTICE & ~\E_DEPRECATED & ~\E_WARNING);

require __DIR__ . '/../vendor/autoload.php';


use src\Container;
use src\Order;


$container = new Container(); 
/*
$container->set(Invoice::class, function(Container $c){
    return new Invoice(
        $c->get(Log::class)
    );
});
$container->set(Log::class, fn() => new Log());
$container->set(Product::class, fn() => new Product());
$container->set(Order::class, function(Container $c){
    return new Order(
        $c->get(Product::class),
        $c->get(Invoice::class)
    );
});

*/
try {
    //code...
    $order = $container->get(Order::class);
echo '<pre style="background-color: #f5f5f5; padding: 10px; border: 1px solid #ccc;">';
var_dump($order->teszt());
echo '</pre>';
exit();
} catch (\Exception $th) {
    //throw $th;
    die($th->getMessage());
}

