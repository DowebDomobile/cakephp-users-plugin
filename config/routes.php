<?php
/**
 * This file is part of the CakePHP(tm) Users plugin package.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) DowebDomobile (http://dowebdomobile.ru)
 * @link          https://github.com/DowebDomobile/cakephp-users-plugin CakePHP(tm) Users plugin project
 * @since         0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Routing\Router;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Route\DashedRoute;

//Router::plugin(
//        'Dwdm/Users',
//        ['path' => '/users'],
//        function (RouteBuilder $routes) {
//            $routes->prefix(
//                'api',
//                function (RouteBuilder $routes) {
//                    $routes->extensions(['json']);
//                    $routes->resources(
//                        'Users',
//                        [
//                            'inflect' => 'dasherize',
//                            'map' => [
//                                'register' => ['method' => 'POST', 'action' => 'register'],
//                                'confirm' => ['method' => 'POST', 'action' => 'confirm'],
//                                'login' => ['method' => 'POST', 'action' => 'login'],
//                            ]
//                        ]
//                    );
//                    $routes->fallbacks('DashedRoute');
//                }
//            );
//            $routes->connect('/logout', ['controller' => 'Users', 'action' => 'logout'], ['_name' => 'logout']);
//            $routes->connect('/login', ['controller' => 'Users', 'action' => 'login'], ['_name' => 'login']);
//
//            $routes->fallbacks('DashedRoute');
//        }
//);
Router::defaultRouteClass(DashedRoute::class);

Router::scope(
    '/',
    function (RouteBuilder $routes) {
        $routes->connect('/', ['controller' => 'Users', 'action' => 'login']);
        $routes->fallbacks(DashedRoute::class);
    }
);
