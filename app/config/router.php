<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

return array(
    // Default
    '/:controller/:action/:params' => [
        'namespace' => 'TrueCustomer\Controllers',
        'controller' => 1,
        'action' => 2,
        'params' => 3
    ],
    '/:controller' => [
        'namespace' => 'TrueCustomer\Controllers',
        'controller' => 1,
        'action' => 'index'
    ],

    // API
    '/api/:controller/:action/:params' => [
        'namespace' => 'TrueCustomer\Controllers\API',
        'controller' => 1,
        'action' => 2,
        'params' => 3
    ],
    '/api/:controller' => [
        'namespace' => 'TrueCustomer\Controllers\API',
        'controller' => 1,
        'action' => 'index'
    ],

    // Custom
    '/' => [
        'namespace' => 'TrueCustomer\Controllers',
        'controller' => 'dashboard',
        'action' => 'index'
    ],
    '/login' => [
        'namespace' => 'TrueCustomer\Controllers',
        'controller' => 'index',
        'action' => 'index'
    ],
);
