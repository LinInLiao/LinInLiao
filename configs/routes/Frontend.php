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
))->setName('homepage');

$router->add('/menu/ajax/{store_id:[a-z0-9\-_A-Z]+}', array(
    'controller'    => 'Index',
    'action'        => 'menuAjax'
))->setName('homepage');

$router->notFound(array(
    'controller'    => 'Index',
    'action'        => 'notFound'
));
