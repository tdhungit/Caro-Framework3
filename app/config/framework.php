<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

return [
    'namespace' => [
        'TrueCustomer\Common' => APP_PATH . '/app/common/',
        'TrueCustomer\Controllers' => APP_PATH . '/app/controllers/',
        'TrueCustomer\Models\Base' => APP_PATH . '/app/models/base/',
        'TrueCustomer\Models' => APP_PATH . '/app/models/',
        'TrueCustomer\Helpers' => APP_PATH . '/app/helpers/',
        // libraries
        'TrueCustomer\Libraries' => APP_PATH . '/app/libraries/',
        'Moment' => APP_PATH . '/app/libraries/Moment/',
    ],
    'volt_function' => [ // volt_function_name => php_function_name
        'in_array' => 'in_array',
        'attribute' => function($resolvedArgs, $exprArgs) {
            return vsprintf('%s->{%s}', explode(', ', $resolvedArgs));
        },
        'remove_space' => function($str) {
            return str_replace(' ', '-', $str);
        },
    ]
];