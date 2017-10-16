<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

return [
    'systems' => [
        'base_url' => [
            'type' => 'text',
            'label' => 'Base Url'
        ],
        'page_title' => [
            'type' => 'text',
            'label' => 'Page Title'
        ],
        'theme' => [
            'type' => 'select',
            'label' => 'Theme',
            'options' => 'theme_list'
        ],
        'logo' => [
            'type' => 'text',
            'label' => 'Logo'
        ],
    ],
    'email' => [
        'email' => [
            'type' => 'text',
            'label' => 'Email'
        ],
    ],
];