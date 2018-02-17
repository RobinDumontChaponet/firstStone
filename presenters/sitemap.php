<?php

$routes = array();

foreach($binder->getRouters() as $router)
    $routes += $router->getRoutes();

$presenter->add('routes', $routes);
