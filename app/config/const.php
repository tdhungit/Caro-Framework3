<?php
/**
 * Created by Caro Team (carodev.com).
 * User: Jacky (jacky@youaddon.com).
 * Year: 2017
 * File: const.php
 */

$app_list_string['theme_list'] = [
    'default' => 'Default'
];
$app_list_string['status_list'] = [
    'Active' => 'Active',
    'Inactive' => 'Inactive'
];
$app_list_string['boolean_list'] = [
    '1' => 'Yes',
    '0' => 'No'
];
$app_list_string['date_format_list'] = [
    '' => '',
    'd/m/Y' => 'd/m/Y',
    'Y-m-d' => 'Y-m-d',
];
$app_list_string['date_time_format_list'] = [
    '' => '',
    'd/m/Y H:i:s' => 'd/m/Y H:i:s',
    'Y-m-d H:i:s' => 'Y-m-d H:i:s',
];
$app_list_string['number_format_list'] = [
    '' => '',
    ',' => ',',
    '.' => '.'
];

// Activities
$app_list_string['activity_type_list'] = [
    'Meeting' => 'Meeting',
    'Call' => 'Call',
    'Mobile Call' => 'Mobile Call'
];
$app_list_string['activity_status_list'] = [
    'Planed' => 'Planed',
    'Held' => 'Held',
    'No Held' => 'No Held'
];
$app_list_string['activity_priority_list'] = [
    'Normal' => 'Normal',
    'High' => 'High'
];

// Customers
$app_list_string['customer_type_list'] = [
    'Organization' => 'Organization',
    'Personal' => 'Personal'
];
$app_list_string['customer_city_list'] = 'address_province_list';
$app_list_string['customer_state_list'] = 'address_district_list';
$app_list_string['customer_address2_list'] = 'address_ward_list';

// Contacts
$app_list_string['contact_salutation_list'] = [
    'Mr' => 'Mr',
    'Ms' => 'Ms',
];
$app_list_string['contact_relate_list'] = [
    'Friend' => 'Friend',
    'Wife' => 'Wife',
    'Children' => 'Children',
    'Relationship' => 'Relationship',
];

return [
    'app_list_strings' => $app_list_string,
];
