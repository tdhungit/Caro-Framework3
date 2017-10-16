<?php
/**
 * Created by Caro Team (carodev.com).
 * User: Jacky (jacky@youaddon.com).
 * Year: 2017
 * File: Users.php
 */

namespace TrueCustomer\Models;


use TrueCustomer\Helpers\Utils\TCUtils;
use TrueCustomer\Helpers\Auth\TCConfigACLAccess;
use TrueCustomer\Models\Base\UsersBase;

class Users extends UsersBase
{
    public $action_controller_name = 'users';

    /**
     * @param $group_id
     * @param bool $recursive
     * @param bool $sibling
     * @return array
     */
    public static function getDBUsersInGroup($group_id, $recursive = true, $sibling = true)
    {
        $users_group = array(
            'siblings' => array(),
            'children' => array()
        );

        // get users
        $users = Users::find("deleted = 0 AND user_group_id = $group_id");
        foreach ($users as $user) {
            /* @var $user Users */
            if (!$recursive) {
                $users_group[] = $user->id;
            } else {
                if ($sibling) {
                    $users_group['siblings'][] = $user->id;
                } else {
                    $users_group['children'][] = $user->id;
                }
            }
        }

        if (!$recursive) {
            return $users_group;
        }

        // get children group
        /* @var $group UserGroups */
        $group = UserGroups::findFirst("deleted = 0 AND parent_id = $group_id");
        if ($group) {
            $users_group_recursive = Users::getDBUsersInGroup($group->id, true, false);
            $users_group['children'] = array_merge($users_group['children'], $users_group_recursive['children']);
        }

        return $users_group;
    }

    /**
     * @param $group_id
     */
    public static function setCacheUsersInGroup($group_id)
    {
        $users = Users::getDBUsersInGroup($group_id);
        TCUtils::write_array2file(TCConfigACLAccess::CACHE_GROUP_PATH . "{$group_id}.php", $users);
    }

    /**
     * @param $group_id
     * @return mixed
     */
    public static function getUsersInGroup($group_id)
    {
        return TCUtils::get_array4file(TCConfigACLAccess::CACHE_GROUP_PATH . "{$group_id}.php");
    }

    /**
     * @param null|mixed $data
     * @param null|mixed $whiteList
     * @return bool
     */
    public function save($data = null, $whiteList = null)
    {
        $save = parent::save($data, $whiteList);
        Users::setCacheUsersInGroup($this->user_group_id);
        return $save;
    }

    /**
     * @param null|mixed $data
     * @param null|mixed $whiteList
     * @return bool
     */
    public function create($data = null, $whiteList = null)
    {
        $create = parent::create($data, $whiteList);
        Users::setCacheUsersInGroup($this->user_group_id);
        return $create;
    }

    /**
     * @param null|mixed $data
     * @param null|mixed $whiteList
     * @return bool
     */
    public function update($data = null, $whiteList = null)
    {
        $update = parent::update($data, $whiteList);
        Users::setCacheUsersInGroup($this->user_group_id);
        return $update;
    }

}