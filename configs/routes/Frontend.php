<?php

use \Phalcon\Mvc\Router,
    \Phalcon\Mvc\Router\Group as RouterGroup;

$router = new Router(false);
$router->setDefaultModule(SITENAME);
$router->removeExtraSlashes(true);
$router->setUriSource(Router::URI_SOURCE_SERVER_REQUEST_URI);


$router->add('/', array(
    'controller'    => 'Index',
    'action'        => 'index'
))->setName('homepage');

$router->add('/testMenu/{store_id:[a-z0-9\-_A-Z]+}', array(
    'controller'    => 'Index',
    'action'        => 'testMenu'
))->setName('homepage-test');

$router->add('/menu/ajax/{store_id:[a-z0-9\-_A-Z]+}', array(
    'controller'    => 'Index',
    'action'        => 'menuAjax'
))->setName('homepage-ajax');

/*
  Orders
*/

$router->add('/order/{order_id:[a-z0-9\-_A-Z]+}', array(
    'controller'    => 'Order',
    'action'        => 'index'
))->setName('order');

$router->add('/order/{order_id:[a-z0-9\-_A-Z]+}/{drink_id:[a-z0-9\-_A-Z]+}/{coldheat_id:[a-z0-9\-_A-Z]+}', array(
    'controller'    => 'Order',
    'action'        => 'orderDrink'
))->setName('order-drink');

$router->add('/order/{order_id:[a-z0-9\-_A-Z]+}/overview', array(
    'controller'    => 'Order',
    'action'        => 'orderOverview'
))->setName('order');


$router->add('/order/add-drink', array(
    'controller'    => 'Order',
    'action'        => 'addDrink'
))->setName('order-add-drink');

/*
  resouces
*/
$router->add('/resource/stores', array(
    'controller'    => 'Resource',
    'action'        => 'stores'
))->setName('resouce-stores');

$router->add('/resource/oStore/{order_id:[a-z0-9\-_A-Z]+}', array(
    'controller'    => 'Resource',
    'action'        => 'orderStore'
))->setName('resouce-order-store');

$router->add('/resource/oDrink/{drink_id:[a-z0-9\-_A-Z]+}/{coldheat_id:[a-z0-9\-_A-Z]+}', array(
    'controller'    => 'Resource',
    'action'        => 'orderDrink',
))->setName('resouce-drink-detail');

$router->add('/resource/oDrinkList/{drink_id:[a-z0-9\-_A-Z]+}', array(
    'controller'    => 'Resource',
    'action'        => 'orderDrinkList',
))->setName('resouce-drink-detail');


$router->add('/order/hook/{store_id:[a-z0-9\-_A-Z]+}', array(
    'controller'    => 'Order',
    'action'        => 'hookOrder'
))->setName('order-hook');




$router->notFound(array(
    'controller'    => 'Index',
    'action'        => 'notFound'
));

