<?php
/**
 *
 */
use Cake\Routing\Router;
use Cake\Routing\RouteBuilder;

Router::plugin(
        'Users',
        ['path' => '/users'],
        function (RouteBuilder $routes) {
            $routes->connect('/logout', ['controller' => 'Users', 'action' => 'logout'], ['_name' => 'logout']);
            $routes->connect('/login', ['controller' => 'Users', 'action' => 'login'], ['_name' => 'login']);

            $routes->fallbacks('DashedRoute');
        }
);
