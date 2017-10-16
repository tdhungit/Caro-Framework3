<?php
/**
 * Created by Caro Team (carodev.com).
 * User: Jacky (jacky@youaddon.com).
 * Year: 2017
 * File: user_preference.php
 */

return [
    'settings' => [
        'timezone' => [
            'label' => 'Timezone',
            'type' => 'text'
        ],
        'date_format' => [
            'label' => 'Date Format',
            'type' => 'select',
            'options' => 'date_format_list'
        ],
        'date_time_format' => [
            'label' => 'Date Time Format',
            'type' => 'select',
            'options' => 'date_time_format_list'
        ],
        'number_format' => [
            'label' => 'Number Format',
            'type' => 'select',
            'options' => 'number_format_list'
        ],
    ],
];