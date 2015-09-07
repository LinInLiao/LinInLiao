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


$router->notFound(array(
    'controller'    => 'Index',
    'action'        => 'notFound'
));
