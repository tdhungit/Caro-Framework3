<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Helpers\Auth;


use TrueCustomer\Helpers\Utils\TCUtils;

class TCConfigACLAccess
{
    const CACHE_GROUP_PATH = '/app/cache/groups/';
    const CACHE_PERMISSION_PATH = '/app/cache/permissions/';

    const ALL = 99;
    const GROUP = 90;
    const CHILD = 80;
    const OWNER = 10;

    public static $access_types = array(
        'list' => 'List',
        'view' => 'View',
        'edit' => 'Edit',
        'delete' => 'Delete'
    );

    public static $accesses = array(
        TCConfigACLAccess::OWNER => 'Owner',
        TCConfigACLAccess::CHILD => 'Children',
        TCConfigACLAccess::GROUP => 'Group',
        TCConfigACLAccess::ALL => 'All',
    );

    public static $access_group_action = array(
        'List',
        'View',
        'Edit',
        'Approve',
        'Delete'
    );

    public static function accessActions()
    {
        return TCUtils::get_array4file('/app/config/access_actions.php');
    }
}