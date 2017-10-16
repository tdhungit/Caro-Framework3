<?php
// Auto Generate by TrueCustomer Builder

namespace TrueCustomer\Models;


use TrueCustomer\Helpers\Utils\TCUtils;
use TrueCustomer\Helpers\Auth\TCConfigACLAccess;
use TrueCustomer\Models\Base\AuthPermissionsBase;

class AuthPermissions extends AuthPermissionsBase 
{
    public $studio = false;
    public $audit = false;
    public $notify = false;

    /**
     * @param $role_id
     * @return mixed
     */
    public static function getPermissions($role_id)
    {
        return TCUtils::get_array4file(TCConfigACLAccess::CACHE_PERMISSION_PATH . "{$role_id}.php");
    }

    /**
     * @param $role_id
     * @return array
     */
    public function getDBPermissions($role_id)
    {
        $permissions_array = array();

        $permissions = $this->getMany("auth_role_id = $role_id");

        /* @var $permission AuthPermissions */
        foreach ($permissions as $permission) {
            $permissions_array[$permission->name][$permission->type] = $permission->access;
        }

        return $permissions_array;
    }

    /**
     * @param $role_id
     */
    public function cachePermissions($role_id)
    {
        $permissions = $this->getDBPermissions($role_id);
        TCUtils::write_array2file(TCConfigACLAccess::CACHE_PERMISSION_PATH . "{$role_id}.php", $permissions);
    }
}